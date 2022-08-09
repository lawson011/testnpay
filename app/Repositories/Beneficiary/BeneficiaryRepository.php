<?php


namespace App\Repositories\Beneficiary;


use App\Models\Beneficiary;
use Illuminate\Support\Facades\Auth;

class BeneficiaryRepository implements BeneficiaryInterface
{
    protected $model;

    public function __construct(Beneficiary $model)
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
        $model->customer_id = $params['customer_id'];
        $model->customer_name = $params['customer_name'];
        $model->bank_code = $params['bank_code'];
        $model->bank_name = $params['bank_name'];
        $model->account_number = $params['account_number'];
    }
}
