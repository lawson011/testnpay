<?php


namespace App\Services\BankOne\CustomerAccount;


use App\Repositories\CustomerAuth\CustomerAuthInterface;
use App\Repositories\CustomerBioData\CustomerBioDataInterface;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Matrix\Exception;

class UploadServices
{
    protected $bioData, $customerAuth;

    public function __construct(CustomerBioDataInterface $bioData, CustomerAuthInterface $customerAuth)
    {
        $this->bioData = $bioData;
        $this->customerAuth = $customerAuth;
    }

    const uploadSupportingDocument = "/Account/UploadSupportingDocument/2";
    const maximum_no_of_times_to_try_upload = 3;

    public function uploadPassport()
    {

       $customerBioDatas = $this->bioData->findByColumn([
            ['upload_photo_to_cba', '=', false],
            ['no_of_upload_tries', '!=', self::maximum_no_of_times_to_try_upload]
        ])->latest()->get();

        echo "*** Running Upload photo to cba *** \n";

        logger("***Running Upload photo to cba*** \n");

        foreach ($customerBioDatas as $customerBioData) {

            $user = $customerBioData->customer->where('registration_method', 'New')
                ->where('is_staff', 0)
                ->find($customerBioData->customer_id);

            if ($user && $user->id == $customerBioData->customer_id) {
                echo " \n ***Running customer Upload photo to cba*** \n";
                logger($user->first_name.' ' .$user->last_name);
                echo $user->first_name.' ' .$user->last_name;
                $this->uploadToCBALogic($customerBioData);
            }

            //for staff change of passport
            $staff = $customerBioData->customer->where('is_staff', 1)
                ->find($customerBioData->customer_id);

            $uploadCount = $customerBioData->where([
                ['upload_photo_count', '>=', 1],
                ['upload_photo_count', '<=', 2]
            ])->first();

            if ($uploadCount && $uploadCount->customer_id == $customerBioData->customer_id
                && $staff && $staff->id == $customerBioData->customer_id) {
                echo " \n ***Running Staff Upload photo to cba*** \n";
                echo $staff->first_name.' ' .$staff->last_name;
                logger($staff->first_name.' ' .$staff->last_name);
                $this->uploadToCBALogic($customerBioData);
            }

        }
    }

    public function uploadToCBALogic($customer)
    {
        try {
            //Explode to get customer image name
            $imageName = explode("storage/", $customer->photo)[1];

            //Get image from were it been save
            $getImage = Storage::get('public/' . $imageName);

            $img = Image::make($getImage);

            echo "***Compressing Image*** \n";
            $imgName = explode('photos/', $imageName)[1];

            // save file with medium quality, to reduce image size.
            $img->save('public/' . $imgName, 10);

            echo "*** Image size-" . $img->filesize() . "-***";

            $editedImage = file_get_contents('public/' . $imgName);

            $convertToBase64 = base64_encode($editedImage);

            $url = env('BANK_ONE_BASE_URL') . self::uploadSupportingDocument;

            $client = new Client([
                'headers' => ['Content-Type' => 'application/json']
            ]);

            $params = [
                'AccountNumber' => $customer->customer->nuban,
                'CustomerImage' => $convertToBase64,
//                'CustomerSignature' => $convertToBase64
            ];

            echo "***Uploading*** \n";
            $response = $client->post($url . '?authtoken=' . env('BANK_ONE_INSTITUTION_TOKEN') . '&version=2',
                ['body' => json_encode($params)]
            );

            //delete from the directory where the low quality image was save
            unlink('public/' . $imgName);

            $decodeResponse = json_decode($response->getBody(), true);
            if ($decodeResponse['IsSuccessful']) {
                $customer->upload_photo_to_cba = true;
                $customer->save();
                echo "***Successful*** \n";
                Log::info('Passport Upload To CBA Successful', formatLogResponse($customer->customer));
            } else {
                echo "***Cannot Upload Pic*** \n";
                echo "***--Customer --*** " . $customer->customer->full_name ." \n";
                Log::critical('Passport fail to upload', formatLogResponse($customer->customer));
                Log::critical('Passport fail to upload ----', $decodeResponse);
            }
            $customer->increment('no_of_upload_tries');
            echo 'Total number of tries ', $customer->no_of_upload_tries;
            Log::info('Total number of tries', [$customer->no_of_upload_tries]);
        } catch (Exception $exception) {
            logger($customer);
            report($exception);
        }
    }

}
