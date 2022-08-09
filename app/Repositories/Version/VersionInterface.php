<?php

namespace App\Repositories\Version;

interface VersionInterface
{
    public function create(array $params);

    public function findById(int $id);

    public function findByColumn(array $params);

    public function getAll();

}
