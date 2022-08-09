<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\BeneficiaryService;
use App\Http\Requests\CreateBeneficiaryRequest;
use App\Http\Requests\DeleteBeneficiaryRequest;

class BeneficiaryController extends Controller
{
    public $beneficiaries;

    /**
     * BeneficiaryController constructor.
     * @param BeneficiaryService $beneficiaries
     */
    public  function __construct(BeneficiaryService $beneficiaries)
    {
        $this->beneficiaries = $beneficiaries;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ApplicationProcessFailedException
     */
    public function index()
    {
        return $this->beneficiaries->index();
    }

    /**
     * @param CreateBeneficiaryRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ApplicationProcessFailedException
     */
    public function create(CreateBeneficiaryRequest $request)
    {
        $validated = $request->validated();

        return $this->beneficiaries->create($validated);
    }

    /**
     * @param DeleteBeneficiaryRequest $request
     * @throws \App\Exceptions\ApplicationProcessFailedException
     */
    public function delete(DeleteBeneficiaryRequest $request)
    {
        $validated = $request->validated();

        return $this->beneficiaries->delete($validated);
    }
}
