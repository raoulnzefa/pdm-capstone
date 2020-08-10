<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCancelOrderRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cancel_order_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('customer_id');
            $table->string('order_number');
            $table->unsignedInteger('reason_id');
            $table->text('comment');
            $table->string('status');
            $table->dateTime('date_request');
            $table->decimal('total',9,2);
            $table->integer('viewed')->default(0);
            $table->integer('status_update')->default(0);
            $table->timestamps();
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->foreign('order_number')->references('number')->on('orders')->onDelete('cascade');
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
        Schema::dropIfExists('cancel_order_requests');
    }
}
