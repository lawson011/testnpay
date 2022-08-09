<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\CustomerActivityLog\CustomerActivityLogInterface as ActivityLogInterface;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;

class LogController extends Controller
{
    protected $log;

    public function __construct(ActivityLogInterface $log){
        $this->log = $log;
    }

    public function activities(Request $request,Builder $builder){
        $data = $this->log->getLogs()->with(['customer'])->select('customer_activity_logs.*');

        if ($request->ajax()){
            return $this->allLogData($data);
        }

        $html = $builder->columns($this->logColumns());
        return view('log.activities',compact('data','html'));
    }

    private function logColumns()
    {
        return [
            [
                'title' => 'Name',
                'name' => 'customer.first_name',
                'data' => 'customer'
            ],
            [
                'title' => 'Platform',
                'name' => 'platform',
                'data' => 'platform'
            ],
            [
                'title' => 'IP Address',
                'name' => 'ip',
                'data' => 'ip'
            ],
            [
                'title' => 'Action',
                'name' => 'action',
                'data' => 'action'
            ],
            [
                'title' => 'URI',
                'name' => 'uri',
                'data' => 'uri'
            ],
            [
                'title' => 'Body',
                'name' => 'body',
                'data' => 'body'
            ],
            [
                'title' => 'Message Type',
                'name' => 'message_type',
                'data' => 'message_type'
            ],
            [
                'title' => 'Date',
                'name' => 'created_at',
                'data' => 'created_at'
            ]
        ];
    }

    private function allLogData($data)
    {
        try {

            return DataTables::of($data)

                ->editColumn('customer', function ($field) {
                    return $field->customer->full_name ?? null;
                })
                ->editColumn('platform', function ($field) {
                    return is_null($field->platform)? "null" : $field->platform;
                })
                ->editColumn('ip', function ($field) {
                    return $field->ip;
                })
                ->editColumn('action', function ($field) {
                    return $field->action;
                })
                ->editColumn('uri', function ($field) {
                    return $field->uri;
                })
                ->editColumn('body', function ($field) {
                    return $field->body;
                })
                ->editColumn('message_type', function ($field) {
                    return $field->message_type;
                })
                ->editColumn('created_at', function ($field) {
                    return $field->created_at;
                })
                ->removeColumn([ 'update_at', 'deleted_at'])
                ->make(true);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
