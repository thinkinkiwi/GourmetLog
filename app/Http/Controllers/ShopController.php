<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Restaurant;
use App\Models\Users;
use App\Models\Category;
use Validator;
use Auth;

class ShopController extends Controller
{

    // ユーザーごとに情報を区別するためのログイン認証コンストラクタ（ここから）
    public function __construct()
    {
        $this->middleware('auth');
    }
    // ユーザーごとに情報を区別するためのログイン認証コンストラクタ（ここまで）

    // 一覧表示：indexメソッド（ここから）
    public function index()
    {
        $user_id = Auth::id();
        $restaurants = Restaurant::where('user_id', $user_id)
        ->orderBy('id')
        ->paginate(10);
        
        return view('list', ['restaurants' => $restaurants]);
    }
    // 一覧表示：indexメソッド（ここまで）

    // 検索機能：searchメソッド（ここから）
    public function search(Request $request)
    {
        $search = $request->input('search');

        if ($search) {
            $restaurants = Restaurant::where('name', 'LIKE', '%' . $search . '%')
                ->orWhere('comment', 'LIKE', '%' . $search . '%')
                ->orderBy('id')
                ->paginate(10);
        } else {
            $restaurants = Restaurant::orderBy('id')
            ->paginate(10);
        }

        return view('list', ['restaurants' => $restaurants]);
    }
    // 検索機能：searchメソッド（ここまで）

    // お店詳細表示：detailメソッド（ここから）
    public function detail($shop_id)
    {
        $user_id = Auth::id();
        $restaurant = Restaurant::where('user_id', $user_id)->find($shop_id);
        return view('detail', ['restaurant' => $restaurant]);
    }
    // お店詳細表示：detailメソッド（ここまで）

    // お店登録の画面表示：editメソッド（ここから）
    public function edit($shop_id = null)
    {
        $restaurant = $shop_id ? Restaurant::find($shop_id) : new Restaurant;
        $categories = Category::all();

        return view('edit', [
            'restaurant' => $restaurant, 
            'categories' => $categories,
            'shop_id' => $shop_id
        ]);
    }
    // お店登録の画面表示：editメソッド（ここまで）

    // 登録：storeメソッド（ここから）
    public function store(Request $request, $shop_id = null)
    {
        // バリデーション（ここから）
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:20',
            'name_katakana' => 'required|regex:/^[ァ-ヶー\s]+$/u',
            'categories' => 'nullable|array',
            'review' => 'required|integer|min:1|max:5',
            'food_picture' => 'nullable|image|max:5120',
            'map_url' => 'nullable|url',
            'phone_number' => 'nullable|digits_between:10,15',
            'comment' => 'required|string|max:300',
        ]);
        // バリデーション（ここまで）

        // バリデーションエラー時（ここから）
        if ($validator->fails()) {
            return redirect()->route('edit', ['shop_id' => $shop_id])
                ->withInput()
                ->withErrors($validator);
        }
        // バリデーションエラー時（ここまで）

        // 登録処理（ここから）
        $restaurant = $shop_id ? Restaurant::find($shop_id) : new Restaurant;
        $restaurant->user_id = Auth::id();
        $restaurant->name = $request->input('name');
        $restaurant->name_katakana = $request->input('name_katakana');
        $restaurant->categories()->sync($request->input('categories'));
        $restaurant->review = $request->input('review');
        if ($request->hasFile('food_picture')) {
            $file = $request->file('food_picture');
        
            // 画像の拡張子を取得
            $extension = $file->getClientOriginalExtension();
        
            // 保存するファイル名を生成（タイムスタンプと拡張子を組み合わせています）
            $filename = time() . '.' . $extension;
        
            // 画像を保存するパス（publicディレクトリ内のimagesフォルダ）
            $path = public_path('images');
        
            // 画像を保存
            $file->move($path, $filename);
        
            // データベースにファイルパスを保存
            $restaurant->food_picture = 'images/' . $filename;
        }
        $restaurant->map_url = $request->input('map_url');
        $restaurant->phone_number = $request->input('phone_number');
        $restaurant->comment = $request->input('comment');
        // 登録処理（ここまで）

        $restaurant->save();

        // confirmにデータを保持したまま遷移
        return view('confirm', [
            'restaurant' => $restaurant
        ]);

    }
    // 登録：storeメソッド（ここまで）
    
    // 確認画面の表示：confirmメソッド（ここから）
    public function confirm(Request $request, $shop_id = null)
    {
        // 入力された情報と渡されたshop_idを取得する
        $restaurant = $request->all();
        $shop_id = $request->input('shop_id');
        
        // カテゴリーが選択されていない場合、空の配列を設定
        $restaurant['categories'] = $request->input('categories', []);
        
        // バリデーション（ここから）
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:20',
            'name_katakana' => 'required|regex:/^[ァ-ヶー\s]+$/u',
            'categories' => 'nullable|array',
            'review' => 'required|integer|min:1|max:5',
            'food_picture' => 'nullable|image|max:5120',
            'map_url' => 'nullable|url',
            'phone_number' => 'nullable|digits_between:10,15',
            'comment' => 'required|string|max:300',
        ]);
        // バリデーション（ここまで）

        // バリデーションエラー時（ここから）
        if ($validator->fails()) {
            return redirect()->route('edit', ['shop_id' => $shop_id])
                ->withInput()
                ->withErrors($validator);
        }
        // バリデーションエラー時（ここまで）

        // confirmにデータを保持したまま遷移
        return view('confirm', [
            'restaurant' => $restaurant,
            'shop_id' => $shop_id
        ]);
    }
    // 確認画面の表示：confirmメソッド（ここから）

    // 確認画面の処理：finalizeメソッド（ここから）
    public function finalize(Request $request, $shop_id = null)
    {
        // confirmからshop_idを受け取る
        $shop_id = $request->input('shop_id');

        // dd($shop_id);

        // shop_idの有無で新規 | 既存を判別、新規ならインスタンス生成
        $restaurant = $shop_id ? Restaurant::find($shop_id) : new Restaurant;
        $restaurant->user_id = Auth::id();
        $restaurant->name = $request->input('name');
        $restaurant->name_katakana = $request->input('name_katakana');
        $restaurant->categories()->sync($request->input('categories'));
        $restaurant->review = $request->input('review');
        if ($request->hasFile('food_picture')) {
            $file = $request->file('food_picture');
        
            // 画像の拡張子を取得
            $extension = $file->getClientOriginalExtension();
        
            // 保存するファイル名を生成（タイムスタンプと拡張子を組み合わせています）
            $filename = time() . '.' . $extension;
        
            // 画像を保存するパス（publicディレクトリ内のimagesフォルダ）
            $path = public_path('images');
        
            // 画像を保存
            $file->move($path, $filename);
        
            // データベースにファイルパスを保存
            $restaurant->food_picture = 'images/' . $filename;
        }
        $restaurant->map_url = $request->input('map_url');
        $restaurant->phone_number = $request->input('phone_number');
        $restaurant->comment = $request->input('comment');

        // 「修正する」ボタン押下時にデータを保持したまま編集画面（/edit/{$shop_id}）に戻す
        if($request->input('back') == 'back'){
            return redirect()->route('edit', ['shop_id' => $shop_id])
                        ->withInput();
        }

        // データ更新（保存）
        $restaurant->save();

        // listに戻す
        return redirect('list');
    }
    // 確認画面の処理：finalizeメソッド（ここまで）
}
