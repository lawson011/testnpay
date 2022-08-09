<?php


namespace App\Repositories\CustomerOnboardingCustomer;

use App\Models\CustomerOnboardingCustomer;

class CustomerOnboardingCustomerRepository implements CustomerOnboardingCustomerInterface
{
    protected $model;

    public function __construct(CustomerOnboardingCustomer $model)
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
        $model->first_name = $params['first_name'];
        $model->last_name = $params['last_name'];
        $model->email = $params['email'] ?? null;
        $model->phone = $params['phone'];
        $model->nuban = $params['nuban'];
        $model->amount = $params['amount'];
        $model->cba_id = $params['cba_id'];
    }
}
