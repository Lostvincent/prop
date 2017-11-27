<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('referer', ['bgm', 'mal'])->default('bgm');
            $table->integer('subject_id')->unsigned()->index();
            $table->integer('ep_id')->unsigned()->index();
            $table->integer('prop_id')->unsigned()->index();
            $table->tinyInteger('min')->unsigned();
            $table->tinyInteger('sec')->unsigned();
            $table->integer('length')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('locations');
    }
}
