<?php

namespace App\Repositories\Beneficiary;

interface BeneficiaryInterface
{
    public function create(array $params);

    public function findById(int $id);

    public function findByColumn(array $params);

    public function getAll();

}
