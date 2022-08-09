<?php


namespace App\Repositories\LoanStatus;

use App\Models\Loans\LoanStatus;

class LoanStatusRepository implements LoanStatusInterface
{
    protected $loanStatus;

    public function __construct(LoanStatus $loanStatus)
    {
        $this->loanStatus = $loanStatus;
    }

    public function create(array $params){
        $model = $this->loanStatus;
        $this->setModelProperties($model, $params);
        $model->save();
        return $model;
    }

    public function findById(int $id)
    {
        return $this->loanStatus::find($id);
    }

    public function getAll()
    {
        return $this->loanStatus::latest();
    }


    public function findByColumn(array $params)
    {
        return $this->loanStatus::where($params);
    }


    private function setModelProperties($model, $params){
        $model->user_id = $params['user_id']; //admin that initiated the request
        $model->loan_id = $params['loan_id'];
        $model->loan_status_id = $params['loan_status_id'];
    }
}
