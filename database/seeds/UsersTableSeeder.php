<?php

use Illuminate\Database\Seeder;

use App\Models\User;

class UsersTableSeeder extends Seeder
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

        $params = [
            [
                'first_name' => 'Onah',
                'last_name' => 'Sunday',
                'email' => 's.onah@nuture.tech',
                'phone' => '0806232120021',
                'password' => bcrypt('123456789'),
                'role' => 'super-admin',
                'gender' => 'Male',
            ],
            [
                'first_name' => 'Akinbola',
                'last_name' => 'Asalu',
                'email' => 'a.asalu@nuture.tech',
                'phone' => '08072834406',
                'password' => bcrypt('1234567'),
                'role' => 'super-admin',
                'gender' => 'Male',
            ]
        ];

        foreach ($params as $param) {
            $check = User::where(['email'=>$param['email'],'phone'=>$param['phone']])->exists();

            if (!$check) {
                $user =  new User();
                $user->first_name = $param['first_name'];
                $user->last_name = $param['last_name'];
                $user->email = $param['email'];
                $user->phone = $param['phone'];
                $user->password = $param['password'];
                $user->gender = $param['gender'];
                $user->email_verified_at = \Illuminate\Support\Carbon::now();
                $user->save();
                $user->assignRole($param['role']);

                $this->command->info('Seeded '.$param['email']);
            }else{
                $this->command->warn('Already seeded '.$param['email']);
            }
        }
    }
}
