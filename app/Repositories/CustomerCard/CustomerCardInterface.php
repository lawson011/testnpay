<?php

namespace App\Repositories\CustomerCard;

interface CustomerCardInterface
{
    public function create(array $params);

    public function findById(int $id);

    public function findByColumn(array $params);

    public function getAll();

}
