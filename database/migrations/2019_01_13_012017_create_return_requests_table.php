<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReturnRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('return_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('customer_id');
            $table->string('invoice_number');
            $table->string('order_number');
            $table->unsignedInteger('reason_id');
            $table->string('action');
            $table->string('comment');
            $table->string('status');
            $table->dateTime('date_return_request');
            $table->integer('viewed')->default(0);
            $table->decimal('subtotal',9,2);
            $table->decimal('discount',9,2)->nullable();
            $table->decimal('shipping_fee',9,2)->nullable();
            $table->decimal('total',9,2);
            $table->integer('status_update')->default(0);
            $table->timestamps();
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->foreign('invoice_number')->references('number')->on('invoices');
            $table->foreign('order_number')->references('number')->on('orders');
            $table->foreign('reason_id')->references('id')->on('reasons');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('return_requests');
    }
}
