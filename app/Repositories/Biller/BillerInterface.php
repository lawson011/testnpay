<?php

namespace App\Repositories\Biller;

interface BillerInterface
{

    public function findById(int $id);

    public function findByColumn(array $params);

    public function getAll();

}
