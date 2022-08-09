<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LoanStatusRequest;
use App\Jobs\ApproveLoanJob;
use App\Jobs\DeclineLoanJob;
use App\Mail\ApproveLoan;
use App\Mail\DeclineLoan;
use App\Repositories\CustomerAuth\CustomerAuthInterface;
use App\Repositories\CustomerLoanStatus\CustomerLoanStatusInterface;
use App\Repositories\LoanStatus\LoanStatusInterface;
use App\Repositories\Auth\AuthInterface;
use App\Repositories\Loan\LoanInterface;
use App\Services\BankOne\Loan\LoanServices;
use App\Services\Paystack\PaystackService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;

class LoanStatusController extends Controller
{

    protected $loan, $auth, $paystackService, $loanStatus, $customerAuth, $customerLoanStatus, $loanServices;
    public function __construct(LoanInterface $loan, AuthInterface $auth, PaystackService $paystackService,
                                LoanStatusInterface $loanStatus, CustomerAuthInterface $customerAuth,
                                CustomerLoanStatusInterface $customerLoanStatus, LoanServices $loanServices)
    {
        $this->auth = $auth;
        $this->loan = $loan;
        $this->paystackService = $paystackService;
        $this->loanStatus = $loanStatus;
        $this->customerAuth = $customerAuth;
        $this->customerLoanStatus = $customerLoanStatus;
        $this->loanServices = $loanServices;
    }

    public function awaitingApproval(Request $request,Builder $builder){

        $data = $this->loan->findByColumn(['loan_status_id'=>loanStatusByName('Awaiting Approval')->id])
            ->with(['customer'])->select('loans.*')->latest();

        if ($request->ajax()){
            return $this->allLoanData($data);
        }

        $html = $builder->columns($this->loanColumns());

        return view('loan.awaiting-approval', compact('data','html'));
    }

    public function declined(Request $request,Builder $builder){

        $data = $this->loan->findByColumn(['loan_status_id'=>loanStatusByName('Declined')->id])
            ->with(['customer'])->select('loans.*')->latest();

        if ($request->ajax()){
            return $this->allLoanData($data);
        }

        $html = $builder->columns($this->loanColumns());

        return view('loan.declined', compact('data','html'));
    }

    public function approved(Request $request,Builder $builder){

        $data = $this->loan->findByColumn(['loan_status_id'=>loanStatusByName('Approved')->id])
            ->with(['customer'])->select('loans.*')->latest();

        if ($request->ajax()){
            return $this->allLoanData($data);
        }

        $html = $builder->columns($this->loanColumns());

        return view('loan.approved', compact('data','html'));
    }

    public function updateStatus(LoanStatusRequest $request){

        $params = $request->all();

        $loan = $this->loan->findById(decrypt($params['loan_id']));

        $customer = $this->customerAuth->findById($loan->customer_id);



        $loanStatus = loanStatusById($params['loan_status'])->name;

        $loanStatusUpdate['user_id'] = $this->auth->authUser()->id;
        $loanStatusUpdate['loan_id'] = $loan->id;
        $loanStatusUpdate['loan_status_id'] = $params['loan_status'];
        $loanStatusUpdate['remark'] = $params['remarks'];

        try {
            DB::beginTransaction();

            if ($loanStatus == 'Approved') {
                $bankOneParams = [
                  'TransactionTrackingRef' => getUniqueToken(5)+$loanStatusUpdate['loan_id'],
                  'LoanProductCode' => $loan->setting->cba_loan_product_code,
                  'CustomerID' => $customer->cba_id,
                  'LinkedAccountNumber' => $customer->nuban,
                  'Tenure' => $loan->term,
                  'Moratorium' => 0,
                  'InterestAccrualCommencementDate' => now(),
                  'Amount' => $loan->amount,
                  'PrincipalPaymentFrequency' => 2,
                  'InterestPaymentFrequency'  => 2
                ];

                //credit applicant account
                $creditAcct =  $this->loanServices->approveLoan($bankOneParams);
                Log::info('Loan Approval Payload', formatLogResponse($creditAcct));
                if (is_array($creditAcct) && $creditAcct['IsSuccessful'] == true) {

//                    $loanSettings = getLoanSettingByColumn(['term' => 15])->first();

//                    $now = now();
//
//                    $daysToAdd = now()->addDays($loanSettings->term);

                    $loan->loan_status_id = $params['loan_status'];
//                    $loan->start_date = $now;
//                    $loan->end_date = $daysToAdd;
//                    $loan->disbursed = true;
                    $loan->save();

                    $creditAcct['amount in naira'] = $loan->amount;

                    $this->customerLoanStatus->create($loanStatusUpdate);

                    Log::info('Credited Loan Applicant', formatLogResponse($creditAcct));

                    dispatch(new ApproveLoanJob($customer->email, $customer->first_name));
                    DB::commit();
                    return redirect()->back()->with('success', 'Loan Approved Successful');
                } else {
                    Log::error('Fail to Credit Loan Applicant', formatLogResponse($creditAcct));
                    return redirect()->back()->with('error_message', json_encode($creditAcct['message']));
                }
            } elseif ($loanStatus == 'Declined') {
                $loan->loan_status_id = $params['loan_status'];
                $loan->save();
                $this->customerLoanStatus->create($loanStatusUpdate);
                $mailData['name'] = $customer->first_name;
                $mailData['remarks'] = $params['remarks'];
                dispatch(new DeclineLoanJob($customer->email, $mailData));
//                Mail::to()->send(new DeclineLoan($mailData));
                DB::commit();
                return redirect()->back()->with('success', 'Loan Declined Successful');
            }
        }catch (\Exception $exception){
            DB::rollBack();
            Log::error('Fail to Credit Loan Applicant', formatLogResponse($exception));
            return redirect()->back()->with('error_message', 'Something went wrong '.$exception->getMessage());
        }
    }

    private function loanColumns()
    {
        return [
            [
                'title' => 'Name',
                'name' => 'customer.first_name',
                'data' => 'customer'
            ],
            [
                'title' => 'Amount',
                'name' => 'amount',
                'data' => 'amount'
            ],
            [
                'title' => 'Rate',
                'name' => 'rate',
                'data' => 'rate'
            ],
            [
                'title' => 'Repay Amount',
                'name' => 'repay_amount',
                'data' => 'repay_amount'
            ],
            [
                'title' => 'Date',
                'name' => 'created_at',
                'data' => 'created_at'
            ],
            [
                'title' => 'Action',
                'name' => 'action',
                'data' => 'action'
            ]
        ];
    }

    private function allLoanData($data)
    {
        try {

            return DataTables::of($data)
                ->editColumn('customer', function ($field) {
                    return $field->customer->full_name;
                })
                ->editColumn('amount', function ($field) {
                    return '₦'.number_format($field->amount);
                })
                ->editColumn('repay_amount', function ($field) {
                    return number_format($field->repay_amount);
                })
                ->editColumn('created_at', function ($field) {
                    return formatDate($field->created_at)->format('d/m/Y');
                })
                ->filterColumn('created_at', function ($query, $keyword) {
                    $query->whereRaw("DATE_FORMAT(loans.created_at,'%d/%m/%Y') like ?", ["%$keyword%"]);
                })
//                ->editColumn('end_date', function ($field) {
//                    return formatDate($field->end_date)->format('d/m/Y');
//                })
//                ->filterColumn('end_date', function ($query, $keyword) {
//                    $query->whereRaw("DATE_FORMAT(loans.end_date,'%d/%m/%Y') like ?", ["%$keyword%"]);
//                })
                ->addColumn('action', function ($field) {
//                    return "<button id='blockAdmin' data-id='" . encrypt($field->id) . "' class='btn btn-success ml-2'>
//                                Block
//                            </button>";
                    return "<a href='".route('admin.customer.details',encrypt($field->customer->id))."'
                    class='btn btn-success ml-2'>View Details</a>";

                })
                ->rawColumns(['customer_id','start_date','end_date','action'])
                ->removeColumn(['update_at'])
                ->make(true);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
