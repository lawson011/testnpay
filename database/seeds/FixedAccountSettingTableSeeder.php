<?php

use Illuminate\Database\Seeder;
use App\Models\FixedAccount\FixedAccountSetting;

class FixedAccountSettingTableSeeder extends Seeder
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
                'tenure' => 30,
                'interest_rate' => 10.3,
                'daily_interest' => 000.2,
                'product_code' => 302,
                'active' => true
            ],
            [
                'tenure' => 90,
                'interest_rate' => 15.3,
                'daily_interest' => 000.3,
                'product_code' => 302,
                'active' => true
            ],
            [
                'tenure' => 120,
                'interest_rate' => 18.3,
                'daily_interest' => 000.7,
                'product_code' => 302,
                'active' => true
            ]
        ];

        foreach ($params as $param) {

            $check = FixedAccountSetting::where(['tenure'=>$param['tenure']])->exists();

            if (!$check) {
                $repaymentMethod = new FixedAccountSetting();
                $repaymentMethod->tenure = $param['tenure'];
                $repaymentMethod->interest_rate = $param['interest_rate'];
                $repaymentMethod->product_code = $param['product_code'];
                $repaymentMethod->active = $param['active'];
                $repaymentMethod->save();
                $this->command->info('Seeded '.$param['tenure']);
            }else{
                $this->command->warn('Already seeded '.$param['tenure']);
            }

        }
    }
}
