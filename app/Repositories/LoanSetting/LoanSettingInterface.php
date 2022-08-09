<?php

namespace App\Repositories\LoanSetting;

interface LoanSettingInterface
{
    public function create(array $params);

    public function findById(int $id);

    public function findByColumn(array $params);

    public function getAll();

    public function getLatestSettings();


}
