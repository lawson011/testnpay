<?php

namespace App\Http\Controllers\V1\Auth;

use App\Services\DeviceService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\DetachDeviceRequest;
use App\Http\Requests\DetachDeviceMailRequest;


class CustomerDeviceController extends Controller
{

    protected $deviceService;

    public function __construct(DeviceService $deviceService)
    {
        $this->deviceService = $deviceService;
    }

    public function requestDetach(DetachDeviceMailRequest $request)
    {
        return $this->deviceService->requestDetach($request);
    }

    public function detachDevice(DetachDeviceRequest $request)
    {
        return $this->deviceService->detach($request);
    }
}
