<?php


namespace App\Repositories\CustomerBlockStatus;

use App\Models\Customers\CustomerBlockStatus;
use App\Repositories\CustomerBlockStatus\UsersBlockStatusInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class UsersBlockStatusRepository implements UsersBlockStatusInterface
{
    protected $block_status;

    public function __construct(CustomerBlockStatus $block_status)
    {
        $this->block_status = $block_status;
    }

    public function create(array $params){
        $model = $this->block_status;
        $this->setModelProperties($model, $params);
        $model->save();
        return $model;
    }

    public function findById(int $id)
    {
        return $this->block_status::find($id);
    }


    public function getAll()
    {
        return $this->block_status::get();
    }


    public function findByColumn(array $params)
    {
        return $this->block_status::where($params);
    }


    private function setModelProperties($model, $params){
        $model->user_id = $params['user_id'];
        $model->reason = $params['reason'];
        $model->status = $params['status'];
    }
}
