<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')
                ->references('id')
                ->on('customers')
                ->onDelete('cascade');
            $table->foreignId('loan_setting_id')
                ->references('id')
                ->on('loan_settings')
                ->onDelete('cascade');
            $table->foreignId('loan_status_id')
                ->references('id')
                ->on('loan_statuses')
                ->onDelete('cascade');
            $table->double('amount');
            $table->string('rate');  //number of days
            $table->string('term');
            $table->string('service_charge');
            $table->double('repay_amount');
            $table->string('cba_loan_product_code')->nullable();
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
        Schema::dropIfExists('loans');
    }
}
