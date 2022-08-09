<?php

namespace App\Repositories\VerifyEmail;


use App\Http\Resources\AuthResource;
use App\Services\ResponseService;
use App\Models\User;
use App\Models\EmailVerification;
use Illuminate\Foundation\Auth\RedirectsUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Repositories\CustomerAuth\CustomerAuthInterface;
use Carbon\Carbon;
use App\Jobs\ProcessEmailVerifiedMail;
use App\Jobs\ProcessVerifyEmailMail;






class VerifyEmailRepository implements VerifyEmailInterface{


    protected $responseService,$authrepo,$model;

    public function __construct(ResponseService $responseService, CustomerAuthInterface $authrepo, EmailVerification $model){
        $this->responseService = $responseService;
        $this->authrepo = $authrepo;
        $this->model = $model;
    }


    public function sendMessage($email){

            //create token
            $token = getUniqueToken();

            $date = Carbon::now();

            $params = array('email'=>$email,'token'=>$token,'created_at'=>$date,'verified'=>'0');

            $tokenmodelparam = array("email"=>$params["email"]);
            $token_model = $this->selectRow($tokenmodelparam);
            if(is_null($token_model)){
                $this->insertRow($params);
            }else{
                $conditions = $tokenmodelparam;
                $this->updateRow($conditions,$params);
            }

            // \Illuminate\Support\Facades\Mail::to("$email")->send(new VerifyEmail($token));
            dispatch(new ProcessVerifyEmailMail($email,$token));

            return $this->responseService->getSuccessResource([ 'success' => [
                'message'=>'Email verification Link sent'
            ]

            ]);

    }

    public function insertRow($params){

        $this->model
            ->insert($params);
    }

    public function updateRow($conditions,$params){


        $this->model
            ->where($conditions)
            ->update($params);
    }

    public function deleteRow($param){
        $this->model
                    ->where($param)
                    ->delete();
    }


    public function selectRow($param){
        return $this->model
        ->where($param)
        ->first();
    }


    public function verifyEmail($data){



        $params = array("token"=>$data["token"],"email"=>$data["email"]);
        $token_model = $this->selectRow($params);
        if(is_null($token_model)){
            return $this->responseService->getErrorResource([ 'message'=>'token invalid','field_errors' => [
                'token'=>"Token not valid for user: ".$data["token"]
            ]
                ]);
        }

        $isExpired = $this->checkExpiry($token_model);



        if($isExpired){
            return $this->responseService->getErrorResource([ 'message'=>'token expired','field_errors' => [
                'token'=>"Token Expired: ".$token_model->token
            ]
                ]);
        }

        //save email_verified_at for user


        if(!is_null($token_model)){

            //if token is expired

            $conditions = $params;
            $params = array("updated_at"=>Carbon::now(),"verified"=>"1");
            $this->updateRow($conditions,$params);
                $email = $data["email"];

                // Mail::to("$email")->send(new EmailVerified());
                dispatch(new ProcessEmailVerifiedMail($email));
                return $this->responseService->getSuccessResource([ 'success' => [
                    'success'=>'Email verified'
                ]
                ]);

        }
        return $this->responseService->getErrorResource([ 'message'=>'invalid token','field_errors' => [
            'token'=>"Invalid token: ".$data["token"]
        ]
        ]);

    }

    public function verifyToken($data){
        $param = array("email"=>$data["email"]);
        $user = $this->authrepo->findByColumn($param)->first();
        if(empty($user)){
            return $this->responseService->getErrorResource(['message'=>'Invalid user', 'nonfield_errors' => [
                'user'=>"Invalid user: ".$data["email"]
            ]
            ]);
        }

        $params = array("token"=>$data["token"],"email"=>$data["email"]);
        $token_model = $this->selectRow($params);
        if(is_null($token_model)){
            return $this->responseService->getErrorResource([ 'message'=>'token expired','field_errors' => [
                'token'=>"Token not valid for user: ".$data["token"]
            ]
                ]);
        }



        if(!empty($token_model)){
            //if token is expired
            $isExpired = $this->checkExpiry($token_model);
            if($isExpired){
                return $this->responseService->getErrorResource([ 'message'=>'token expired','field_errors' => [
                    'token'=>"Token Expired: ".$token_model->token
                ]
                    ]);
            }
            return $this->responseService->getSuccessResource([ 'success' => [
                'message'=>'token validated'
            ]
            ]);
        }
        return $this->responseService->getErrorResource(['message'=>'Invalid token' ,'field_errors' => [
            'token'=>"Invalid Token: ".$data["token"]
        ]
        ]);
    }

    public function checkExpiry($token_model){
        $dt = Carbon::parse($token_model->created_at)->addMinutes(10);
            $ct = Carbon::now();
           return $ct > $dt?true:false;
    }

    public function deleteEmail($email){
        $deleteparam = array("email"=>$email);
        $this->deleteRow($deleteparam);
    }

    public function validateEmail($email){
        $params = array("email"=>$email,"verified"=>"1");
        $model = $this->selectRow($params);
        if(is_null($model)){
            return false;
        }
        return true;
    }






}
