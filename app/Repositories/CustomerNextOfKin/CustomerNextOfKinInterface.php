<?php

namespace App\Repositories\CustomerNextOfKin;

interface CustomerNextOfKinInterface
{
    public function create(array $params);

    public function findById(int $id);

    public function findByColumn(array $params);

    public function getAll();

}
