<?php


namespace App\Repositories\AdminBlockStatus;

use App\Models\AdminBlockStatus;
use Illuminate\Support\Facades\Auth;

class AdminBlockStatusRepository implements AdminBlockStatusInterface
{
    protected $block_status;

    public function __construct(AdminBlockStatus $block_status)
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
        $model->admin_id = Auth::id();
    }
}
