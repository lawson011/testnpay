<?php


namespace App\Repositories\Transaction;

use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class TransactionRepository implements TransactionInterface
{
    protected $model;

    /**
     * TransactionRepository constructor.
     * @param Transaction $model
     */
    public function __construct(Transaction $model)
    {
        $this->model = $model;
    }

    /**
     * @param $params
     * @return mixed
     */
    public function create($params)
    {
        return $this->model->create($params);
    }

    /**
     * @param $user
     * @param $reference
     * @return mixed
     */
    public function find($user,$reference)
    {
        return $this->model->where('transaction_reference','=',$reference)->where('customer_id','=',$user)->firstOrFail();
    }

    /**
     * @param $nuban
     * @return mixed
     */
    public function all($nuban)
    {
        return  $this->model->where('sender_account_number','=',$nuban)
                    ->orWhere('receiver_account_number','=',$nuban)->get();
    }

    /**
     * @param $nuban
     * @param $start_date
     * @param $end_date
     * @return mixed
     */
    public function range($nuban,$start_date,$end_date)
    {
        return $this->model->where('sender_account_number','=',$nuban)
                            ->orWhere('receiver_account_number','=',$nuban)
                            ->whereDate('created_at','>=',$start_date)
                            ->whereDate('created_at','<=',$end_date)
                            ->get();
    }

    /**
     * @param $nuban
     * @return mixed
     */
    public function dailyTransactions($nuban)
    {
        return $this->model->where('sender_account_number','=',$nuban)
                    ->whereDate('created_at','=',now()->format('Y-m-d'))
                    ->sum('amount');
    }
}
