<?php

namespace App\Repositories\BillerCategory;

interface BillerCategoryInterface
{

    public function findById(int $id);

    public function findByColumn(array $params);

    public function getAll();

}
