<?php

namespace App\Repositories\FixedAccountSetting;

interface FixedAccountSettingInterface
{
    public function create(array $params);

    public function findById(int $id);

    public function findByColumn(array $params);

    public function getAll();

}
