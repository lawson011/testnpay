<?php

namespace App\Http\Middleware;

use App\Repositories\CustomerAuth\CustomerAuthInterface;
use App\Services\ResponseService;
use Closure;
use Illuminate\Http\Request;

class CheckUser
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    protected $customerAuth, $responseService;
    public function __construct(CustomerAuthInterface $customerAuth, ResponseService $responseService)
    {
        $this->customerAuth = $customerAuth;
        $this->responseService = $responseService;
    }

    public function handle($request, Closure $next)
    {
        $authCustomer = $this->customerAuth->authCustomer();

        // check if user is not blocked
        if ($authCustomer->blocked == true){

            $this->customerAuth->logout(); //log user out

            return $this->responseService->getErrorResource([
                'status_code' => 401,
                'message' => 'Please contact admin'
            ]);
        }

        if ($request->headers->get("Platform") == 'WEB'){
            return $next($request);
        }

        $device_id = $request->headers->get("device-id");

        $device = $authCustomer->device->where('device_id',$device_id)->where('active',true)->first();

        //check if device id is active
        if (empty($device)){

            //for apple login
            if ($authCustomer->username != usernameToSkipDeviceDetach($authCustomer->username)) {

                $this->customerAuth->logout(); //log user out

                return $this->responseService->getErrorResource([
                    'status_code' => 401,
                    'message' => 'Unauthorized - No device info'
                ]);
            }
        }

        return $next($request);
    }
}
