<?php


namespace App\Repositories\CustomerCard;

use App\Models\Customers\CustomerCard;

class CustomerCardRepository implements CustomerCardInterface
{
    protected $customerCard;

    public function __construct(CustomerCard $customerCard)
    {
        $this->customerCard = $customerCard;
    }

    public function create(array $params){
        $model = $this->customerCard;
        $this->setModelProperties($model, $params);
        $model->save();
        return $model;
    }

    public function findById(int $id)
    {
        return $this->customerCard::find($id);
    }

    public function getAll()
    {
        return $this->customerCard::get();
    }


    public function findByColumn(array $params)
    {
        return $this->customerCard::where($params);
    }


    private function setModelProperties($model, $params){
        $model->customer_id = $params['customer_id'];
        $model->authorization_code = $params['authorization_code'];
        $model->exp_month = $params['exp_month'];
        $model->exp_year = $params['exp_year'];
        $model->signature = $params['signature'];
        $model->card_type = $params['card_type'];
        $model->bank = $params['bank'];
        $model->amount_charged = $params['amount_charged'];
        $model->reference = $params['reference'];
        $model->default = true;
    }
}
