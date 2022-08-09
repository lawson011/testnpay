<?php

namespace App\Repositories\VerifyEmail;

interface VerifyEmailInterface
{
 
    public function sendMessage($email);
    public function insertRow($params);
    public function deleteRow($params);
    public function updateRow($conditions,$params);
    // public function userByColumn($params,$value);
    public function selectRow($params);
    public function verifyToken($data);
    public function verifyEmail($data);
    public function deleteEmail($data);
    public function validateEmail($data);
}


?>