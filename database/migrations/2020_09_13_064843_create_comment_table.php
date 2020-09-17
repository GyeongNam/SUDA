<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comment', function (Blueprint $table) {
          $table->bigIncrements('c_num');
          $table->string('comment');
          $table->boolean('c_activation');
          $table->timestamps();
        });
        Schema::table('comment', function (Blueprint $table) {
          $table->unsignedBigInteger('post_num');
          $table->foreign('post_num')->references('post_num')->on('post');
          $table->string('c_writer');
          $table->foreign('c_writer')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comment');
    }
}
