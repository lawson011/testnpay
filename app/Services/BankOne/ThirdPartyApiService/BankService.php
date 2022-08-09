<?php


namespace App\Services\BankOne\ThirdPartyApiService;

use App\Exceptions\ApplicationProcessFailedException;
use App\Services\ResponseService;
use App\Models\Bank;
use App\Http\Resources\BanksCollection;
use App\Http\Resources\BankOneBankResource;
use App\Services\BankOne\BaseService;
use Illuminate\Support\Facades\Http;

class BankService extends BaseService
{
    public $bank,$response,$bankUrl='/BillsPayment/GetCommercialBanks';

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function all()
    {
        $this->bank = app(Bank::class);

        return $this->response->getSuccessResource([
            'data' => $this->bank->all()
        ]);
    }

    /**
     * @param $cbn_code
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBank($cbn_code)
    {
        $this->bank = app(Bank::class);

        return $this->response->getSuccessResource([
            'data' => $this->bank->where('cbn_code',$cbn_code)->first()
        ]);
    }

    public function bankOneAllBanks($response_type=null){
        $this->url = env('BANK_ONE_THIRD_PARTY_API').$this->bankUrl.'/'.env('BANK_ONE_INSTITUTION_TOKEN');

        $data = Http::get($this->url);

        if($data->status() !== 200){
            $data->throw();
        }

        $data_decode = json_decode($data->body(),true);

        logger(' Bank List Data Dump '.$data->body());

        if(!is_array($data_decode) ){
            throw new ApplicationProcessFailedException('Failed to retrieve bank list',500);
        }

        if($response_type === 'data'){
            return $data_decode;
        }

        return $this->response->getSuccessResource([
            'data' =>  BankOneBankResource::collection(collect($data_decode))
        ]);
    }
}
