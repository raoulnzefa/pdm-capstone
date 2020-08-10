<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShippingAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipping_addresses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('order_number');
            $table->string('shipping_firstname');
            $table->string('shipping_lastname');
            $table->string('shipping_street');
            $table->string('shipping_province');
            $table->string('shipping_municipality');
            $table->string('shipping_barangay');
            $table->integer('shipping_province_id');
            $table->integer('shipping_municipality_id');
            $table->integer('shipping_barangay_id');
            $table->string('shipping_zip_code');
            $table->string('shipping_mobile_no');
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
        Schema::dropIfExists('shipping_addresses');
    }
}
