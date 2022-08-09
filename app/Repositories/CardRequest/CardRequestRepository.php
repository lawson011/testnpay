<?php


namespace App\Repositories\CardRequest;


use App\Models\Customers\CardRequest;
use Illuminate\Support\Facades\Auth;

class CardRequestRepository implements CardRequestInterface
{
    protected $model;

    public function __construct(CardRequest $model)
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
        $model->customer_id = Auth::id();
        $model->card_request_status_id = cardRequestStatusByName('Processing')->id;
        $model->pickup_type = $params['pickup_type'];
        $model->customer_remarks = $params['customer_remarks'] ?? null; //number of days
    }
}
