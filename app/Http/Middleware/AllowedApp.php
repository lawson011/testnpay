<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AllowedApp
{
    protected $headerParams = [
        [
          'platform-token' => 'zUHht8nMcmNmyOsXl9KPixTaNvBsfX2hcpGsu4RRGjvt',
          'platform' => 'Android'
        ],
        [
            'platform-token' => 'qA2xOACgV1hD1fwSF79iYBioBGrGT6whhP0ARdpkcNJN',
            'platform' => 'IOS'
        ],
        [
            'platform-token' => 'YUTHGACgV1hD1fwSF79iYBioBGrGT6whIUIUesedef11!',
            'platform' => 'WEB'
        ]
    ];
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Get header parameters
        $platform_token = $request->headers->get("Platform-Token");
        $platform = $request->headers->get("Platform");

        $platformKey = array_column($this->headerParams, 'platform');
        $found_key = array_search($platform, $platformKey); //get array index

        //Check if header parameters are valid
        if (!$platform_token || !$platform || !$this->headerParams[$found_key] ||
            $this->headerParams[$found_key]['platform-token'] != $platform_token ||
            $this->headerParams[$found_key]['platform'] != $platform
        )
         return response()->json([
             'errors' => [
                 'status' => 401,
                 'message' => 'Unauthorized ',
             ]
         ], 401);

        return $next($request);
    }
}
