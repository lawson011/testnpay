<?php

namespace App\Repositories\CustomerAuth;

interface CustomerAuthInterface
{
    public function apiLogin($params);

    public function logout();

    public function findById(int $id);

    public function findByColumn(array $params);

    public function authCustomer();

    public function create(array $params);

    public function getAll();

    public function refreshToken(array $params);
}
