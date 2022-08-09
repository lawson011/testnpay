<?php


namespace App\Repositories\BillerCategory;

use App\Models\BillerCategory;

class BillerCategoryRepository implements BillerCategoryInterface
{
    protected $model;

    public function __construct(BillerCategory $model)
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
