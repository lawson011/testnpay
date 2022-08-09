<?php

namespace App\Http\Middleware;

use Closure;

class ThirdPartyMiddleware
{

    protected $headerParams = [
        [
            'platform-token' => 'aK1TI/kXBO3gMlguWgx0ofxKvvIUCpFTXxrGYsH+z68=',
            'platform' => 'ReferralApp'
        ],
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
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

        logger("{$platform}--{$request}");
        return $next($request);
    }
}
