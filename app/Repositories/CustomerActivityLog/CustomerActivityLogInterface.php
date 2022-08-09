<?php

namespace App\Repositories\CustomerActivityLog;

interface CustomerActivityLogInterface
{
    public function create(array $params);

    public function findById(int $id);

    public function findByColumn(array $params);

    public function getAll();

    public function getLogs();

}
