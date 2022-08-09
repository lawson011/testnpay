<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('billers', function (Blueprint $table) {
             $table->id();
             $table->foreignId('billers_category_id')->constrained('billers');//service type
             $table->string('identifier');//
             $table->string('slug');
             $table->string('billers');//billers
             $table->string('code');//billers alias code
             $table->string('operation');//operations
             $table->boolean('status');//status
             $table->boolean('verification');//verification
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
        Schema::dropIfExists('billers');
    }
}
