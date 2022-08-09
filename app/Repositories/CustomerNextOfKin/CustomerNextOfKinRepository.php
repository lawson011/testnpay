<?php


namespace App\Repositories\CustomerNextOfKin;

use App\Models\Customers\CustomerNextOfKin;

class CustomerNextOfKinRepository implements CustomerNextOfKinInterface
{
    protected $customerNextOfKin;

    public function __construct(CustomerNextOfKin $customerNextOfKin)
    {
        $this->customerNextOfKin = $customerNextOfKin;
    }

    public function create(array $params){
        $model = $this->customerNextOfKin;
        $this->setModelProperties($model, $params);
        $model->save();
        return $model;
    }

    public function findById(int $id)
    {
        return $this->customerNextOfKin::find($id);
    }

    public function getAll()
    {
        return $this->customerNextOfKin::latest();
    }

    public function findByColumn(array $params)
    {
        return $this->customerNextOfKin::where($params);
    }


    private function setModelProperties($model, $params){
        $model->customer_id = $params['customer_id'];
        $model->name = $params['next_of_kin_name'];
        $model->address = $params['next_of_kin_address'];
        $model->phone = $params['next_of_kin_phone'];
        $model->city = $params['next_of_kin_city'];
        $model->state_id = $params['next_of_kin_state'];
    }
}
