<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRepliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('replies', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('comment_id')->index()->unsigned()->comment('评论id');
            $table->string('fromId')->index()->comment('评论者id');
            $table->boolean("status")->comment("0未查看 1已查看");
            $table->string('toId')->comment('被评论者id');
            $table->string("content")->comment("回复内容");
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
        Schema::dropIfExists('replies');
    }
}
