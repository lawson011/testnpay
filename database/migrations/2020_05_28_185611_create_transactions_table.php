<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('customer_id');
            $table->string('transaction_reference')->unique();
            $table->string('nip_session')->nullable();
            $table->double('amount');
            $table->string('sender_account_number');
            $table->string('receiver_account_number');
            $table->string('narration');
            $table->string('transaction_type');//local or nip or gl
            $table->string('channel');
            $table->string('bank')->default('nuturemfb');
            $table->string('device')->nullable();
            $table->boolean('status')->default(0);
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
        Schema::dropIfExists('transactions');
    }
}
