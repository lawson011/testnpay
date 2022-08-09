<?php

namespace App\Repositories\LoanStatus;

interface LoanStatusInterface
{
    public function create(array $params);

    public function findById(int $id);

    public function findByColumn(array $params);

    public function getAll();

}
