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
                <form action="{{ url('list') }}" method="GET">
                    <input type="text" name="search" placeholder="ここにキーワードを入力してください">
                    <button type="submit" class="btn btn-outline-dark">検索</button>
                </form>
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
                <td><a href="{{ url('detail/'.$restaurant->id) }}" class="btn btn-success">詳細</a></td>
                <td><a href="{{ url('edit/' . $restaurant->id) }}" class="btn btn-primary">編集</a></td>
                <td><button class="btn btn-danger" onclick="shopDelete({{ $restaurant->id }})">削除</button></td>
            </tr>
            @endforeach
        </table>
        <!-- お店リスト（ここまで） -->

        <!-- ページネーションのページ選択 -->
        {{ $restaurants->links('pagination::bootstrap-5') }}
            
        </div>
        <!-- メインボディ（ここまで） -->
    
    </div>
    <!-- メインコンテンツ（ここまで） -->

</div>
<!-- コンテンツ大枠(ここまで) -->
@endsection

<!-- 削除確認ウィンドウを表示するJS（ここから） -->
<script>
    function shopDelete(shop_id) {
        // 確認ウィンドウを表示
        if (confirm('本当に削除してもよろしいですか？')) {
            // OK→削除実行（destroy）
            $.ajax({
                url: `{{ url('delete') }}/${shop_id}`,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                success: function (data) {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('削除に失敗しました');
                    }
                },
                error: function () {
                    alert('エラーが発生しました');
                }
            });
        }
    }
</script>
<!-- 削除確認ウィンドウを表示するJS（ここまで） -->