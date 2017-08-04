<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStrategiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('strategies', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('integration_id')->index();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('type'); // target rank, top 3 average, matche buybox
            $table->integer('rank')->nullable();
            $table->string('action')->nullable(); // match, below, above
            $table->double('amount')->nullable();
            $table->string('amountType')->nullable(); // currency, percentage
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
        Schema::dropIfExists('strategies');
    }
}
