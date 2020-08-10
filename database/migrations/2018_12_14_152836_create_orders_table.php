<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('number')->unique();
            $table->unsignedInteger('customer_id');
            $table->string('order_status');
            $table->dateTime('order_created');
            $table->string('order_delivery_method');
            $table->string('order_payment_status');
            $table->string('order_payment_method');
            $table->integer('order_quantity');
            $table->decimal('order_subtotal',9,2);
            $table->decimal('order_total',9,2);
            $table->string('order_paypal_url')->nullable();
            $table->dateTime('order_payment_date')->nullable();
            $table->dateTime('order_cancelled')->nullable();
            $table->dateTime('order_completed')->nullable();
            $table->dateTime('order_warranty')->nullable();
            $table->string('order_remarks')->nullable();
            $table->integer('viewed')->default(0);
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
        Schema::dropIfExists('orders');
    }
}
