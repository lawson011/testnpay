<?php


namespace App\Services\BankOne\ThirdPartyApiService\Account;

use App\Services\BankOne\BaseService;
use Illuminate\Support\Facades\Http;
use App\Exceptions\ApplicationProcessFailedException;

class NipAccountEnquiryService extends BaseService
{
    //TODO get this values from bankone developers
    private const enquiry = '/NIPNameInquiry';

    public function urlParameter($params)
    {
        $url = config('bankone.thirdparty-api.base-url')
            . self::enquiry . "/{$params['account_number']}/"
            . config('bankone.nuture-mfb.institution-code')
            . '/' . config('bankone.nuture-mfb.channel-code')
            . "/{$params['bank_code']}/"
            . config('bankone.nuture-mfb.institution-token');
        logger(' Bank One Nip Name Enquiry ' . $url);
        return $url;
    }

    public function getAccountInformation($params)
    {
        $response = Http::withHeaders(['Content-Type' => 'application/json'])->get($this->urlParameter($params));

        $data = json_decode($response->body(), true);

        logger($response . ' Response from bankone for account enquiry');

        if ($response->status() !== 200) {
            throw new ApplicationProcessFailedException('Failed to retrieve user account details', 400);
        }

        if (!is_array($data)) {
            throw new ApplicationProcessFailedException('Failed to retrieve user account details', 400);
        }

        if (auth()->user()->nuban === $params['account_number']) {
            throw new ApplicationProcessFailedException('Account belongs to this user', 400);
        }

        return $this->response->getSuccessResource([
            'data' => [
                'name' => $data['ResponseMessage'],
                'reference' => $data['Reference']
            ]]);
    }

}
