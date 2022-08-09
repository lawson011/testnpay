<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StaffChangePassport;
use App\Jobs\StaffChangePassportJob;
use App\Repositories\CustomerAuth\CustomerAuthInterface;
use App\Repositories\CustomerBioData\CustomerBioDataInterface;
use App\Services\ResponseService;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StaffController extends Controller
{
    const noOfTimeAllowedToUploadPassport = 7;

    protected $customerAuth, $responseService;

    public function __construct(CustomerAuthInterface $customerAuth, ResponseService $responseService)
    {
        $this->customerAuth = $customerAuth;
        $this->responseService = $responseService;
    }

    /**
     * @param StaffChangePassport $request
     * @return bool|\Illuminate\Http\JsonResponse
     */

    public function changePassport(StaffChangePassport $request){

        $authCustomer = $this->customerAuth->authCustomer();

        if ($authCustomer->is_staff == false) return false;

        $bioData =  app(CustomerBioDataInterface::class);

        $staff = $bioData->findByColumn([['customer_id','=',$authCustomer->id]])->first();

        if ($staff->upload_photo_count == self::noOfTimeAllowedToUploadPassport)
            return $this->responseService->getErrorResource([
                'message'=>'You have reached your maximum upload, please contact system admin'
            ]);

        $photo = Storage::putFile('public\photos', new File($request->photo)); //save image

        //delete old image
        Storage::delete('public/photos/'.explode('photos/', $staff->photo)[1]);

        //save the image to db
        $staff->photo = asset(Storage::url(str_replace('public','',$photo))); // saved photo absolute path
        $staff->upload_photo_count = 1 + $staff->upload_photo_count;
        $staff->upload_photo_to_cba = false;
        $staff->save();

        return $this->responseService->getSuccessResource();
    }
}
