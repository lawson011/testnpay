<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index(){
        $roles = Role::latest()->get();
        $permissions = Permission::all();
        return view('role.index',compact('roles','permissions'));
    }

    public function show(){
        $permissions = Permission::all();
        return view('role.show',compact('permissions'));
    }

    public function create(Request $request){
        $request->validate([
           'role' => 'required|unique:roles,name',
           'permissions' => 'required|array'
        ]);

        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        $role = new Role();
        $role->name = $request->input('role');
        $role->save();
        $role->givePermissionTo($request->input('permissions'));

        return redirect()->route('admin.role.index');
    }

    public function update(Request $request, $id){

        if($role = Role::findOrFail(decrypt($id))) {

            // admin role has everything
            if($role->name === 'super-admin') {
                $role->syncPermissions(Permission::all());
                return back()->with('success',$role->name . ' permissions has been updated.');
            }

            $permissions = $request->get('permissions', []);

            $role->syncPermissions($permissions);

            return back()->with('success',$role->name . ' permissions has been updated.');
        } else {
            return back()->with('error_message','Role does not exist');
        }

    }

}
