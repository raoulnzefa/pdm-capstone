<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoryVariantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_variants', function (Blueprint $table) {
            $table->increments('id');
            $table->string('inventory_number');
            $table->unsignedInteger('variant_id');
            $table->timestamps();
            $table->foreign('inventory_number')->references('number')->on('inventories');
            $table->foreign('variant_id')->references('id')->on('product_with_variants');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory_variants');
    }
}
