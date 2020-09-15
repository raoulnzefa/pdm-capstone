<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReplacementRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('replacement_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('customer_id');
            $table->string('order_number');
            $table->string('inventory_number');
            $table->string('product_number');
            $table->string('product_name');
            $table->integer('quantity');
            $table->text('reason');
            $table->string('status');
            $table->dateTime('request_created');
            $table->dateTime('request_approved')->nullable();
            $table->dateTime('request_declined')->nullable();
            $table->dateTime('request_replaced')->nullable();
            $table->integer('viewed')->default(0);
            $table->integer('status_update')->default(0);
            $table->timestamps();
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->foreign('order_number')->references('number')->on('orders');
            $table->foreign('inventory_number')->references('number')->on('inventories');
            $table->foreign('product_number')->references('number')->on('products');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('replacement_requests');
    }
}
