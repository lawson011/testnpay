<?php


namespace App\Repositories\CustomerLoanStatus;

use App\Models\Loans\CustomerLoanStatus;

class CustomerLoanStatusRepository implements CustomerLoanStatusInterface
{
    protected $applicantLoanStatus;

    public function __construct(CustomerLoanStatus $applicantLoanStatus)
    {
        $this->applicantLoanStatus = $applicantLoanStatus;
    }

    public function create(array $params){
        $model = $this->applicantLoanStatus;
        $this->setModelProperties($model, $params);
        $model->save();
        return $model;
    }

    public function findById(int $id)
    {
        return $this->applicantLoanStatus::find($id);
    }

    public function getAll()
    {
        return $this->applicantLoanStatus::latest();
    }


    public function findByColumn(array $params)
    {
        return $this->applicantLoanStatus::where($params);
    }


    private function setModelProperties($model, $params){
        $model->user_id = $params['user_id'];
        $model->loan_id = $params['loan_id'];
        $model->loan_status_id = $params['loan_status_id'];
        $model->remark = $params['remark'] ?? null;
    }
}
