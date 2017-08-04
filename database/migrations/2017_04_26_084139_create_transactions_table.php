<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('marketPlace_item_id')->unsigned()->index();
            $table->integer('order_id')->unsigned()->index();
            $table->string('transactionID')->unique()->nullable();
            $table->string('trackingNumber')->nullable();
            $table->integer('quantity');
            $table->double('price');
            $table->double('shippingCost')->nullable();
            $table->double('tax')->nullable();
            $table->double('gift')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
