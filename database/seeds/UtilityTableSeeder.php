<?php

use Illuminate\Database\Seeder;
use App\Models\UtilityType;

class UtilityTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $params = [
            "Water Bill",
            "PHCN",
            "Others"
        ];

        foreach ($params as $param) {

            $check = UtilityType::where(['name'=>$param])->exists();

            if (!$check) {
                $state = new UtilityType();
                $state->name = $param;
                $state->save();
                $this->command->info('Seeded '.$param);
            }else{
                $this->command->warn('Already seeded '.$param);
            }
        }
    }
}
