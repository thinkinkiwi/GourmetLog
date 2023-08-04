<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('restaurants', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');               // ユーザーID
            $table->string('name')->nullable();          // 店名
            $table->string('name_katakana')->nullable(); // フリガナ
            $table->integer('review')->nullable();       // レビュー
            $table->string('comment')->nullable();       // コメント
            $table->string('food_picture')->nullable();  // 料理画像
            $table->string('map_url')->nullable();       // Google map URL
            $table->timestamps();                        // 
            $table->softDeletes();                       // 削除日時：NULL許容のdelete
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurants');
    }
};
