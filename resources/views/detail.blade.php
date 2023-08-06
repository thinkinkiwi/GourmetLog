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
        <div class="mainBody">
            <h3>{{ $restaurant->name }} 詳細</h3>
            <p>フリガナ： {{ $restaurant->name_katakana }}</p>
            <p>カテゴリー： {{ $restaurant->category->name ?? '未分類' }}</p>
            <p>レビュー： {{ $restaurant->review }}</p>
            <p>画像：<br>
            <img src="{{ $restaurant->food_picture }}" alt="{{ $restaurant->name }}" class="image-map-size"></p>
            <div class="google-map">
                <p>地図：</p>
                <iframe src="{{ $restaurant->map_url }}" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0" class="image-map-size"></iframe>
            </div>
            <p>電話番号： {{ $restaurant->phone_number }}</p>
            <p>コメント： {{ $restaurant->comment }}</p>
            <a href="{{ url('/list') }}" class="btn btn-outline-dark">お店リストに戻る</a>
        </div>
        <!-- メインボディ（ここまで） -->
        
    </div>
    <!-- メインコンテンツ（ここまで） -->

</div>
<!-- コンテンツ大枠(ここまで) -->
@endsection