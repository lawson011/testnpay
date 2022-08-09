<?php

namespace App\Repositories\CustomerRegistrationSetting;

interface CustomerRegistrationSettingInterface
{
    public function create(array $params);

    public function findById(int $id);

    public function findByColumn(array $params);

    public function getAll();

}
