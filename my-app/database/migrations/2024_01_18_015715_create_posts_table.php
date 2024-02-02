<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
//スキーマファサードでcreateメソッドを使用している。

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void //戻り値がないことを示すためにある。
    {
        //1つ目はテーブルの名前、2つ目は新しいテーブル定義をするために使用するBluprintオブジェクトを受け取るクロージャ。
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('cat_id');
            $table->text('content');
            $table->unsignedInteger('comment_count')->default(0);//投稿に対するコメントの数を格納するための符号なしの整数型
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
        //ロールバック機能を搭載する。
    }
};
