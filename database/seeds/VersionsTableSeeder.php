<?php

use Illuminate\Database\Seeder;
use App\Models\Version;

class VersionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $params = [
            [
                'value'=>'1.12',
                'platform'=>'Android',
                'active'=>1
            ],
            [
                'value'=>'1.0.2',
                'platform'=>'IOS',
                'active'=>1
            ]
        ];
        foreach ($params as $param) {

            $check = Version::where([
                ['value','=',$param['value']],
                ['platform','=',$param['platform']]
            ])->exists();

            if (!$check) {
                 Version::where([
                    ['platform','=',$param['platform']]
                ])->update([
                    'active' => false
                ]);
//                $updateVersion->active = false;
//                $updateVersion->save();

                $version =  new Version();
                $version->value = $param['value'];
                $version->platform = $param['platform'];
                $version->active = $param['active'];
                $version->save();

                $this->command->info("Seeded {$param['platform']} {$param['value']}");
            }else{
                $this->command->warn("Already seeded {$param['platform']} {$param['value']}");
            }

        }
    }
}
