<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// ダッシュボード表示
Route::get('/dashboard', function () {
    return view('dashboard');
});

// 一覧表示
Route::get('/list', [App\Http\Controllers\ShopController::class, 'index']);

// お店詳細ページ表示
Route::get('/detail/{shop_id}', [App\Http\Controllers\ShopController::class, 'detail']);

// お店の新規登録・編集ページ表示
Route::get('/edit/{shop_id?}', [App\Http\Controllers\ShopController::class, 'edit'])->name('edit');

// お店の新規登録処理
Route::match(['post', 'put'], '/store/{shop_id?}', [App\Http\Controllers\ShopController::class, 'store'])->name('store');

// お店の更新処理
Route::put('/store/{shop_id}', [App\Http\Controllers\ShopController::class, 'store'])->name('store.update');

// 更新処理の確認画面
Route::post('/confirm', [App\Http\Controllers\ShopController::class, 'confirm'])->name('confirm');

// 確認画面後の登録処理
// Route::put('/finalize/{shop_id?}', [App\Http\Controllers\ShopController::class, 'finalize'])->name('finalize');
Route::post('/finalize/{shop_id?}', [App\Http\Controllers\ShopController::class, 'finalize'])->name('finalize');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
