<?php

namespace App\Repositories\CustomerOnboardingCustomer;

interface CustomerOnboardingCustomerInterface
{
    public function create(array $params);

    public function findById(int $id);

    public function findByColumn(array $params);

    public function getAll();

}
