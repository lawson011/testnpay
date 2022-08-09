<?php


namespace App\Repositories\CustomerIdentityCard;

use App\Models\Customers\CustomerIdentityCard;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class CustomerIdentityCardRepository implements CustomerIdentityCardInterface
{
    protected $customerIdentityCard;

    public function __construct(CustomerIdentityCard $customerIdentityCard)
    {
        $this->customerIdentityCard = $customerIdentityCard;
    }

    public function create(array $params){

        $model = $this->customerIdentityCard;

        $photo = Storage::putFile('public\identity_card', new File($params['identity_card'])); //save image

        $params['url'] = asset(Storage::url(str_replace('public','',$photo))); // saved photo absolute path

        $this->setModelProperties($model, $params);
        $model->save();
        return $model;
    }

    public function findById(int $id)
    {
        return $this->customerIdentityCard::find($id);
    }

    public function getAll()
    {
        return $this->customerIdentityCard::get();
    }

    public function updateRow($conditions,$params){

        $this->customerIdentityCard
            ->where($conditions)
            ->update($params);
    }

    public function findByColumn(array $params)
    {
        return $this->customerIdentityCard::where($params);
    }


    private function setModelProperties($model, $params){
        $model->customer_id = Auth::id();
        $model->identity_card_type_id = $params['identity_card_type'];
        $model->url = $params['url'];
    }
}
