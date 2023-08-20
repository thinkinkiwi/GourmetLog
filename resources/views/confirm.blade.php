@extends('layouts.app')
@section('content')

<!-- コンテンツ大枠(ここから) -->
<div class="wrapper">
    @include('sidebar')

    <!-- メインコンテンツ（ここから） -->
    <div class="mainContents">

        <!-- メインヘッダ（ここから） -->
        <div class="mainHeader">
        </div>
        <!-- メインヘッダ（ここまで） -->

        <!-- メインボディ（ここから） -->
        <!-- 確認画面：フォーム（ここから） -->
        <form action="{{ route('finalize', ['shop_id' => $shop_id ?? null]) }}" method="post" enctype="multipart/form-data">
        @csrf

        <!-- 確認画面（ここから） -->
        <p>店名: {{ $restaurant['name'] }}</p>
        <p>フリガナ: {{ $restaurant['name_katakana'] }}</p>
        <p>カテゴリー:
            @if(!empty($selected_categories))
                @foreach($selected_categories as $categoryId)
                    {{ $categories->where('id', $categoryId)->first()->name }}
                @endforeach
            @else
                未分類
            @endif
        </p>
        <p>レビュー: {{ $restaurant['review'] }}</p>
        <p>電話番号: {{ $restaurant['phone_number'] }}</p>
        <p>コメント: {{ $restaurant['comment'] }}</p>
        <p>
            @if(isset($restaurant['food_picture']))
                <!-- <img src="{{ asset('/upload/' . $restaurant['food_picture']) }}" alt="Food Picture"> -->
                <img src="{{ asset('images/' . $restaurant['food_picture']->getClientOriginalName()) }}" alt="Food Picture">
            @endif
        </p>
        <p>Google Map URL: <a href="{{ $restaurant['map_url'] }}" target="_blank">{{ $restaurant['map_url'] }}</a></p>
        <!-- 確認画面（ここまで） -->

        <!-- 隠しフィールド（ここから） -->
        <input type="hidden" name="name" value="{{ $restaurant['name'] }}">
        <input type="hidden" name="name_katakana" value="{{ $restaurant['name_katakana'] }}">
        <input type="hidden" name="review" value="{{ $restaurant['review'] }}">
        <input type="hidden" name="uploaded_image" value="{{ $uploaded_image }}">
        <input type="hidden" name="map_url" value="{{ $restaurant['map_url'] }}">
        <input type="hidden" name="phone_number" value="{{ $restaurant['phone_number'] }}">
        <input type="hidden" name="comment" value="{{ $restaurant['comment'] }}">
        @foreach($restaurant['categories'] as $categoryId)
            <input type="hidden" name="categories[]" value="{{ $categoryId }}">
        @endforeach

        @if($shop_id)
        <input type="hidden" name="shop_id" value="{{ $shop_id }}">
        @endif
        <!-- 隠しフィールド（ここまで） -->

        <!-- 修正・登録ボタン（ここから） -->
        <button type="submit" name="back" value="back" class="btn btn-outline-dark">修正する</button>
        <button type="submit" class="btn btn-primary">登録する</button>
        <!-- 修正・登録ボタン（ここまで） -->

        <!-- 選択したカテゴリーIDの隠しフィールド（ここから） -->
        <input type="hidden" name="selected_categories" value="{{ json_encode($selected_categories) }}">
        <!-- 選択したカテゴリーIDの隠しフィールド（ここまで） -->

        </form>
        <!-- 確認画面：フォーム（ここまで） -->
        <!-- メインボディ（ここまで） -->

    </div>
    <!-- メインコンテンツ（ここまで） -->

</div>
<!-- コンテンツ大枠(ここまで) -->
@endsection