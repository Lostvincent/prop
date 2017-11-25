<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRelationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('relations', function (Blueprint $table) {
            $table->enum('referer', ['bgm', 'mal'])->default('bgm');
            $table->integer('subject_id')->unsigned()->index();
            $table->integer('character_id')->unsigned()->index()->nullable();
            $table->integer('prop_id')->unsigned()->index();

            $table->unique(['subject_id', 'character_id', 'prop_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('relations');
    }
}
