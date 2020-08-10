<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStorePickupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_pickups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('order_number');
            $table->string('pickup_firstname');
            $table->string('pickup_lastname');
            $table->dateTime('pickup_in_store')->nullable();
            $table->timestamps();
            $table->foreign('order_number')->references('number')->on('orders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_pickups');
    }
}
