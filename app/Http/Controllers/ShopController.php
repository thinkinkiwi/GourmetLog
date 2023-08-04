<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Restaurant;


class ShopController extends Controller
{
    //
    public function index()
{
    $restaurants = Restaurant::all(); // お店のリストを取得
    // dd($restaurants);
    return view('list', ['restaurants' => $restaurants]); // ビューにデータを渡す
}

}
