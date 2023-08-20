<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    // category＿tagsテーブルを中間テーブルとしたrestaurantsテーブルとのリレーション
    public function restaurants()
    {
        return $this->belongsToMany(Restaurant::class, 'category_tags');
    }
}
