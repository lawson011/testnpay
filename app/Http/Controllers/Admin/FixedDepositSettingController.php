<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\FixedAccountSetting\FixedAccountSettingInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\LoanSetting\LoanSettingInterface;
use App\Http\Requests\Admin\LoanSettingRequest;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;
use Carbon\Carbon;

class FixedDepositSettingController extends Controller
{
    protected $fixedAccountSetting;

    public function __construct(FixedAccountSettingInterface $fixedAccountSetting){
        $this->fixedAccountSetting = $fixedAccountSetting;
    }

    public function update($id){
        $data = $this->fixedAccountSetting->findById(decrypt($id));

        return view('fixedAccount.update-settings', compact('data'));
    }

    //
    public function newSetting(){
        return view('fixedAccount.add-settings');
    }

    public function storeNewSetting(Request $request){
         $this->fixedAccountSetting->create($request->all());
        return redirect()->route('admin.fixed-deposit.settings');
    }

    public function storeUpdate(Request $request){
        $request->validate([
           'tenure' => 'required',
           'interest_rate' => 'required' ,
            'product_code' => 'required'
        ]);
        $model = $this->fixedAccountSetting->findByID(decrypt($request["id"]));
        $model->tenure = $request["tenure"];
        $model->interest_rate = $request["interest_rate"];
        $model->product_code = $request["product_code"];
        $model->active = true;
        $model->user_id = Auth::id();
        $model->save();
        return redirect()->route('admin.fixed-deposit.settings');
    }

    public function showSettings(Request $request,Builder $builder){
        $data = $this->fixedAccountSetting->getAll()->latest();

        if ($request->ajax()){
            return $this->allSettingsData($data);
        }

        $html = $builder->columns($this->settingsColumns());
        return view('fixedAccount.settings',compact('data','html'));
    }

    private function settingsColumns()
    {
        return [
            [
                'title' => 'Tenure',
                'name' => 'tenure',
                'data' => 'tenure'
            ],
            [
                'title' => 'Interest Rate',
                'name' => 'interest_rate',
                'data' => 'interest_rate'
            ],
            [
                'title' => 'Product Code',
                'name' => 'product_code',
                'data' => 'product_code'
            ],
            [
                'title' => 'Admin',
                'name' => 'user_id',
                'data' => 'user_id'
            ],
            [
                'title' => 'Active',
                'name' => 'active',
                'data' => 'active'
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

                ->editColumn('active', function ($field) {
                    return $field->active == true ? 'Yes' : 'No';
                })
               ->editColumn('user_id', function ($field) {
                    return $field->user->full_name ?? '';
                })->editColumn('tenure', function ($field) {
                    return $field->tenure.' Days';
                })->editColumn('created_at', function ($field) {
                    return $field->created_at->format('d-m-Y') ?? '';
                })->addColumn('action', function ($field) {
                    return "<a href='".route('admin.fixed-deposit.settings.update',encrypt($field->id))."'
                                        class='btn btn-success ml-2'>Edit</a>";

                                    })
                ->removeColumn([ 'update_at', 'deleted_at'])
                ->make(true);
        } catch (\Exception $e) {
            return $e->getMessage();
        }




    }
}
