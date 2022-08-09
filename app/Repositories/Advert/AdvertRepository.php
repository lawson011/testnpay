<?php

namespace App\Repositories\Advert;

use App\Models\Advert;

class AdvertRepository implements AdvertInterface
{
    protected $version;

    public function __construct(Advert $version)
    {
        $this->version = $version;
    }

    public function create(array $params){
        $model = $this->version;
        $this->setModelProperties($model, $params);
        $model->save();
        return $model;
    }

    public function findById(int $id)
    {
        return $this->version::find($id);
    }

    public function getAll()
    {
        return $this->version::latest()->get();
    }

    public function findByColumn(array $params)
    {
        return $this->version::where($params);
    }

    private function setModelProperties($model, $params){
        $model->name = $params['name'];
        $model->url = $params['url'];
        $model->active = 1;
    }
}
