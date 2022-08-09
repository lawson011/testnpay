<?php


namespace App\Repositories\LoanSetting;

use App\Models\Loans\LoanSetting;
use App\Repositories\LoanSetting\LoanSettingInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class LoanSettingRepository implements LoanSettingInterface
{
    protected $loanSetting;

    public function __construct(LoanSetting $loanSetting)
    {
        $this->loanSetting = $loanSetting;
    }

    public function create(array $params){
        $model = $this->loanSetting;
        $this->setModelProperties($model, $params);
        $model->save();
        return $model;
    }

    public function findById(int $id)
    {
        return $this->loanSetting::find($id);
    }


    public function getAll()
    {
        return $this->loanSetting->get();
    }


     public function getLatestSettings()
     {
         return $this->loanSetting::latest();
     }


    public function findByColumn(array $params)
    {
        return $this->loanSetting::where($params);
    }


    private function setModelProperties($model, $params){
        $model->rate = $params['rate'];
        $model->term = $params['term'];
        $model->amount = $params['amount'];
        $model->repayment_amount = $params['repayment_amount'];
        $model->user_id = Auth::id();
    }
}
