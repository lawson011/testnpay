<?php

namespace App\Repositories\LoanServiceCharge;

interface LoanServiceChargeInterface
{
    public function create(array $params);

    public function findById(int $id);

    public function findByColumn(array $params);

    public function getAll();

    public function update(int $id, array $param);

}
