<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUploadPhotoCountToCustomerBioDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_bio_data', function (Blueprint $table) {
            $table->integer('upload_photo_count')->default(0)->after('upload_photo_to_cba');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer_bio_data', function (Blueprint $table) {
            $table->integer('upload_photo_count');
        });
    }
}
