<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\User;
use App\Repositories\Auth\AuthInterface;
use App\Repositories\CustomerAuth\CustomerAuthInterface;
use App\Repositories\Loan\LoanInterface;
use App\Services\Paystack\PaystackService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;

class LoanController extends Controller
{
    protected $loan, $auth, $customerAuth;
    public function __construct(LoanInterface $loan, CustomerAuthInterface $customerAuth, PaystackService $paystackService)
    {
        $this->customerAuth = $customerAuth;
        $this->loan = $loan;
        $this->paystackService = $paystackService;
    }

    public function all(Request $request, Builder $builder)
    {
        $data = $this->loan->loanModel()->with(['user','repaymentMethod'])->select('loans.*')->latest();
        if ($request->ajax()){
            return $this->allLoanData($data);
        }
        $html = $builder->columns($this->loanColumns());

        return view('loan.all', compact('data','html'));
    }

    public function disbursed(Request $request, Builder $builder){

        $data = $this->loan->findByColumn(['disbursed'=>true])->with(['user'])->select('loans.*')->latest();

        if ($request->ajax()){
            return $this->allLoanData($data);
        }

        $html = $builder->columns($this->loanColumns());

        return view('loan.disbursed', compact('data','html'));

    }

    public function repaid(Request $request, Builder $builder){

        $data = $this->loan->findByColumn(['repay'=>true])->with(['user'])->select('loans.*')->latest();

        if ($request->ajax()){
            return $this->allLoanData($data);
        }

        $html = $builder->columns($this->loanColumns());

        return view('loan.repaid', compact('data','html'));

    }

    public function due(Request $request, Builder $builder)
    {
        $data = $this->loan->loanModel()->where('end_date','<=',Carbon::now())
            ->where(['repay'=>false,'disbursed'=>true])->with(['user'])->select('loans.*')->latest();

        if ($request->ajax()){
            return $this->allLoanData($data);
        }

        $html = $builder->columns($this->loanColumns());

        return view('loan.due', compact('data','html'));
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
                'title' => 'Start Date',
                'name' => 'start_date',
                'data' => 'start_date'
            ],
            [
                'title' => 'End Date',
                'name' => 'end_date',
                'data' => 'end_date'
            ],
            [
                'title' => 'Disbursed',
                'name' => 'disbursed',
                'data' => 'disbursed'
            ],
            [
                'title' => 'Repay',
                'name' => 'repay',
                'data' => 'repay'
            ],
            [
                'title' => 'Repayment Date',
                'name' => 'repayment_date',
                'data' => 'repayment_date'
            ],
            [
                'title' => 'Repayment Method',
                'name' => 'repaymentMethod.name',
                'data' => 'repaymentMethod',
                'searchable' => false
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
                ->editColumn('start_date', function ($field) {
                    return formatDate($field->start_date ?? $field->created_at)->format('d/m/Y');
                })
                ->filterColumn('start_date', function ($query, $keyword) {
                    $query->whereRaw("DATE_FORMAT(loans.created_at,'%d/%m/%Y') like ?", ["%$keyword%"]);
                })
                ->editColumn('end_date', function ($field) {
                    return formatDate($field->end_date)->format('d/m/Y');
                })
                ->filterColumn('end_date', function ($query, $keyword) {
                    $query->whereRaw("DATE_FORMAT(loans.end_date,'%d/%m/%Y') like ?", ["%$keyword%"]);
                })
                ->editColumn('disbursed', function ($field) {
                    return $field->disbursed == 1 ? 'Yes' : 'No';
                })
                ->editColumn('repay', function ($field) {
                    return $field->repay == 1 ? 'Yes' : 'No';
                })
                ->editColumn('repayment_date', function ($field) {
                    return $field->repayment_date ? formatDate($field->repayment_date)->format('d/m/Y') : '';
                })
                ->filterColumn('repayment_date', function ($query, $keyword) {
                    $query->whereRaw("DATE_FORMAT(loans.repayment_date,'%d/%m/%Y') like ?", ["%$keyword%"]);
                })
                ->editColumn('repaymentMethod', function ($field) {
                    return $field->repaymentMethod->method ?? '';
                })
                ->rawColumns(['customer_id','start_date','end_date','repayment_date'])
                ->removeColumn(['update_at'])
                ->make(true);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    private function userColumns()
    {
        return [
            [
                'title' => 'Name',
                'name' => 'first_name',
                'data' => 'first_name'
            ],
            [
                'title' => 'Image',
                'name' => 'bioData.photo',
                'data' => 'photo'
            ],
            [
                'title' => 'Email',
                'name' => 'email',
                'data' => 'email'
            ],
            [
                'title' => 'Phone',
                'name' => 'phone',
                'data' => 'phone'
            ],
            [
                'title' => 'Action',
                'name' => 'action',
                'data' => 'action'
            ]
        ];
    }

    private function allUserData($data)
    {
        try {
            return DataTables::of($data)
                ->addColumn('action', function ($field) {
                    return "<a href='".route('admin.loan.applicant.details',encrypt($field->id))."' class='btn btn-success ml-2'>View Details</a>";
                })
                ->editColumn('photo', function ($field) {

                    return "<img width='200' height='200' class='rounded-circle' src='".$field->bioData->first()['photo']."'/>";
                })
                ->editColumn('first_name', function ($field) {
                    return $field->full_name;
                })
                ->rawColumns(['action', 'photo'])
                ->removeColumn(['created_at', 'update_at', 'deleted_at'])
                ->make(true);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
