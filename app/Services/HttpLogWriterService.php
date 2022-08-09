<?php
namespace App\Services;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class HttpLogWriterService implements \Spatie\HttpLogger\LogWriter{

    public function __construct(){

    }

    public function logRequest(Request $request,bool $error = false):void{
        $method = strtoupper($request->getMethod());

        $uri = $request->getPathInfo();

        $bodyAsJson = json_encode($request->except(config('http-logger.except')));

        $message = "{$method} {$uri} - {$bodyAsJson}";
        $data = array("action"=>$method,"uri" => $uri,"body"=>$bodyAsJson,"error"=>$error,"ip" =>$request->ip());
        if($error){
            Log::critical($message);
        }else{
            Log::info($message);
        }
        createActivityLog($data);
    }

}

