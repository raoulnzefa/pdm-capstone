<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->string('number')->unique();
            $table->string('order_number');
            $table->string('first_name');
            $table->string('last_name');
            $table->decimal('subtotal',10,2);
            $table->decimal('discount',10,2)->nullable();
            $table->decimal('shipping_fee',10,2)->nullable();
            $table->decimal('total',10,2);
            $table->string('status');
            $table->dateTime('created');
            $table->date('payment_date')->nullable();
            $table->date('warranty')->nullable();
            $table->timestamps();
            $table->foreign('order_number')->references('number')->on('orders');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
