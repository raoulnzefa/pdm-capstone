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
            $table->string('inventory_number');
            $table->string('product_name');
            $table->decimal('price',10,2);
            $table->integer('quantity');
            $table->decimal('total',10,2);
            $table->date('date_invoice');
            $table->timestamps();
            $table->foreign('invoice_number')->references('number')->on('invoices')->onDelete('cascade');
            $table->foreign('inventory_number')->references('number')->on('inventories');
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
