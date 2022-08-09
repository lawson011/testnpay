<?php

use Illuminate\Database\Seeder;
use App\Models\Customers\CardRequestStatus;

class CardRequestStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = [
          'Processing',
          'Approved',
          'Delivered',
          'Declined'
        ];

        foreach ($statuses as $status){
            $check = CardRequestStatus::where('name',$status)->exists();

            if (!$check){
                $model = new CardRequestStatus();
                $model->name = $status;
                $model->save();
                $this->command->info('Seeded '.$status);
            }else{
                $this->command->warn('Already seeded '.$status);
            }
        }
    }
}
