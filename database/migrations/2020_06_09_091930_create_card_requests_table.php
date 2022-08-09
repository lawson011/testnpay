<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCardRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('card_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained();
            $table->foreignId('card_request_status_id')->constrained();
            $table->enum('pickup_type',['Delivery','Pickup']);
            $table->foreignId('user_id')->nullable(); // user who updated the status
            $table->longText('user_remarks')->nullable();//if status is declined
            $table->longText('customer_remarks')->nullable();
            $table->dateTime('date_updated')->nullable();
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
        Schema::dropIfExists('card_requests');
    }
}
