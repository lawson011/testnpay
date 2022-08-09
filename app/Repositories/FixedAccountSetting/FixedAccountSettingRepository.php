<?php


namespace App\Repositories\FixedAccountSetting;


use App\Models\FixedAccount\FixedAccountSetting;
use Illuminate\Support\Facades\Auth;

class FixedAccountSettingRepository implements FixedAccountSettingInterface
{
    protected $model;

    public function __construct(FixedAccountSetting $model)
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
        $model->tenure = $params['tenure'];
        $model->interest_rate = $params['interest_rate'];
//        $model->daily_interest = $params['daily_interest'];
        $model->product_code = $params['product_code'];
        $model->user_id = Auth::id();
        $model->active = true;
    }
}
