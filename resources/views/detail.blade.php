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
            <p>カテゴリー：
            @if($restaurant->categories->isEmpty())
                未分類
            @else
                {{ $restaurant->categories->pluck('name')->implode(', ') }}
            @endif
            </p>
            <p>レビュー： {{ $restaurant->review }}</p>
            <p>画像：<br>
            @if($restaurant->food_picture)
                    @if(filter_var($restaurant->food_picture, FILTER_VALIDATE_URL))
                        <!-- 外部のURLの場合 -->
                        <img src="{{ $restaurant->food_picture }}" alt="Food Picture" style="width: 150px; height: auto;">
                    @else
                        <!-- ローカルのパスの場合 -->
                        <img src="{{ asset('images/' . $restaurant->food_picture) }}" alt="Food Picture" style="width: 150px; height: auto;">
                    @endif
                @endif
            </p>
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