<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFixedAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fixed_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id');
            $table->foreignId('fixed_account_setting_id');
            $table->double('amount');
            $table->string('tenure');
            $table->string('interest_rate');
            $table->string('product_code');
            $table->boolean('interest_monthly'); //if customer want the interest on a monthly basics
            $table->boolean('rollover')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fixed_accounts');
    }
}
