<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\ProfilePassword;
use Mail;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Admin\ChangePasswordRequest;
use App\Repositories\Auth\AuthInterface;

class ProfileController extends Controller
{

    public $auth;
    public function __construct(AuthInterface $auth){
        $this->auth = $auth;
    }

    public function index(){
        $data = array('user'=>Auth::user());
        return view('profile.index',compact('data'));
    }
    public function sendPasswordMail(){
        $email = Auth::user()->email;
        $path = "/admin/profile/changepassword";
        $url = Request()->root().$path;
        Mail::to("$email")->send(new ProfilePassword($url));
    }
    public function changePassword(){

        return view('profile.changepassword');
    }
    public function savePassword(ChangePasswordRequest $request){

        $user = Auth::user();
        $user->password = bcrypt($request['password']);
        $user->save();
        return back()->with('success', 'Password changed successfully');
    }
}
