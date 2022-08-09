<?php

namespace App\Repositories\Loan;

interface LoanInterface
{
    public function create(array $params);

    public function findById(int $id);

    public function findByColumn(array $params);

    public function getAll();

    public function loanModel();

    public function eagerLoadRelationship(array $params);

}
