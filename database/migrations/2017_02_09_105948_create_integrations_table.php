<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIntegrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('integrations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index();
            $table->string('marketPlace');
            $table->string('site');
            $table->string('siteID');
            $table->string('sellerID')->nullable(); // remove nullable later
            $table->text('authToken');
            $table->string('currency');
            $table->text('packingSlipURL')->nullable();
            $table->string('country');
            $table->string('timezone')->nullable(); // 4:00 am to all zones
            $table->date('tokenEXP')->nullable();
            $table->boolean('qtySync')->default(true);
            $table->boolean('enabled')->default(true);
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
        Schema::dropIfExists('integrations');
    }
}
