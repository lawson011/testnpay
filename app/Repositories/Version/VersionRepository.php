<?php


namespace App\Repositories\Version;

use App\Models\Version;

class VersionRepository implements VersionInterface
{
    protected $version;

    public function __construct(Version $version)
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
        return $this->version::get();
    }

    public function findByColumn(array $params)
    {
        return $this->version::where($params);
    }

    private function setModelProperties($model, $params){
        $model->platform = $params['platform'];
        $model->value = $params['value'];
        $model->active = 1;
    }
}
