<?php

namespace App\Http\Middleware;

use App\Repositories\CustomerAuth\CustomerAuthInterface;
use App\Services\ResponseService;
use Closure;

class StaffMiddleware
{

    protected $customerAuth, $responseService;
    public function __construct(CustomerAuthInterface $customerAuth, ResponseService $responseService)
    {
        $this->customerAuth = $customerAuth;
        $this->responseService = $responseService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $authCustomer = $this->customerAuth->authCustomer();

        // check if user is not blocked
        if ($authCustomer->is_staff == false){

            $this->customerAuth->logout(); //log user out

            return $this->responseService->getErrorResource([
                'status_code' => 401,
                'message' => 'Please contact admin'
            ]);
        }

        return $next($request);
    }
}
