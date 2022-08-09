<?php


namespace App\Repositories\FixedAccount;


use App\Models\FixedAccount\FixedAccount;
use Illuminate\Support\Facades\Auth;

class FixedAccountRepository implements FixedAccountInterface
{
    protected $model;

    public function __construct(FixedAccount $model)
    {
        $this->model = $model;
    }

    public function create(array $params){
        $model = $this->model;
        $this->setModelProperties($model, $params);
        $model->save();
        return $model;
    }

    public function findById(int $id)
    {
        return $this->model::find($id);
    }

    public function getAll()
    {
        return $this->model->latest();
    }

    public function findByColumn(array $params)
    {
        return $this->model::where($params);
    }

    private function setModelProperties($model, $params){
        $model->customer_id = Auth::id();
        $model->fixed_account_setting_id = $params['tenure'];
        $model->amount = $params['amount'];
        $model->tenure = $params['days']; //number of days
        $model->product_code = $params['product_code'];
        $model->interest_rate = $params['interest_rate'];
        $model->interest_monthly = $params['interest_monthly']; //if the customer want the interest monthly
    }
}
