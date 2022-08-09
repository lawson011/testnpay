<?php

namespace App\Repositories\CustomerBlockStatus;

interface UsersBlockStatusInterface
{
    public function create(array $params);

    public function findById(int $id);

    public function findByColumn(array $params);

    public function getAll();





}
