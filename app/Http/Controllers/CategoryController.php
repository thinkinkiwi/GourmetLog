<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Users;
use App\Models\Category;
use Validator;
use Auth;

class CategoryController extends Controller
{
    //
    // カテゴリー一覧表示
    public function indexCategories()
    {
        $categories = Category::all();
        return view('category', compact('categories'));
    }

    // カテゴリー登録処理
    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
        ]);

        $category = new Category();
        $category->name = $request->name;
        $category->save();

        return redirect('/category');
    }

    // カテゴリー編集のためのカテゴリーidの取得
    public function getCategory($category_id)
    {
        $category = Category::find($category_id);
        return response()->json($category);
    }

    // カテゴリー更新処理
    public function updateCategory(Request $request, $category_id)
    {
        $request->validate([
            'name' => 'required|max:255',
        ]);

        $category = Category::find($category_id);
        $category->name = $request->name;
        $category->save();

        return redirect('/category');
    }

    // カテゴリー削除処理
    public function destroyCategory($category_id)
    {
        $category = Category::find($category_id);
        $category->delete();

        return redirect('/category');
    }
}
