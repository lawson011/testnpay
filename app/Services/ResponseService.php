<?php

namespace App\Services;

use Illuminate\Support\Arr;


class ResponseService
{

    public function getErrorResource(array $attributes = null)
    {

        $status_code = Arr::get($attributes, "status_code", 422);
        $message = Arr::get($attributes, "message", "Failed");

        $non_field_errors = Arr::get($attributes, "non_field_errors");

        $field_errors = Arr::get($attributes, "field_errors");

        $error = [
            "status" => $status_code,
            "message" => $message,
            "code" => Arr::get($attributes, "code", "SYS_01")//ErrorCode::AUTH_01
        ];

        if ($non_field_errors) Arr::set($error, "non_field_errors", $non_field_errors);

        if ($field_errors) Arr::set($error, "field_errors", $field_errors);


        return response()->json([
            "status" => false,
            "message" => $message,
            "data" => $error
        ], $status_code);
    }

    public function getSuccessResource(array $attributes = null)
    {

        $status_code = Arr::get($attributes, "status_code", 200);
        $message = Arr::get($attributes, "message", "Successful");
        $data = Arr::get($attributes, "data");

        $result = [
            "status" => true,
            "message" => $message,
            "data" => [
                "status" => $status_code,
                "message" => $message
            ]
        ];

        if ($data) {
            Arr::set($result, "data", $data);
        }

        return response()->json($result, $status_code);
    }

}
