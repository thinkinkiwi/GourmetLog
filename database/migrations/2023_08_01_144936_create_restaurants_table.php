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
            $table->bigInteger('user_id');   // ユーザーID
            $table->string('name');          // 店名
            $table->string('name_katakana'); // フリガナ
            $table->integer('review');       // レビュー
            $table->string('food_picture');  // 料理画像
            $table->string('map_url');       // Google map URL
            $table->timestamps();
            $table->softDeletes();           // 削除日時：NULL許容のdelete
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
