<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Restaurant;
use App\Models\Users;
use App\Models\Category;
use App\Models\CategoryTags;
use Validator;
use Auth;
use Illuminate\Support\Facades\Http;

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

        return view('list', [
            'restaurants' => $restaurants
        ]);
    }
    // 検索機能：searchメソッド（ここまで）

    // お店詳細表示：detailメソッド（ここから）
    public function detail($shop_id)
    {
    $user_id = Auth::id();
    $restaurant = Restaurant::with('categories')
                        ->where('user_id', $user_id)
                        ->find($shop_id);

    // 短縮URLを展開
    $expandedUrl = $this->expandShortUrl($restaurant->map_url);
    // dd($expandedUrl);
    
    // 緯度経度を取得
    $coordinates = $this->getCoordinatesFromMapUrl($expandedUrl);
    // dd($coordinates);

    return view('detail', [
        'restaurant' => $restaurant, 
        'coordinates' => $coordinates
    ]);
    }
    // お店詳細表示：detailメソッド（ここまで）

    public function expandUrl(Request $request)
    {
        $shortUrl = $request->input('url');
        $response = Http::head($shortUrl);
        return response()->json(['expanded_url' => $response->headers()['Location'][0]]);
    }

    private function getCoordinatesFromMapUrl($mapUrl)
    {
        $regex = '/@(-?\d+\.\d+),(-?\d+\.\d+),\d+z/';
        $match = preg_match($regex, $mapUrl, $results);  // preg_matchを使用
        if ($match) {
            return ['lat' => $results[1], 'lng' => $results[2]];
        }
        return null;
    }

    // お店登録の画面表示：editメソッド（ここから）
    public function edit($shop_id = null)
    {
    $restaurant = $shop_id ? Restaurant::find($shop_id) : new Restaurant;
    $categories = Category::all();

    $selected_categories = old('categories') ?? [];
    if($shop_id && empty($selected_categories)) {
        $selected_categories = $restaurant->categories->pluck('id')->toArray();
    }

    // セッションから選択されたカテゴリーIDを取得
    $selected_categories = session('selected_categories', []);

    // dd($selected_categories);

    return view('edit', [
        'restaurant' => $restaurant, 
        'categories' => $categories,
        'selected_categories' => $selected_categories,
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
            'food_picture' => 'nullable',
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

        $selected_categories = $request->input('categories', []);
        
        // カテゴリーが選択されていない場合、空の配列を設定
        $restaurant['categories'] = $request->input('categories', []);

        // カテゴリーがJSON文字列として送信されている場合、配列に変換
        if (is_string($restaurant['categories'])) {
            $restaurant['categories'] = json_decode($restaurant['categories'], true);
        }

        // カテゴリーIDのみを取り出す
        $restaurant['categories'] = array_column($restaurant['categories'], 'id');

        // 「修正」ボタン押下時にカテゴリーデータを保持するため、セッションにカテゴリーIDを保存
        session(['selected_categories' => $selected_categories]);

        // if ($request->hasFile('food_picture')) {
        $file = $request->file('food_picture');
        if( !empty($file)){
            $filename = $file->getClientOriginalName();
            $move = $file->move(public_path('images'), $filename);
            if (!$move) {
                dd('ファイルの移動に失敗しました。', $file->getError());
            }
        }else{
            $filename = "noimage.png";
        }

        // 登録処理（ここから）
        $restaurant = $shop_id ? Restaurant::find($shop_id) : new Restaurant;
        $restaurant->user_id = Auth::id();
        $restaurant->name = $request->input('name');
        $restaurant->name_katakana = $request->input('name_katakana');
        if ($shop_id) {
            $restaurant->categories()->sync($selected_categories);
        }
        $restaurant->review = $request->input('review');
        $restaurant['food_picture'] = $filename;
        $restaurant->map_url = $request->input('map_url');
        $restaurant->phone_number = $request->input('phone_number');
        $restaurant->comment = $request->input('comment');
        // 登録処理（ここまで）

        $restaurant->save();

        // confirmにデータを保持したまま遷移
        return view('confirm', [
            'restaurant' => $restaurant,
            'categories' => $categories,
            'selected_categories' => $selected_categories,
            'uploaded_image' => $filename
        ]);

    }
    // 登録：storeメソッド（ここまで）
    
    // 確認画面の表示：confirmメソッド（ここから）
    public function confirm(Request $request, $shop_id = null)
    {

        // バリデーション（ここから）
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:20',
            'name_katakana' => 'required|regex:/^[ァ-ヶー\s]+$/u',
            'categories' => 'nullable|array',
            'review' => 'required|integer|min:1|max:5',
            'food_picture' => 'nullable',
            'map_url' => 'nullable|url',
            'phone_number' => 'nullable|digits_between:10,15',
            'comment' => 'required|string|max:300',
        ]);
        // バリデーション（ここまで）

        // バリデーションエラー時（ここから）
        if ($validator->fails()) {
            // dd($validator->errors()->get('food_picture'));
            return redirect()->route('edit', ['shop_id' => $shop_id])
                ->withInput()
                ->withErrors($validator);
        }
        // バリデーションエラー時（ここまで）

        // dd($request->all());

        // 入力された情報と渡されたshop_idを取得する
        $restaurant = $request->all();
        $categories = Category::all();
        $shop_id = $request->input('shop_id');

        $selected_categories = $request->input('categories', []);
        
        // カテゴリーが選択されていない場合、空の配列を設定
        $restaurant['categories'] = $request->input('categories', []);

        // dd(is_string($restaurant['categories']), $restaurant['categories']); // ステップ1
        // $decoded_categories = json_decode($restaurant['categories'], true);
        // $category_ids = array_column($decoded_categories, 'id');
        // dd($decoded_categories); // ステップ2
        // dd($category_ids); // ステップ3
    
        // カテゴリーがJSON文字列として送信されている場合、配列に変換
        if (is_string($restaurant['categories'])) {
            $restaurant['categories'] = json_decode($restaurant['categories'], true);
        }

        // カテゴリーIDのみを取り出す
        $restaurant['categories'] = array_column($restaurant['categories'], 'id');

        // 「修正」ボタン押下時にカテゴリーデータを保持するため、セッションにカテゴリーIDを保存
        session(['selected_categories' => $selected_categories]);

        // dd($restaurant['categories']);

        $file = $request->file('food_picture');
        if( !empty($file)){
            $filename = $file->getClientOriginalName();
            $move = $file->move(public_path('images'), $filename);
            session(['uploaded_image_name' => $filename]);
            if (!$move) {
                dd('ファイルの移動に失敗しました。', $file->getError());
            } else {
            }
        }else{
            $filename = "noimage.png";
        }

        // dd($restaurant);

        // 確認画面にデータを保持したまま遷移
        return view('confirm', [
            'restaurant' => $restaurant,
            'shop_id' => $shop_id,
            'categories' => $categories,
            'selected_categories' => $selected_categories,
            'uploaded_image' => $filename
        ]);

    }
    // 確認画面の表示：confirmメソッド（ここまで）

    // 確認画面の処理：finalizeメソッド（ここから）
    public function finalize(Request $request, $shop_id = null)
    {
        // confirmからshop_idを受け取る
        $shop_id = $request->input('shop_id');

        // カテゴリーIDをセッションから取得
        $selected_categories = session('selected_categories', []);

        // dd($shop_id);

        // shop_idの有無で新規 | 既存を判別、新規ならインスタンス生成
        $restaurant = $shop_id ? Restaurant::find($shop_id) : new Restaurant;
        $restaurant->user_id = Auth::id();
        $restaurant->name = $request->input('name');
        $restaurant->name_katakana = $request->input('name_katakana');
        // if ($shop_id) {
        // $restaurant->categories()->sync($selected_categories);
        // }
        $restaurant->review = $request->input('review');
        $filename = session('uploaded_image_name', 'noimage.png');
        // dd($filename);
        $restaurant->food_picture = $filename;
        $restaurant->map_url = $request->input('map_url');
        $restaurant->phone_number = $request->input('phone_number');
        $restaurant->comment = $request->input('comment');

        // 「修正する」ボタン押下時にデータを保持したまま編集画面（/edit/{$shop_id}）に戻す
        if($request->input('back') == 'back'){
            $selected_categories = json_decode($request->input('selected_categories'), true);
            return redirect()->route('edit', ['shop_id' => $shop_id])
                        ->with('selected_categories', $selected_categories)
                        ->withInput();
        }

        // dd($restaurant);
        
        // データ更新（保存）
        $restaurant->save();

        // カテゴリーの同期をデータセーブの後にすることでエラーを回避
        $restaurant->categories()->sync($selected_categories);

        // dd($restaurant);

        // 登録処理が完了したらセッションから選択したカテゴリーの情報を削除
        $request->session()->forget('selected_categories');

        $request->session()->forget('uploaded_image_name');

        // dd($restaurant);

        // listに戻す
        return redirect('list');
    }
    // 確認画面の処理：finalizeメソッド（ここまで）

    // 削除：destroyメソッド（ここから）
    public function destroy($shop_id){
        $restaurant = Restaurant::where('id', $shop_id)->where('user_id', Auth::user()->id)->first();
    
        if($restaurant) {
            $restaurant->delete();
        return response()->json(['success' => true]);
        }
        return response()->json(['success' => false]);
    
    }
    // 削除：destroyメソッド（ここまで）
}
