<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\LoanSetting\LoanSettingInterface;
use App\Http\Requests\Admin\LoanSettingRequest;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;
use Carbon\Carbon;

class LoanSettingController extends Controller
{
    protected $loanSetting;

    public function __construct(LoanSettingInterface $loanSetting){
        $this->loanSetting = $loanSetting;
    }

    public function updateView($id){
        $data = $this->loanSetting->findById(decrypt($id));

        return view('loan.update-settings', compact('data'));
    }

    public function add(LoanSettingRequest $request){
        $params = array("amount"=>$request["amount"],"rate"=>$request["rate"],"term"=>$request["term"]);
        $params['repayment_amount'] = $request["amount"] + ($request["amount"] * $request["rate"]/100);

        $model = $this->loanSetting->create($params);
        return redirect()->route('admin.loan.settings');
    }

    public function update(LoanSettingRequest $request){
        // $request['repayment_amount'] = $request["amount"] + ($request["amount"] * $request["rate"]/100);

        $model = $this->loanSetting->findByID(decrypt($request["id"]));
        $model->rate = $request["rate"];
        $model->term = $request["term"];
        $model->amount = $request["amount"];
        $model->cba_loan_product_code = $request["cba_loan_product_code"];
        $model->service_charge = $request["service_charge"];
        $model->user_id = Auth::id();
        $model->repayment_amount = $request["amount"] + ($request["amount"] * $request["rate"]/100);
        $model->updated_at = Carbon::now();
        $model->save();
        return redirect()->route('admin.loan.settings');
    }

    public function addView(){
        return view('loan.add-settings');
    }

    public function settings(Request $request,Builder $builder){
        $data = $this->loanSetting->getLatestSettings();

        if ($request->ajax()){
            return $this->allSettingsData($data);
        }

        $html = $builder->columns($this->settingsColumns());
        return view('loan.settings',compact('data','html'));
    }

    private function settingsColumns()
    {
        return [
            [
                'title' => 'Rate',
                'name' => 'rate',
                'data' => 'rate'
            ],
            [
                'title' => 'Term',
                'name' => 'term',
                'data' => 'term'
            ],
            [
                'title' => 'Amount',
                'name' => 'amount',
                'data' => 'amount'
            ],
            [
                'title' => 'Repayment Amount',
                'name' => 'repayment_amount',
                'data' => 'repayment_amount'
            ],
            [
                'title' => 'Service Charge',
                'name' => 'service_charge',
                'data' => 'service_charge'
            ],
            [
                'title' => 'Admin',
                'name' => 'user_id',
                'data' => 'user_id'
            ],            [
                'title' => 'CBA Loan Product Code',
                'name' => 'cba_loan_product_code',
                'data' => 'cba_loan_product_code'
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

    private function allSettingsData($data)
    {
        try {

            return DataTables::of($data)

                ->editColumn('rate', function ($field) {
                    return $field->rate ?? '';
                })
                ->editColumn('term', function ($field) {
                    return $field->term ?? '';
                })->editColumn('user_id', function ($field) {
                    return $field->user->full_name ?? '';
                })->editColumn('amount', function ($field) {
                    return $field->amount ?? '';
                })->editColumn('repayment_amount', function ($field) {
                    return $field->repayment_amount ?? '';
                })->editColumn('created_at', function ($field) {
                    return $field->created_at->format('d-m-Y') ?? '';
                })->addColumn('action', function ($field) {
                    return "<a href='".route('admin.loan.settings.updateview',encrypt($field->id))."'
                                        class='btn btn-success ml-2'>Edit Loan Setting</a>";

                                    })
                ->removeColumn([ 'update_at', 'deleted_at'])
                ->make(true);
        } catch (\Exception $e) {
            return $e->getMessage();
        }




    }
}
