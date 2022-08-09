<?php

use Illuminate\Database\Seeder;

use App\Models\BillerCategory;

class BillCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $params = json_decode(file_get_contents('data/billcategory.json',true),true);

        foreach ($params as $param) {

            $check = BillerCategory::where(['identifier'=>$param['identifier']])->exists();

            if (!$check) {
                $billerCategory = new BillerCategory();
                $billerCategory->identifier = $param['identifier'];
                $billerCategory->save();
                $this->command->info('Seeded '.$param['identifier']);
            }else{
                $this->command->warn('Already seeded '.$param['identifier']);
            }

        }
    }
}
