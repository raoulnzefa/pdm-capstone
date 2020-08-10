<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoiceProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('invoice_number');
            $table->string('product_number');
            $table->string('product_name');
            $table->decimal('price',10,2);
            $table->integer('quantity');
            $table->integer('return_qty')->nullable();
            $table->decimal('total',10,2);
            $table->string('invoicing_id');
            $table->string('invoicing_type');
            $table->date('date_invoice');
            $table->timestamps();
            $table->foreign('invoice_number')->references('number')->on('invoices')->onDelete('cascade');
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
        Schema::dropIfExists('invoice_products');
    }
}
