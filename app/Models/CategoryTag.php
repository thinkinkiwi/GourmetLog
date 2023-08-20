<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoryTag extends Model
{
    use HasFactory, SoftDeletes;

    // restaurantsテーブルとのリレーション
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    // categoriesテーブルとのリレーション
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
