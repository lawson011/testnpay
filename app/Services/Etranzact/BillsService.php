<?php


namespace App\Services\Etranzact;

use App\Exceptions\ApplicationProcessFailedException;
use App\Services\Etranzact\EtrazanctSoapsTrait\BillSoapRequestTrait;
use App\Http\Resources\BillsResource;
use App\Services\ResponseService;

class BillsService extends BaseService
{
    use BillSoapRequestTrait;


    /**
     * @return mixed
     * @throws ApplicationProcessFailedException
     */
    public function menu()
    {

        return dd($this->allBills());
        $data = $this->post($this->allBills());

        $bills = xml_to_array($data);

        if(isset($bills['biller']) && !empty($bills['biller'])){
            return app(ResponseService::class)->getSuccessResource([
                'data' => BillsResource::collection($bills['biller'])
            ]);
        }

        throw new ApplicationProcessFailedException('Could not retrieve billers list',400);
    }

    /**
     * @param $params
     * @throws ApplicationProcessFailedException
     */
    public function menuItems($params)
    {
        $menu = xml_to_array($this->post(
            $this->billItems($params)
        ));

        return dd($menu);
    }
}

