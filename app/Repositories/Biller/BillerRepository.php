<?php


namespace App\Repositories\Biller;

use App\Models\Biller;

class BillerRepository implements BillerInterface
{
    protected $model;

    public function __construct(Biller $model)
    {
        $this->model = $model;
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
}
