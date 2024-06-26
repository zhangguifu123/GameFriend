<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('post_id')->index()->comment('征友贴id');
            $table->string('toId')->index()->comment('文章作者id');
            $table->string('fromId')->index()->comment('评论者id');
            $table->boolean("status")->comment("0未查看 1已查看")->default(0);
            $table->string("content")->comment("留言内容");
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
        Schema::dropIfExists('comments');
    }
}
