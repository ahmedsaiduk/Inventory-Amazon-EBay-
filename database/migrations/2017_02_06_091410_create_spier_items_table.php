<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpierItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spier_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index();
            $table->integer('store_category_id')->unsigned()->index()->nullable();
            $table->string('sku')->nullable();
            $table->string('upc')->nullable();
            $table->string('ean')->nullable(); 
            $table->string('title'); 
            $table->text('description')->nullable(); 
            $table->string('condition')->nullable();
            $table->string('conditionID')->nullable(); 
            $table->integer('quantityAvailable');
            $table->integer('quantitySold')->nullable(); 
            $table->string('conditionDescription')->nullable(); 
            $table->double('cost')->nullable();
            $table->double('mapPrice')->nullable();
            $table->double('retailPrice')->nullable();  
            $table->integer('postalCode')->nullable();
            $table->string('country')->nullable();
            $table->string('shippingType')->nullable();
            $table->string('shippingService')->nullable();
            $table->string('status')->nullable();
            $table->boolean('synced')->nullable();
            $table->unique(['user_id','sku']);
            $table->timestamps(); 

            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('spier_items');
    }
}
