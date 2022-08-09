<?php

use Illuminate\Database\Seeder;
use App\Models\Customers\CustomerRegistrationSetting;

class CustomerRegistrationSettingSeeder extends Seeder
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
                'product_code' => 900, //from cba
                'account_officer_code' => 'PT00158', //from cba
                'active' => true,
            ]
        ];

        foreach ($params as $param) {

            $check = CustomerRegistrationSetting::where([
                'product_code'=>$param['product_code'],'account_officer_code'=>$param['account_officer_code'],
                'active'=>$param['active']
            ])->exists();

            if (!$check) {
                $customerSettings = new CustomerRegistrationSetting();
                $customerSettings->product_code = $param['product_code'];
                $customerSettings->account_officer_code = $param['account_officer_code'];
                $customerSettings->active = $param['active'];
                $customerSettings->user_id = 1;
                    $customerSettings->save();
                $this->command->info('Seeded '.$param['product_code']);
            }else{
                $this->command->warn('Already seeded '.$param['product_code']);
            }

        }
    }
}
