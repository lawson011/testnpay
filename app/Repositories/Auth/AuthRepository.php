<?php


namespace App\Repositories\Auth;

use App\Http\Resources\UserResource;
use App\Services\ResponseService;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthRepository implements AuthInterface
{
    protected $user, $responseService;

    public function __construct(User $user, ResponseService $responseService)
    {
        $this->user = $user;
        $this->responseService = $responseService;
    }

    public function create(array $params){
        $model = $this->user;
        $this->setModelProperties($model, $params);
        $model->save();
        $model->assignRole($params['role']);
        return $model;
    }

    public function getRoles($params){
        return $this->user::role($params);
    }

    public function edit($id, $params){
        $model = $this->findById($id);
        auditTray('Edit User',$model,$params);
        $this->setModelProperties($model, $params);
        $model->save();
        if ($params['role']){
            foreach ($model->roles as $role) {

                $model->removeRole($role);
            }
            $model->assignRole($params['role']);
        }

        foreach ($model->permissions as $permission) {
            $model->revokePermissionTo($permission);
        }
        if (isset($params['permissions'])) {
            $model->givePermissionTo($params['permissions']);
        }
    }

    public function allApplicants(){
        return $this->user::role('applicant');
    }

    public function allAdmins(){
        return $this->user;
    }

    //Web authentication
    public function adminLogin($params){

        $user = $this->user::where('email', $params['email'])->first();

        if (!$user) {
            return redirect()->back()->withErrors([
                'email' =>'Invalid Email or Password' //Not to display exact error message to prevent attackers from guessing
            ]);
        }

        if ($user->blocked == 1) {
            return redirect()->back()->withErrors([
                'email' => 'Please contact system administrator' //Account blocked
            ]);
        }

        // If a user with the email was found - check if the specified password
        // belongs to this user
        if (!Hash::check($params['password'], $user->password)) {
            return redirect()->back()->withErrors([
                'email' => 'Invalid Email or Password' //Not to display exact error message to prevent attackers from guessing
            ]);
        }

        if (Auth::attempt(['email' => $params['email'], 'password' => $params['password']])){
            return redirect()->intended('admin/dashboard');
        } else {
            // Auth::logout();
            return redirect()->back()->withErrors([
                'email' => "Contact system administrator"
            ]);
        }

    }

    public function logout()
    {
        $accessToken = auth()->user()->token();

        DB::table('oauth_refresh_tokens')
            ->where('access_token_id', $accessToken->id)
            ->delete();
        // $revoked = DB::table('oauth_access_tokens')->where('id', '=', $id)->update(['revoked' => 1]);
        // logout from all devices
        DB::table('oauth_access_tokens')
            ->where('user_id', Auth::user()->id)
            ->delete();

        $accessToken->revoke();
       // Auth::logout();

        return $this->responseService->getSuccessResource([
            'message'=>'Logout Successful'
        ]);

       // return response()->json(['msg' => 'Logout Successful','status' => 200]);
    }

    public function findById(int $id)
    {
        return $this->user::find($id);
    }


    public function findByColumn(array $params)
    {
        return $this->user::where($params);
    }

    public function authUser(){
        return Auth::user();
    }

    private function setModelProperties($model, $params){
        $model->first_name = strtoupper($params['first_name']);
        $model->last_name = strtoupper($params['last_name']);
        $model->phone = $params['phone'];
        if (isset($params['email'])) {
            $model->email = $params['email'];
        }
        if ( $params['gender']) {
            $model->gender = $params['gender'];
        }
        if (isset($params['password'])) {
            $model->password = bcrypt($params['password']);
        }
        $model->email_verified_at = Carbon::now();
    }
}
