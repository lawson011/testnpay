<?php

use Illuminate\Database\Seeder;

use App\Models\Biller;
use Illuminate\Support\Str;

class BillTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $params = json_decode(file_get_contents('data/bill.json',true),true);

        foreach ($params as $param) {

            $check = Biller::where(['identifier'=>$param['identifier']])->exists();

            if (!$check) {
                $bill = new Biller();
                $bill->billers_category_id = $param['billers_category_id'];
                $bill->identifier = $param['identifier'];
                $bill->slug = Str::slug($param['identifier'],'');
                $bill->billers = $param['billers'];
                $bill->code = $param['code'];
                $bill->operation = $param['operation'];
                $bill->status = $param['status'];
                $bill->verification = $param['verification'];
                $bill->save();

                $this->command->info('Seeded '.$param['identifier']);
            }else{
                $this->command->warn('Already seeded '.$param['identifier']);
            }

        }
    }
}
