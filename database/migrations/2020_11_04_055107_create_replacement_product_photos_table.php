<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReplacementProductPhotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('replacement_product_photos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('replacement_request_id');
            $table->string('product_photo_url');
            $table->string('product_photo_path');
            $table->timestamps();
            $table->foreign('replacement_request_id')->references('id')->on('replacement_requests');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('replacement_product_photos');
    }
}
