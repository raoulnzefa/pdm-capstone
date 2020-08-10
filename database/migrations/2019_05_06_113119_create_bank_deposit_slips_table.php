<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBankDepositSlipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_deposit_slips', function (Blueprint $table) {
            $table->increments('id');
            $table->string('order_number');
            $table->unsignedInteger('customer_id');
            $table->unsignedInteger('bank_deposit_payment_id');
            $table->string('image');
            $table->integer('is_confirmed')->default(0);
            $table->dateTime('date_confirmed')->nullable();
            $table->timestamps();
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->foreign('order_number')->references('number')->on('orders');
            $table->foreign('bank_deposit_payment_id')->references('id')->on('bank_deposit_payments');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bank_deposit_slips');
    }
}
