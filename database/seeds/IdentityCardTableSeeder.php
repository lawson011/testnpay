<?php

use Illuminate\Database\Seeder;
use App\Models\IdentityCardType;

class IdentityCardTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $params = [
            "National ID Card",
            "Driver's License",
            "Voter's Card",
            "NIN Slip",
            "International Passport",
            "Others"
        ];

        foreach ($params as $param) {

            $check = IdentityCardType::where(['name'=>$param])->exists();

            if (!$check) {
                $state = new IdentityCardType();
                $state->name = $param;
                $state->save();
                $this->command->info('Seeded '.$param);
            }else{
                $this->command->warn('Already seeded '.$param);
            }
        }
    }
}
