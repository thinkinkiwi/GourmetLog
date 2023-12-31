<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Restaurant extends Model
{
    use HasFactory, SoftDeletes;

    // usersテーブルとのリレーション
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // category_tagsテーブルを中間テーブルとしたcategoriesテーブルとのリレーション
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_tags');
    }
}
