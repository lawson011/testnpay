<?php

namespace App\Repositories\ActivityLog;

interface ActivityLogInterface
{
    public function create(array $params);

    public function findById(int $id);

    public function findByColumn(array $params);

    public function getAll();

    public function getLogs();

}