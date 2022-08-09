<?php

use Illuminate\Database\Seeder;
use App\Models\Loans\LoanStatus;

class LoanStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $datas = ['Awaiting Approval','Declined','Approved'];

        foreach ($datas as $data){
            $checkLoanStatus = LoanStatus::whereName($data)->exists();
            if (!$checkLoanStatus){
                $loanStatus = new LoanStatus();
                $loanStatus->name = $data;
                $loanStatus->save();
                $this->command->info('Seeded ' .$data);
            }else{
                $this->command->warn('Already seeded '. $data);
            }
        }

    }
}
