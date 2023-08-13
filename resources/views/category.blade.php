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
            <h4>カテゴリー管理</h4>

            <!-- カテゴリー登録（ここから） -->
            <div>
            <form action="{{ url('category/store') }}" 
              method="post">
              @csrf
                カテゴリー名:<br>
                <input type="text" name="name">
                <button type="submit" class="btn btn-primary">
                    登録
                </button>
            </form>
            </div>
            <!-- カテゴリー登録（ここまで） -->

            <!-- カテゴリーリスト（ここから） -->
            <table class="table table-bordered table-striped">
            <tr>
                <th>ID</th>
                <th>カテゴリー</th>
                <th>編集</th>
                <th>削除</th>
            </tr>
            @foreach ($categories as $category)
            <tr>
                <td>{{ $category->id }}</td>
                <td>{{ $category->name }}</td>
                <td><a href="{{ url('edit/' . $category->id) }}" class="btn btn-primary">編集</a></td>
                <td><button class="btn btn-danger" onclick="categoryDelete({{ $category->id }})">削除</button></td>
            </tr>
            @endforeach
        </table>
        <!-- カテゴリーリスト（ここまで） -->
            
        <div id="editCategoryModal" class="modal fade" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editCategoryModalLabel">No <span id="category-id"></span> カテゴリー編集</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <label>カテゴリー名:</label>
        <input type="text" id="category-name" class="form-control" />
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">戻る</button>
        <button type="button" class="btn btn-primary" id="save-category">修正（保存）</button>
      </div>
    </div>
  </div>
</div>

        </div>
        <!-- メインボディ（ここまで） -->
    
    </div>
    <!-- メインコンテンツ（ここまで） -->

</div>
<!-- コンテンツ大枠(ここまで) -->
@endsection

<!-- 削除確認ウィンドウを表示するJS（ここから） -->
<script>
    function categoryDelete(category_id) {
        // 確認ウィンドウを表示
        if (confirm('本当に削除してもよろしいですか？')) {
            // OK→削除実行（destroy）
            $.ajax({
                url: `{{ url('delete') }}/${category_id}`,
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

