<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePriceLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('price_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('integration_id')->index();
            $table->unsignedInteger('marketPlace_item_id')->index();
            $table->double('previousPrice');
            $table->integer('previousRank')->nullable();
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
        Schema::dropIfExists('price_logs');
    }
}
