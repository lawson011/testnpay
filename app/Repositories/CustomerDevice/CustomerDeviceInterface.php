<?php

namespace App\Repositories\CustomerDevice;

interface CustomerDeviceInterface
{
    public function create(array $params);

    public function findById(int $id);

    public function findByColumn(array $params);

    public function updateRow($conditions,$params);

    public function getAll();

}
