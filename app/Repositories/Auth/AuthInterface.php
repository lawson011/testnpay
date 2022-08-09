<?php

namespace App\Repositories\Auth;

interface AuthInterface
{
    public function adminLogin($params);

    public function allApplicants();

    public function allAdmins();

    public function edit($id, $params);

    public function logout();

    public function findById(int $id);

    public function findByColumn(array $params);

    public function authUser();

    public function create(array $params);

    public function getRoles(array $params);
}