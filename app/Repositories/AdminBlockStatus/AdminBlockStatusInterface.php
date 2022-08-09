<?php

namespace App\Repositories\AdminBlockStatus;

interface AdminBlockStatusInterface
{
    public function create(array $params);

    public function findById(int $id);

    public function findByColumn(array $params);

    public function getAll();
}
