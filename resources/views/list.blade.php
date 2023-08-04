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
            <h4>お店リスト</h4>

            <!-- 検索窓・ボタン設置場所 -->
            <div>

            </div>

            <!-- お店リスト（ここから） -->
            <table class="table table-bordered table-striped">
            <tr>
                <th>ID</th>
                <th>店名</th>
                <th>レビュー</th>
                <th>コメント</th>
                <th>詳細</th>
                <th>編集</th>
                <th>削除</th>
            </tr>
            @foreach ($restaurants as $restaurant)
            <tr>
                <!-- コメント部分は10文字で…表記になるように制限付与 -->
                <td>{{ $restaurant->id }}</td>
                <td>{{ $restaurant->name }}</td>
                <td>{{ $restaurant->review }}</td>
                <td>{{ \Illuminate\Support\Str::limit($restaurant->comment, 10) }}</td>
                <td><a href="#" class="btn btn-success">詳細</a></td>
                <td><a href="#" class="btn btn-primary">編集</a></td>
                <td><a href="#" class="btn btn-danger">削除</a></td>
            </tr>
            @endforeach
        </table>
        <!-- お店リスト（ここまで） -->
            
        </div>
        <!-- メインボディ（ここまで） -->
    
    </div>
    <!-- メインコンテンツ（ここまで） -->

</div>
<!-- コンテンツ大枠(ここまで) -->
@endsection