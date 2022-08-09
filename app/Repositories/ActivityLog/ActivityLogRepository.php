<?php


namespace App\Repositories\ActivityLog;

use App\Models\ActivityLog;
use App\Repositories\ActivityLog\ActivityLogInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class ActivityLogRepository implements ActivityLogInterface
{
    protected $log;

    public function __construct(ActivityLog $log)
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
        $model->user_id = $params['user_id'];
        $model->platform = $params['platform'];
        $model->ip = $params['ip'];
        $model->action = $params['action'];
        $model->uri = $params['uri'];
        $model->body = $params['body'];
        $model->message_type = $params['message_type'];

    }
}
