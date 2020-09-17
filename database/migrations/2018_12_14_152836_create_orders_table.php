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
            $table->string('order_shipping_method');
            $table->string('order_payment_status');
            $table->string('order_payment_method');
            $table->integer('order_quantity');
            $table->decimal('order_subtotal',9,2);
            $table->decimal('order_shipping_fee',9,2)->nullable();
            $table->decimal('order_total',9,2);
            $table->decimal('order_shipping_discount',9,2)->nullable();
            $table->string('order_paypal_url')->nullable();
            $table->dateTime('order_payment_date')->nullable();
            $table->dateTime('order_due_payment')->nullable();
            $table->dateTime('order_cancelled')->nullable();
            $table->dateTime('order_for_pickup')->nullable();
            $table->dateTime('order_for_shipping')->nullable();
            $table->dateTime('order_shipped')->nullable();
            $table->dateTime('order_completed')->nullable();
            $table->tinyInteger('order_restocked')->default(0);
            $table->dateTime('order_warranty')->nullable();
            $table->string('order_remarks')->nullable();
            $table->integer('viewed')->default(0);
            $table->integer('status_update')->default(0);
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
