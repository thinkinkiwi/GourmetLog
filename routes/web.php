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
})->name('dashboard');

// 一覧表示
Route::get('/list', [App\Http\Controllers\ShopController::class, 'index']);

// 検索機能
Route::get('/list', [App\Http\Controllers\ShopController::class, 'search']);

// お店詳細ページ表示
Route::get('/detail/{shop_id}', [App\Http\Controllers\ShopController::class, 'detail']);

// お店の新規登録・編集ページ表示
Route::get('/edit/{shop_id?}', [App\Http\Controllers\ShopController::class, 'edit'])->name('edit');

// お店の新規登録処理
Route::match(['post', 'put'], '/store/{shop_id?}', [App\Http\Controllers\ShopController::class, 'store'])->name('store');

// お店の更新処理
Route::put('/store/{shop_id}', [App\Http\Controllers\ShopController::class, 'store'])->name('store.update');

// 更新処理の確認画面
Route::post('/confirm/{shop_id?}', [App\Http\Controllers\ShopController::class, 'confirm'])->name('confirm');

// 確認画面後の登録処理
Route::post('/finalize/{shop_id?}', [App\Http\Controllers\ShopController::class, 'finalize'])->name('finalize');

// 削除機能
Route::delete('/delete/{shop_id}', [App\Http\Controllers\ShopController::class, 'destroy']);

// カテゴリー一覧表示
Route::get('/category', [App\Http\Controllers\CategoryController::class, 'indexCategories']);

// カテゴリー登録処理
Route::post('/category/store', [App\Http\Controllers\CategoryController::class, 'storeCategory']);

// カテゴリー編集のためのカテゴリーidの取得
Route::get('/category/get/{category_id}', [App\Http\Controllers\CategoryController::class, 'getCategory']);

// カテゴリー更新処理
Route::put('/category/update/{category_id}', [App\Http\Controllers\CategoryController::class, 'updateCategory']);

// カテゴリー削除処理
Route::delete('/category/delete/{category_id}', [App\Http\Controllers\CategoryController::class, 'destroyCategory']);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
