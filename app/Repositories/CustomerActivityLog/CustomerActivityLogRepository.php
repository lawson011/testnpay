<?php


namespace App\Repositories\CustomerActivityLog;

use App\Models\Customers\CustomerActivityLog;

class CustomerActivityLogRepository implements CustomerActivityLogInterface
{
    protected $log;

    public function __construct(CustomerActivityLog $log)
    {
        $this->log = $log;
    }

    public function create(array $params){
        $model = $this->log;
        $this->setModelProperties($model, $params);
        $model->save();
        return $model;
    }

    public function findById(int $id)
    {
        return $this->log::find($id);
    }


    public function getAll()
    {
        return $this->log::orderBy('created_at','desc')->get();
    }

    public function getLogs()
    {
        return $this->log::latest();
    }


    public function findByColumn(array $params)
    {
        return $this->log::where($params);
    }


    private function setModelProperties($model, $params){
        $model->customer_id = $params['customer_id'];
        $model->platform = $params['platform'];
        $model->ip = $params['ip'];
        $model->action = $params['action'];
        $model->uri = $params['uri'];
        $model->body = $params['body'];
        $model->message_type = $params['message_type'];

    }
}
