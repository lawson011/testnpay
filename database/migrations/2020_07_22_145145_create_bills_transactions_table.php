<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillsTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bills_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id');
            $table->foreignId('billers_category_id');
            $table->string('trx_reference')->unique();
            $table->string('etrazanct_reference')->unique();
            $table->string('reference')->unique();
            $table->string('message');
            $table->string('account');
            $table->double('amount');
            $table->boolean('status')->default(0);
            $table->index('trx_reference');
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
        Schema::dropIfExists('bills_transactions');
    }
}
