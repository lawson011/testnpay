<?php

use App\Models\Bank;
use Illuminate\Database\Seeder;

class BanksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $params = json_decode(file_get_contents('data/banks.json',true),true);

        foreach ($params as $param) {

            $check = Bank::where(['name'=>$param['bank_name']])->exists();

            if (!$check) {
                $bank = new Bank();
                $bank->name = $param['bank_name'];
                $bank->abbr = $param['bank_name_short'];
                $bank->cbn_code = $param['cbn_code'];
                $bank->save();
                $this->command->info('Seeded '.$param['bank_name']);
            }else{
                $this->command->warn('Already seeded '.$param['bank_name']);
            }

        }
    }
}
