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
            <p>{{ $restaurant->name_katakana }}</p>
            <p>カテゴリー：
            @if($restaurant->categories->isEmpty())
                その他
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
            <p>Google Map URL：</p>
            <div id="map" class="image-map-size"></div>
            </div>
            <p>電話番号：<br>
            {{ $restaurant->phone_number }}</p>
            <p>コメント：<br>
            {{ $restaurant->comment }}</p>
            <a href="{{ url('/list') }}" class="btn btn-outline-dark">お店リストに戻る</a>
        </div>
        <!-- メインボディ（ここまで） -->
        
    </div>
    <!-- メインコンテンツ（ここまで） -->

</div>
<!-- コンテンツ大枠(ここまで) -->
@endsection

<script>

function fetchExpandedUrl(shortUrl, callback) {
    fetch('/expand-url?url=' + encodeURIComponent(shortUrl))
        .then(response => response.json())
        .then(data => {
            callback(data.expanded_url);
        });
}

    function initMap() {
    @isset($coordinates)
        var uluru = {lat: parseFloat("{{ $coordinates['lat'] }}"), lng: parseFloat("{{ $coordinates['lng'] }}")};
        var map = new google.maps.Map(document.getElementById('map'), {zoom: 16, center: uluru});
        var marker = new google.maps.Marker({position: uluru, map: map});
    @endisset
}
</script>