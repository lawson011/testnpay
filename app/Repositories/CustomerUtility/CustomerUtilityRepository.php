<?php


namespace App\Repositories\CustomerUtility;

use App\Models\Customers\CustomerUtility;

use Illuminate\Http\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;


class CustomerUtilityRepository implements CustomerUtilityInterface
{
    protected $customerUtility;

    public function __construct(CustomerUtility $customerUtility)
    {
        $this->customerUtility = $customerUtility;
    }

    public function create(array $params){

        $model = $this->customerUtility;

        $photo = Storage::putFile('public\utility', new File($params['utility'])); //save image

        $params['url'] = asset(Storage::url(str_replace('public','',$photo))); // saved photo absolute path

        $this->setModelProperties($model, $params);
        $model->save();
        return $model;
    }

    public function findById(int $id)
    {
        return $this->customerUtility::find($id);
    }

    public function getAll()
    {
        return $this->customerUtility::latest();
    }

    public function updateRow($conditions,$params){

        $this->customerUtility
            ->where($conditions)
            ->update($params);
    }

    public function findByColumn(array $params)
    {
        return $this->customerUtility::where($params);
    }


    private function setModelProperties($model, $params){
        $model->customer_id = Auth::id();
        $model->utility_type_id = $params['utility_type'];
        $model->url = $params['url'];
    }
}
