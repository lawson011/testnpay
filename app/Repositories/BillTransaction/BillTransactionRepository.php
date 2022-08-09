<?php


namespace App\Repositories\BillTransaction;

use App\Models\BillTransaction;


class BillTransactionRepository implements BillTransactionInterface
{
    protected $model;

    public function __construct(BillTransaction $model)
    {
        $this->model = $model;
    }

    public function get(array $condition)
    {
        return $this->model->where([$condition])->get();
    }

    public function all()
    {
        return $this->model->latest();
    }
}