<?php


namespace App\Repositories\CustomerBioData;

use App\Models\Customers\CustomerBioData;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class CustomerBioDataRepository implements CustomerBioDataInterface
{
    protected $customerBioData;

    public function __construct(CustomerBioData $customerBioData)
    {
        $this->customerBioData = $customerBioData;
    }

    public function create(array $params){

        $model = $this->customerBioData;
        $photo = Storage::putFile('public\photos', new File($params['photo'])); //save image

        $params['photo'] = asset(Storage::url(str_replace('public','',$photo))); // saved photo absolute path
        $this->setModelProperties($model, $params);
        $model->save();
        return $model;
    }


    public function findById(int $id)
    {
        return $this->customerBioData::find($id);
    }

    public function getAll()
    {
        return $this->customerBioData::latest();
    }

    public function findByColumn(array $params)
    {
        return $this->customerBioData::where($params);
    }

    private function setModelProperties($model, $params){
        $model->customer_id = $params['customer_id'];
        $model->bvn = $params['bvn'] ?? null;
        $model->bvn_phone = $params['bvn_phone'] ?? null;
        $model->bvn_dob = $params['bvn_dob'] ?? null;
        $model->dob = $params['dob'];
        $model->occupation = $params['occupation'] ?? null;
        $model->salary_range = $params['salary_range'] ?? null;
        $model->address = $params['address'];
        $model->city = $params['city'] ?? null;
        $model->state_id = $params['state'] ?? null;
        $model->photo = $params['photo'];
    }
}
