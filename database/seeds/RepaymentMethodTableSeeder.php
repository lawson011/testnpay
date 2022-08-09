<?php

use App\Models\Loans\RepaymentMethod;
use Illuminate\Database\Seeder;

class RepaymentMethodTableSeeder extends Seeder
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
                'method' => 'Scheduler'
            ],
            [
                'method' => 'Self'
            ]
        ];

        foreach ($params as $param) {

            $check = RepaymentMethod::where(['name'=>$param['method']])->exists();

            if (!$check) {
                $repaymentMethod = new RepaymentMethod();
                $repaymentMethod->name = $param['method'];
                $repaymentMethod->save();
                $this->command->info('Seeded '.$param['method']);
            }else{
                $this->command->warn('Already seeded '.$param['method']);
            }

        }
    }
}
