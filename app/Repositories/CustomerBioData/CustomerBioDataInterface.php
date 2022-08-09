<?php

namespace App\Repositories\CustomerBioData;

interface CustomerBioDataInterface
{
    public function create(array $params);

    public function findById(int $id);

    public function findByColumn(array $params);

    public function getAll();

}
