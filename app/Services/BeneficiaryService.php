<?php


namespace App\Services;

use App\Exceptions\ApplicationProcessFailedException;
use App\Services\ResponseService;
use App\Repositories\Beneficiary\BeneficiaryInterface;
use App\Http\Resources\BeneficiaryResource;
use App\Jobs\SaveBeneficiaryJob;

class BeneficiaryService
{
    public $response, $beneficiary;

    public function __construct(ResponseService $response, BeneficiaryInterface $beneficiary)
    {
        $this->response = $response;
        $this->beneficiary = $beneficiary;
    }

    /**
     * @throws ApplicationProcessFailedException
     */
    public function index()
    {
        $data = $this->beneficiary->findByColumn([[
            'customer_id', '=', auth()->user()->id
        ]])->get();

        if ($data->isEmpty()) {
            throw new ApplicationProcessFailedException('Beneficiary list empty', 500);
        }

        return $this->response->getSuccessResource([
            'data' => BeneficiaryResource::collection($data)
        ]);
    }

    /**
     * @param $params
     * @return \Illuminate\Http\JsonResponse
     * @throws ApplicationProcessFailedException
     */
    public function create($params)
    {
        $params['customer_id'] = auth()->user()->id;

        $this->beneficiaryExists($params);

        $this->countBeneficiaries($params);

        dispatch(new SaveBeneficiaryJob($params));

        return $this->response->getSuccessResource([
            'message' => "Beneficiary created"
        ]);
    }

    /**
     * @param $params
     * @throws ApplicationProcessFailedException
     */
    public function beneficiaryExists($params): void
    {
        $exists = $this->beneficiary->findByColumn([
            ['customer_id', '=', $params['customer_id']],
            ['account_number', '=', $params['account_number']]
        ])->count();

        if ($exists >= 1) {
            throw new ApplicationProcessFailedException('Beneficiary already exists', 500);
        }
    }

    /**
     * @param $params
     * @throws ApplicationProcessFailedException
     */
    public function countBeneficiaries($params): void
    {
        $count = $this->beneficiary->findByColumn([[
            'customer_id', '=', $params['customer_id'],
        ]])->count();

        if ($count >= config('npay.beneficiaries')) {
            throw new ApplicationProcessFailedException('User cannot create beneficiaries, limit exceeded', 500);
        }
    }

    /**
     * @param $params
     * @return \Illuminate\Http\JsonResponse
     * @throws ApplicationProcessFailedException
     */
    public function delete($params)
    {
        $customer = auth()->user()->id;
        $account = $params['account_number'];

        $exists = $this->beneficiary->findByColumn([
            ['customer_id', '=', $customer],
            ['account_number', '=', $account]
        ])->delete();

        if ($exists) {
            return $this->response->getSuccessResource([
                'message' => "Beneficiary deleted"
            ]);
        }

        throw new ApplicationProcessFailedException('Could not delete beneficiary', 400);
    }
}
