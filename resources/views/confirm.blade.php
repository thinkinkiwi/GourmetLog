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
        <form action="{{ route('finalize', $restaurant->id ?? null) }}" method="post">
        @csrf
        @if(isset($data['shop_id']))
            @method('PUT')
        @endif

        <!-- 確認画面（ここから） -->
        <p>店名: {{ $data['name'] }}</p>
        <p>フリガナ: {{ $data['name_katakana'] }}</p>
        <p>カテゴリー:
            @foreach($data['categories'] as $categoryId)
                {{ $categories->where('id', $categoryId)->first()->name }} 
            @endforeach
        </p>
        <p>レビュー: {{ $data['review'] }}</p>
        <p>電話番号: {{ $data['phone_number'] }}</p>
        <p>コメント: {{ $data['comment'] }}</p>
        @if(isset($data['food_picture']))
        <img src="{{ asset('storage/' . $data['food_picture']) }}" alt="Food Picture">
        @endif
        <p>Google Map URL: <a href="{{ $data['map_url'] }}" target="_blank">{{ $data['map_url'] }}</a></p>
        <!-- 確認画面（ここまで） -->

        <!-- 隠しフィールド（ここから） -->
        <input type="hidden" name="name" value="{{ $data['name'] }}">
        <input type="hidden" name="name_katakana" value="{{ $data['name_katakana'] }}">
        <input type="hidden" name="review" value="{{ $data['review'] }}">
        @if(isset($data['food_picture']))
        <input type="hidden" name="food_picture" value="{{ $data['food_picture'] }}">
        @endif
        <input type="hidden" name="map_url" value="{{ $data['map_url'] }}">
        <input type="hidden" name="phone_number" value="{{ $data['phone_number'] }}">
        <input type="hidden" name="comment" value="{{ $data['comment'] }}">
        @foreach($data['categories'] as $categoryId)
            <input type="hidden" name="categories[]" value="{{ $categoryId }}">
        @endforeach
        <!-- 隠しフィールド（ここまで） -->

        <!-- 修正・登録ボタン（ここから） -->
        <p><a href="#" class="btn btn-outline-dark">修正する</a></p>
        <button type="submit" class="btn btn-primary">登録する</button>
        <!-- 修正・登録ボタン（ここまで） -->

        </form>
        <!-- 確認画面：フォーム（ここまで） -->
        <!-- メインボディ（ここまで） -->

    </div>
    <!-- メインコンテンツ（ここまで） -->

</div>
<!-- コンテンツ大枠(ここまで) -->
@endsection