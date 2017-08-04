<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('integration_id')->unsigned()->index();
            $table->string('siteOrderId')->unique();
            $table->string('status'); // awaiting payment, awaiting shipment, shipped, cancelled
            $table->double('totalPrice')->nullable();
            $table->double('totalTax')->nullable();
            $table->double('totalShipping')->nullable();
            $table->double('totalGift')->nullable();
            $table->timestamp('purchaseDate');
            $table->string('shippingService')->nullable();
            $table->string('buyerName')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
