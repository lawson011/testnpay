<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RolesTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(BanksTableSeeder::class);
        $this->call(StatesTableSeeder::class);
        $this->call(IdentityCardTableSeeder::class);
        $this->call(UtilityTableSeeder::class);
        $this->call(LoanSettingTableSeeder::class);
        $this->call(LoanStatusTableSeeder::class);
        $this->call(RepaymentMethodTableSeeder::class);
        $this->call(FixedAccountSettingTableSeeder::class);
        $this->call(CardRequestStatusTableSeeder::class);
        $this->call(CustomerRegistrationSettingSeeder::class);
        $this->call(BillCategoryTableSeeder::class);
        $this->call(BillTableSeeder::class);
    }
}
