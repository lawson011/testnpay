<?php

use Illuminate\Database\Seeder;
use \Spatie\Permission\Models\Role;
use \Spatie\Permission\Models\Permission;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = ['update loans status','view approve loans','view loan awaiting approval','view declined loans',
            'view all customer', 'view customer details','view all admins','create admin','view admin', 'block admin',
            'unblock admin', 'block customer', 'unblock customer', 'update staff status',
            'edit admin', 'view roles', 'create role',  'update role permission', 'loan settings', 'update agent status',
            'sync customer info with CBA', 'view bills log'
            ];

        foreach ($permissions as $permission) {
            $check = Permission::where(['name'=>$permission])->exists();

            if (!$check) {
                $role = new Permission();
                $role->name = $permission;
//                $role->guard_name = 'api';
                $role->save();
                $this->command->info('Seeded '.$permission);
            }else{
                $this->command->warn('Already seeded '.$permission);
            }
        }

        $params = [
            [
                'name' => 'admin',
                'permission' => [
                    'update loans status','view approve loans','view declined loans','view loan awaiting approval',
                    'view all customer', 'view customer details', 'block customer', 'unblock customer',
                ]
            ],
            [
                'name' => 'loan-officer',
                'permission' => [
                    'update loans status', 'view approve loans', 'view loan awaiting approval',
                    'view declined loans', 'view customer details'
                ]
            ],
            [
                'name' => 'super-admin',
                'permission' => Permission::all()
            ]
        ];

        foreach ($params as $param) {
            $check = Role::where(['name'=>$param['name']])->exists();

            if (!$check) {
                $role = new Role();
                $role->name = $param['name'];
                $role->save();
                $role->givePermissionTo($param['permission']);
                $this->command->info('Seeded '.$param['name']);
            }else{
                $this->command->warn('Already seeded '.$param['name']);
            }
        }
    }
}
