<?php


namespace App\Repositories\CustomerDevice;

use App\Models\Customers\CustomerDevice;

class CustomerDeviceRepository implements CustomerDeviceInterface
{
    protected $customerDevice;

    public function __construct(CustomerDevice $customerDevice)
    {
        $this->customerDevice = $customerDevice;
    }

    public function create(array $params){
        $model = $this->customerDevice;
        $this->setModelProperties($model, $params);
        $model->save();
        return $model;
    }

    public function findById(int $id)
    {
        return $this->customerDevice::find($id);
    }

    public function getAll()
    {
        return $this->customerDevice::get();
    }

    public function updateRow($conditions,$params){

        $this->customerDevice
            ->where($conditions)
            ->update($params);
    }

    public function findByColumn(array $params)
    {
        return $this->customerDevice::where($params);
    }


    private function setModelProperties($model, $params){
        $model->customer_id = $params['customer_id'];
        $model->device_name = $params['device_name'];
        $model->device_id = $params['device_id'];
        $model->active = true;
    }
}
