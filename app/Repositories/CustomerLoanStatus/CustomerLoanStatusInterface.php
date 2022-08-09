<?php

namespace App\Repositories\CustomerLoanStatus;

interface CustomerLoanStatusInterface
{
    public function create(array $params);

    public function findById(int $id);

    public function findByColumn(array $params);

    public function getAll();

}
