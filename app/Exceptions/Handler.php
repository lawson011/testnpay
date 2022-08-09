<?php

namespace App\Exceptions;

use App\Repositories\CustomerAuth\CustomerAuthInterface;
use App\Services\HttpLogWriterService;
use Exception;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\ResponseService;
use Illuminate\Http\Client\RequestException as IlluminateRequestException;

use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
        'pin',
        'confirm_pin',
        'token',
        'otp_code',
        'opt'
    ];

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        // the request is coming from admin domain
        if (env('ADMIN_APP_URL') == explode('//', URL::to('/'))[1]) {

            return redirect()->guest(route('login'));

        } else {

            return (new ResponseService())->getErrorResource([
                'status_code' => '401',
                'message' => 'Unauthenticated.',
                'code' => '401'
            ]);
        }

    }

    /**
     * Report or log an exception.
     *
     * @param Throwable $exception
     * @return void
     *
     * @throws Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    protected $httplogwriterservice;

    /**
     * Render an exception into an HTTP response.
     *
     * @param Request $request
     * @param Throwable $exception
     * @return Response
     *
     * @throws Throwable
     */
    public function render($request, Throwable $exception)
    {

        if ($exception instanceof  ThrottleRequestsException){
            logger($exception);
            app(CustomerAuthInterface::class)->logout();
            return (new ResponseService())->getErrorResource([
                'message' =>'Too many request, please try in few minute',
                'status_code' => 429
            ]);
        }

        if ($exception instanceof QueryException) {
            return (new ResponseService())->getErrorResource([
                'message' => $exception->getMessage(),
            ]);
        }

        if ($exception instanceof ModelNotFoundException) {
            return (new ResponseService())->getErrorResource([
                'message' => $exception->getMessage()
            ]);
        }

        if ($exception instanceof ApplicationProcessFailedException) {
            return (new ResponseService())->getErrorResource([
                'message' => $exception->getMessage(),
                'status_code' => $exception->getCode()
            ]);
        }

        if ($exception instanceof IlluminateRequestException) {
            return (new ResponseService())->getErrorResource([
                'message' => $exception->getMessage(),
                'status_code' => $exception->getCode()
            ]);
        }


        if ($exception instanceof NotFoundHttpException) {

            if ($request->path() != '/' || !empty($request->query())) {
                $writer = new HttpLogWriterService();
                $writer->logRequest($request, true);
                return response()->json(['error' => 'Not Found'], 404);
            }

        }

        return parent::render($request, $exception);
    }
}
