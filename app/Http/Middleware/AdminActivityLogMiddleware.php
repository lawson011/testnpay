<?php

namespace App\Http\Middleware;

use App\Models\AdminActivityLog;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdminActivityLogMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $method = strtoupper($request->getMethod());

        $uri = $request->getPathInfo();

        $bodyAsJson = json_encode($request->except(config('http-logger.except')));

        AdminActivityLog::create([
            'user_id' => Auth::id() ?? null,
            'platform' => Request()->header("Platform") ?? env('ADMIN_APP_URL'),
            'ip' => $request->ip(),
            'action' => $method,
            'url' => $uri,
            'body' => $bodyAsJson
        ]);
        return $next($request);
    }
}
