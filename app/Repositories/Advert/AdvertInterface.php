<?php

namespace App\Repositories\Advert;

interface AdvertInterface
{
    public function create(array $params);

    public function findById(int $id);

    public function findByColumn(array $params);

    public function getAll();

}
