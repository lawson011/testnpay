<?php


namespace App\Repositories\LoanServiceCharge;

use App\Models\Loans\LoanServiceCharge;
use Illuminate\Support\Facades\Auth;


class LoanServiceChargeRepository implements LoanServiceChargeInterface
{
    protected $loanServiceCharge;

    public function __construct(LoanServiceCharge $loanServiceCharge)
    {
        $this->loanServiceCharge = $loanServiceCharge;
    }

    public function create(array $params){
        $model = $this->loanServiceCharge;
        $this->setModelProperties($model, $params);
        $model->save();
        return $model;
    }

    public function findById(int $id)
    {
        return $this->loanServiceCharge::find($id);
    }

    public function update(int $id, array $param){
        $model = $this->findById($id);
        $this->setModelProperties($model,$param);
        $model->save();
    }


    public function getAll()
    {
        return $this->loanServiceCharge->latest();
    }

    public function findByColumn(array $params)
    {
        return $this->loanServiceCharge::where($params);
    }


    private function setModelProperties($model, $params){
        $model->percentage = $params['percentage'];
        $model->user_id = Auth::id();
    }
}
