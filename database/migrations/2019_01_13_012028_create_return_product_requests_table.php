<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReturnProductRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('return_product_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('return_request_id');
            $table->unsignedInteger('order_product_id');
            $table->decimal('price',9,2);
            $table->integer('quantity');
            $table->integer('ordered_qty');
            $table->decimal('amount',9,2);
            $table->string('refund_selected')->default('no');
            $table->integer('return_qty')->default(0);
            $table->timestamps();
            $table->foreign('return_request_id')->references('id')->on('return_requests')->onDelete('cascade');
            $table->foreign('order_product_id')->references('id')->on('order_products');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('return_product_requests');
    }
}
