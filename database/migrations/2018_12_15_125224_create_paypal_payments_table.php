<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaypalPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paypal_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('order_number');
            $table->string('transaction_id');
            $table->string('payment_method');
            $table->string('payer_status');
            $table->string('payer_email');
            $table->decimal('subtotal',10,2);
            $table->decimal('shipping',10,2);
            $table->decimal('total',10,2);
            $table->string('payer_state');
            $table->string('created');
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
        Schema::dropIfExists('paypal_payments');
    }
}
