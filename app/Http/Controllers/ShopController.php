<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Restaurant;
use App\Models\Users;
use Auth;

class ShopController extends Controller
{

    // ユーザーごとに情報を区別するためのログイン認証コンストラクタ（ここから）
    public function __construct()
    {
        $this->middleware('auth');
    }
    // ユーザーごとに情報を区別するためのログイン認証コンストラクタ（ここまで）

    // 一覧表示（ここから）
    public function index()
    {
        $user_id = Auth::id();
        $restaurants = Restaurant::where('user_id', $user_id)->get();
        return view('list', ['restaurants' => $restaurants]);
    }
    // 一覧表示（ここまで）

    // お店詳細表示(ここから)
    public function detail($shop_id)
    {
        $user_id = Auth::id();
        $restaurant = Restaurant::where('user_id', $user_id)->find($shop_id);
        return view('detail', ['restaurant' => $restaurant]);
    }
    // お店詳細表示(ここまで)

}
