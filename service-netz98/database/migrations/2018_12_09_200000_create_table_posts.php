<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablePosts extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->longText('post_title');
            $table->longText('post_link');
            $table->longText('post_description');
            $table->longText('post_content');
            $table->integer('post_comments_count')->unsigned();
            $table->integer('creators_id')->unsigned();
            $table->foreign('creators_id')->references('id')->on('creators');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down()
    {
        Schema::drop('posts');
    }
}
