<?php

use Illuminate\Database\Seeder;
use App\Models\Customers\UsernameToSkipDetachDevice;

class UsernameToSkipDetachDeviceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $params = [
            'Akasalu',
            'nwudoebuka',
            'nakeeljr'
        ];

        foreach ($params as $param) {

            $check = UsernameToSkipDetachDevice::where(['username'=>$param])->exists();

            if (!$check) {
                $state = new UsernameToSkipDetachDevice();
                $state->username = $param;
                $state->user_id = 1;
                $state->save();
                $this->command->info('Seeded '.$param);
            }else{
                $this->command->warn('Already seeded '.$param);
            }
        }

    }
}
