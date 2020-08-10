<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBankDepositPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_deposit_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('bank_account_id');
            $table->string('order_number');
            $table->timestamps();
            $table->foreign('bank_account_id')->references('id')->on('bank_accounts');
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
        Schema::dropIfExists('bank_deposit_payments');
    }
}
