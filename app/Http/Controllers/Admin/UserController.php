<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BlockAdminRequest;
use App\Jobs\AdminResetPasswordJob;
use App\Mail\Admin\ProfilePassword;
use App\Repositories\AdminBlockStatus\AdminBlockStatusInterface;
use App\Repositories\Auth\AuthInterface;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;
use  Illuminate\Support\Facades\Mail;

class UserController extends Controller
{

    protected $auth,$block_status;

    public function __construct(AuthInterface $auth, AdminBlockStatusInterface $block_status)
    {
        $this->auth = $auth;
        $this->block_status = $block_status;
    }

    /**
     * Show all users.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request, Builder $builder)
    {

        $data = $this->auth->allAdmins()->with(['roles','permissions'])->select('users.*')->latest();
        if ($request->ajax()) {
            return $this->allUserData($data);
        }

        $html = $builder->columns($this->userColumns());

        return view('user.index', compact('data', 'html'));

    }

    public function create(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required|numeric|digits:11',
            'gender' => 'required|in:Male,Female',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|exists:roles,id'
        ]);

        $params = $request->all();
        $params['password'] = Str::random(6);

        $this->auth->create($params);

        $path = "/admin/profile/changepassword";

        $data['url'] = Request()->root().$path;
        $data['password'] = $params['password'];

        Mail::to($params['email'])->send(new ProfilePassword($data));

        return back()->with('success', 'Account created successfully');
    }

    /**
     * Send link to reset password
     *
     * @param $id
     *
     * @return RedirectResponse
     */
    public function resetPassword($id)
    {
        $user = $this->auth->findById(decrypt($id));
        //Send email with link to reset password
        $token = Str::random(60);
        $url = URL::to('/')."/change-password/$token";

        //Create Password Reset Token
        DB::table('password_resets')->updateOrInsert([
            'email' => $user->email,
            'token' => $token,
            'created_at' => now()
        ]);

        $data = [
            'name' => $user->full_name,
            'url' => $url,
            'email' => $user->email
        ];

        dispatch(new AdminResetPasswordJob($data));

        return back()->with('success', 'Password reset link has been sent to '.$user->email);
    }

    /**
     * Display page for password reset
     *
     * @param $token
     *
     * @return Factory|RedirectResponse|View
     */
    public function changePassword($token)
    {
        $reset = DB::table('password_resets')
            ->where('token', $token)
            ->whereBetween('created_at', [now()->subMinutes(20), now()])->first();

        if (! $reset) {
            return redirect()->to('/')->with('error_message', 'Token expired');
        }

        return view('change-password', compact('token'));
    }

    public function passwordReset($token, Request $request)
    {
        $request->validate([
            'password' => 'required|confirmed'
        ]);
        $reset = DB::table('password_resets')
            ->where('token', $token)->first();

        if (! $reset) {
            return redirect()->to('/')->with('error_message', 'Token expired');
        }

        $user = $this->auth->findByColumn([
            'email' => $reset->email
        ])->first();

        $user->password = bcrypt($request->input('password'));
        $user->save();

        return redirect()->to('/')->with('success', 'Password successfully changed, please login');
    }

    public function edit($id, Request $request){
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required|numeric|digits:11',
            'gender' => 'required|in:Male,Female',
            'role' => 'required|array|exists:roles,id',
            'permissions' => 'nullable|array|exists:permissions,id'
        ]);

        $params = $request->except('email');

        $this->auth->edit($id, $params);

        return back()->with('success', 'Update Successful');
    }

    public function block(BlockAdminRequest $request)
    {
        $user = $this->auth->findById(decrypt($request->id));
        $user->blocked = true;
        $user->save();
        $this->block_status->create(
            [
                "user_id"=>decrypt($request->id),
                "reason"=>$request->reason,"status"=>1
            ]
        );
    }

    public function unblock(BlockAdminRequest $request)
    {

        $user = $this->auth->findById(decrypt($request->id));
        $user->blocked = false;
        $user->save();
        $block_status = $this->block_status->create(
            array("user_id"=>decrypt($request->id),"reason"=>$request->reason,"status"=>0));
    }

    private function userColumns()
    {
        return [
            [
                'title' => 'Name',
                'name' => 'first_name',
                'data' => 'first_name'
            ],
            [
                'title' => 'Email',
                'name' => 'email',
                'data' => 'email'
            ],
            [
                'title' => 'Phone',
                'name' => 'phone',
                'data' => 'phone'
            ],
            [
                'title' => 'Gender',
                'name' => 'gender',
                'data' => 'gender'
            ],
            [
                'title' => 'Role',
                'name' => 'role',
                'data' => 'role'
            ],
            [
                'title' => 'Direct Permissions',
                'name' => 'permission',
                'data' => 'permission'
            ],
            [
                'title' => 'Action',
                'name' => 'action',
                'data' => 'action'
            ]
        ];
    }

    private function allUserData($data)
    {
        try {

            return DataTables::of($data)
                ->addColumn('action', function ($field) {
                    if (Auth::id() != $field->id) {
                        $editButton = "<button id='editAdmin' data-details='" . $field . "' data-id='" . encrypt($field->id) . "'
class='btn btn-dribbble ml-2'>Edit</button><br><br>";
                        $resetLink = "
<a class='btn btn-success ml-2' href=".route('admin.user.reset-password', encrypt($field->id)).">
Reset password</a><br><br>";
                        if ($field->blocked == false) {
                            return $resetLink. ' ' .$editButton . ' ' . "<button id='blockAdmin' data-id='" . encrypt($field->id) . "' class='btn btn-success ml-2' data-toggle='modal' data-animation='bounce' data-target='.show_block_dropdown'>Block</button>";
                        } elseif ($field->blocked == true) {
                            return $resetLink. ' ' .$editButton . ' ' . "<button id='unblockAdmin' data-id='" . encrypt($field->id) . "' class='btn btn-danger ml-2' data-toggle='modal' data-animation='bounce' data-target='.show_unblock_dropdown'>unblock</button>";
                        }
                    }
                   return 'You can not update your account';
                })
                ->addColumn('role', function ($field) {
                    return $field->roles->map(function ($roleName) {
                        return $roleName->name;
                    })->implode('<br>');
                })
                ->editColumn('first_name', function ($field) {
                    return $field->full_name;
                })
                ->addColumn('permission', function ($field) {
                    // Direct permissions
                    return $field->getDirectPermissions()->map(function ($field) {
                        return $field->name;
                    })->implode('<br>');
                })
                ->rawColumns(['action', 'role', 'permission'])
                ->removeColumn(['created_at', 'update_at', 'deleted_at'])
                ->make(true);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}

