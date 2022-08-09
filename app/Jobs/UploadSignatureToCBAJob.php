<?php

namespace App\Jobs;

use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Matrix\Exception;

class UploadSignatureToCBAJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    const uploadSupportingDocument = "/Account/UploadSupportingDocument/2";

    protected $customer;

    /**
     * Create a new job instance.
     *
     * @param $customer
     */
    public function __construct($customer)
    {
        $this->customer = $customer;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            //Explode to get customer image name
            $imageName = explode("storage/", $this->customer->signature)[1];

            //Get image from were it been save
            $getImage = Storage::get('public/' . $imageName);

            $img = Image::make($getImage);

            $imgName = explode('signature/', $imageName)[1];

            // save file with medium quality, to reduce image size.
            $img->save($imgName, 5);

            $editedImage = file_get_contents($imgName);

            $convertToBase64 = base64_encode($editedImage);

            $url = env('BANK_ONE_BASE_URL') . self::uploadSupportingDocument;

            $client = new Client([
                'headers' => ['Content-Type' => 'application/json']
            ]);

            $params = [
                'AccountNumber' => $this->customer->customer->nuban,
                'CustomerSignature' => $convertToBase64
            ];

            $response = $client->post($url . '?authtoken=' . env('BANK_ONE_INSTITUTION_TOKEN') . '&version=2',
                ['body' => json_encode($params)]
            );

            //delete from the directory where the low quality image was save
            unlink($imgName);

            $decodeResponse = json_decode($response->getBody(), true);
            if ($decodeResponse['IsSuccessful']) {
                Log::info('Signature Upload To CBA Successful', formatLogResponse($this->customer->customer));
            } else {
                Log::critical('Signature fail to upload', formatLogResponse($this->customer->customer));
                Log::critical('Signature fail to upload ----', $decodeResponse);
            }
        } catch (Exception $exception) {
            report($exception);
        }
    }
}
