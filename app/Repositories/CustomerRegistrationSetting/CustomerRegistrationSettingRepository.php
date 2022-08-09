<?php


namespace App\Repositories\CustomerRegistrationSetting;


use App\Models\Customers\CustomerRegistrationSetting;
use Illuminate\Support\Facades\Auth;

class CustomerRegistrationSettingRepository implements CustomerRegistrationSettingInterface
{
    protected $model;

    public function __construct(CustomerRegistrationSetting $model)
    {
        $this->model = $model;
    }

    public function create(array $params){
        $model = $this->model;
        $this->setModelProperties($model, $params);

        //update all active column to false
        $this->findByColumn([
           ['active','=',true]
        ])->update(['active' => false]);

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
        $model->product_code = $params['product_code'];
        $model->account_officer_code =$params['account_officer_code'];
        $model->active = true;
        $model->user_id = Auth::id();
    }
}
