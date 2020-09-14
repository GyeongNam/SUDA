<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post', function (Blueprint $table) {
            $table->bigIncrements('post_num');
            $table->string('Kategorie');
            $table->string('Title');
            $table->string('Text');
            $table->string('image')->nulluble();
            $table->integer('like')->nulluble();
            $table->boolean('post_activation');
            $table->timestamps();
        });
        Schema::table('post', function (Blueprint $table) {
          $table->string('writer');
          $table->foreign('writer')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('post');
    }
}
