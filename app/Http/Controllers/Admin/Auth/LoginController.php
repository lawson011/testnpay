<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminLoginRequest;
use App\Repositories\Auth\AuthInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    protected $auth;
    public function __construct(AuthInterface $auth)
    {
        $this->auth = $auth;
    }

    public function login(AdminLoginRequest $request){
        $params = $request->all();
        return $this->auth->adminLogin($params);
    }

    public function showLoginForm(){
        return view('login');
    }

    public function logout(){
        Auth::logout();
        return redirect()->route('login');
    }
}
