<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMarketPlaceItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('market_place_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('integration_id')->index();
            $table->unsignedInteger('spier_item_id')->index();
            $table->unsignedInteger('strategy_id')->index()->nullable();
            $table->string('marketPlaceItemID')->nullable();
            $table->string('asin')->nullable();
            $table->string('upc')->nullable();
            $table->string('ean')->nullable();
            $table->string('sku')->nullable();
            $table->double('price');
            $table->integer('quantityAvailable');
            $table->string('imageUrl')->nullable();
            $table->double('priceMin')->nullable();
            $table->double('priceMax')->nullable();
            $table->integer('quantitySold')->nullable();
            $table->integer('currentRank')->nullable();
            $table->string('status')->nullable();
            $table->unique(['integration_id', 'marketPlaceItemID']);
            $table->boolean('revised')->default(true);
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
        Schema::dropIfExists('market_place_items');
    }
}
