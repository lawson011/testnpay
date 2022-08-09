<?php

namespace App\Repositories\FixedAccount;

interface FixedAccountInterface
{
    public function create(array $params);

    public function findById(int $id);

    public function findByColumn(array $params);

    public function getAll();

}
