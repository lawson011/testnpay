<?php

use Illuminate\Database\Seeder;

class TermsAndConditionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Customers\Customer::where('terms_and_condition', true)->update([
           'terms_and_condition' => false
        ]);
    }
}
