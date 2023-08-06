<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // category＿tagsテーブルとのリレーション
    public function categoryTags()
    {
        return $this->hasMany(CategoryTag::class);
    }
}
