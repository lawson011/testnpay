<?php


namespace App\Repositories\BillTransaction;


interface BillTransactionInterface
{
    public function get(array $condition);
    public function all();
}