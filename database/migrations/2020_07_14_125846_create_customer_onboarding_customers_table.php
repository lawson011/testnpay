<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerOnboardingCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_onboarding_customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone')->unique();
            $table->string('email')->unique()->nullable();
            $table->string('nuban')->nullable();
            $table->string('cba_id');
            $table->string('amount')->nullable();
            //If user has downloaded the app, activate will be true
            $table->boolean('activate')->default(0);
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
        Schema::dropIfExists('customer_onboarding_customers');
    }
}
