<?php

use App\Models\Loans\LoanSetting;
use Illuminate\Database\Seeder;

class LoanSettingTableSeeder extends Seeder
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
                'term' => '15', //number of daye
                'rate' => '20', //percentage
                'amount' => '1000',
                'service_charge' => '0.0',
                'cba_loan_product_code' => 419
            ],
            [
                'term' => '15',
                'rate' => '20',
                'amount' => '2000',
                'service_charge' => '0.0',
                'cba_loan_product_code' => 419
            ],
            [
                'term' => '15',
                'rate' => '20',
                'amount' => '3000',
                'service_charge' => '0.0',
                'cba_loan_product_code' => 419
            ],
            [
                'term' => '15',
                'rate' => '20',
                'amount' => '4000',
                'service_charge' => '0.0',
                'cba_loan_product_code' => 419
            ],
            [
                'term' => '15',
                'rate' => '20',
                'amount' => '5000',
                'service_charge' => '0.0',
                'cba_loan_product_code' => 419
            ]
        ];

        foreach ($params as $param) {

            $check = LoanSetting::where([
                'rate'=>$param['rate'],'term'=>$param['term'],
                'amount'=>$param['amount']
            ])->exists();

            $serviceCharge = (($param['service_charge']*$param['amount'])/100);
            $rate = (($param['rate']*$param['amount'])/100);

            if (!$check) {
                $loanSetting = new LoanSetting();
                $loanSetting->rate = $param['rate'];
                $loanSetting->term = $param['term'];
                $loanSetting->amount = $param['amount'];
                $loanSetting->repayment_amount = $serviceCharge+$rate+$param['amount'];
                $loanSetting->service_charge = $param['service_charge'];
                $loanSetting->cba_loan_product_code = $param['cba_loan_product_code'];
                $loanSetting->save();
                $this->command->info('Seeded '.$param['amount']);
            }else{
                $this->command->warn('Already seeded '.$param['amount']);
            }

        }
    }
}
