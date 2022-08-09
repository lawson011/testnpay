<?php


namespace App\Repositories\Loan;

use App\Models\Loans\Loan;
use App\Repositories\Loan\LoanInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class LoanRepository implements LoanInterface
{
    protected $loan;

    public function __construct(Loan $loan)
    {
        $this->loan = $loan;
    }

    public function create(array $params){
        $model = $this->loan;
        $this->setModelProperties($model, $params);
        $model->save();
        return $model;
    }

    public function findById(int $id)
    {
        return $this->loan::find($id);
    }

    public function eagerLoadRelationship(array $params){
        return $this->loan::with($params);
    }

    public function getAll()
    {
        return $this->loan::latest();
    }

    public function loanModel()
    {
        return $this->loan;
    }


    public function findByColumn(array $params)
    {
        return $this->loan::where($params);
    }

    private function setModelProperties($model, $params){
        $model->customer_id = $params['customer_id'];
        $model->loan_setting_id = $params['loan_setting_id'];
        $model->loan_status_id = $params['loan_status_id'];
        $model->amount = $params['amount'];
        $model->rate = $params['rate']; //percentage
        $model->term = $params['term']; //number of days
        $model->repay_amount = $params['repay_amount'];
        $model->service_charge = $params['service_charge'];
    }
}
