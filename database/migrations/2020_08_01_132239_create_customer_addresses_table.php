<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_addresses', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('customer_id');
            $table->string('address_name');
            $table->string('firstname');
            $table->string('lastname');
            $table->string('street');
            $table->string('province');
            $table->string('municipality');
            $table->string('barangay');
            $table->string('province_id');
            $table->string('municipality_id');
            $table->string('barangay_id');
            $table->string('zip_code');
            $table->string('mobile_no');
            $table->timestamps();
            $table->foreign('customer_id')->references('id')->on('customers');      
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('addresses');
    }
}
