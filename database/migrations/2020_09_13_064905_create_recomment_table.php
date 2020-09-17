<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecommentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recomment', function (Blueprint $table) {
          $table->bigIncrements('rc_num');
          $table->string('recomment');
          $table->boolean('re_activation');
          $table->timestamps();
        });
        Schema::table('recomment', function (Blueprint $table) {
          $table->unsignedBigInteger('post_num');
          $table->foreign('post_num')->references('post_num')->on('post');
          $table->unsignedBigInteger('c_num');
          $table->foreign('c_num')->references('c_num')->on('comment');
          $table->string('rc_writer');
          $table->foreign('rc_writer')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recomment');
    }
}
