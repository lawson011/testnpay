<?php


namespace App\Repositories\Transaction;


interface TransactionInterface
{
    public function create($params);
    public function find($nuban,$reference);
    public function all($nuban);
    public function range($nuban,$start,$end);
    public function dailyTransactions($nuban);
}
