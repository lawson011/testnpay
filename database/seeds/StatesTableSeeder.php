<?php

use App\Models\State;
use Illuminate\Database\Seeder;

class StatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $params = json_decode(file_get_contents('data/states.json',true),true);

        foreach ($params as $param) {

            $check = State::where(['name'=>$param])->exists();

            if (!$check) {
                $state = new State();
                $state->name = $param;
                $state->save();
                $this->command->info('Seeded '.$param);
            }else{
                $this->command->warn('Already seeded '.$param);
            }
        }
    }
}
