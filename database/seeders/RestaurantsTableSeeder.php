<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RestaurantsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $faker = \Faker\Factory::create(); // Fakerインスタンスを作成

        // 10個のランダムなデータを生成
        foreach (range(1, 10) as $index) {
            DB::table('restaurants')->insert([
                'user_id' => 1,
                'name' => $faker->name,
                'name_katakana' => $faker->name,
                'review' => $faker->randomDigit,
                'phone_number' => $faker->phoneNumber,
                'comment' => $faker->text,
                'food_picture' => $faker->imageUrl(),
                'map_url' => $faker->url,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}