<?php

namespace App\Services;
use Illuminate\Http\Request;

class HttpLogProfileService implements \Spatie\HttpLogger\LogProfile{

    public function shouldLogRequest(Request $request): bool{
        return in_array(strtolower($request->method()), ['post', 'put', 'patch', 'delete','get']);
    }
}