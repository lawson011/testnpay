<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone');
            $table->timestamp('phone_verified_at')->nullable();
            $table->string('email');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('nuban')->unique();
            $table->string('cba_id')->unique();
            $table->string('username');
            $table->string('password');
            $table->string('transaction_pin')->nullable();
            $table->boolean('is_staff')->default(0);
            $table->boolean('is_agent')->default(0);
            $table->boolean('blocked')->default(0);
            $table->string('gender')->nullable();
            $table->string('referral_code')->unique();
            $table->string('referred_by')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('customers');
    }
}
