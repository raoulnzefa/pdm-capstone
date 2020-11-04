<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDefectiveProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('defective_products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('inventory_number');
            $table->unsignedInteger('replacement_request_id');
            $table->timestamps();
            $table->foreign('inventory_number')->references('number')->on('inventories');
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
        Schema::dropIfExists('defective_products');
    }
}
