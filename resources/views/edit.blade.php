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
        <!-- 登録/編集フォーム（ここから） -->
        <form action="{{ route('confirm', ['shop_id' => $shop_id]) }}" method="post" enctype="multipart/form-data">
        @csrf
        @if(isset($restaurant) && $restaurant->id)
            <input type="hidden" name="shop_id" value="{{ $restaurant->id }}">
        @endif

            <!-- 登録/編集画面（ここから） -->
            <div>
                <label for="name">店名:</label>
                <input type="text"
                       name="name"
                       id="name" 
                       value="{{ old('name', $restaurant->name ?? '') }}"
                       required>
                @error('name')
                    <div>{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label for="name_katakana">フリガナ:</label>
                <input type="text" 
                       name="name_katakana"
                       id="name_katakana"
                       value="{{ old('name_katakana', $restaurant->name_katakana ?? '') }}"
                       required>
                @error('name_katakana')
                    <div>{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label for="category">カテゴリー:</label>
                @foreach ($categories as $category)
                <input type="checkbox" 
                    name="categories[]" 
                    value="{{ $category->id }}" {{ in_array($category->id, old('categories', $restaurant->categories->pluck('id')->toArray() ?? [])) ? 'checked' : '' }}> {{ $category->name }}
                @endforeach
                @error('categories')
                    <div>{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label for="review">レビュー:</label>
                <select name="review" id="review" required>
                    @foreach(range(1, 5) as $review)
                        <option value="{{ $review }}" {{ old('review', $restaurant->review) == $review ? 'selected' : '' }}>{{ $review }}</option>
                    @endforeach
                </select>
                @error('review')
                    <div>{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label for="food_picture">画像:</label>
                <input type="file" 
                       name="food_picture"
                       id="food_picture"
                       accept="image/*">
                @error('food_picture')
                    <div>{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label for="map_url">Google Map URL:</label>
                <input type="url" 
                       name="map_url"
                       id="map_url"
                       value="{{ old('map_url', $restaurant->map_url ?? '') }}">
                @error('map_url')
                    <div>{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label for="phone_number">電話番号:</label>
                <input type="number" 
                       name="phone_number"
                       id="phone_number"
                       value="{{ old('phone_number', $restaurant->phone_number ?? '') }}">
                @error('phone_number')
                    <div>{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label for="comment">コメント:</label>
                <textarea name="comment" 
                          id="comment" 
                          required>
                          {{ old('comment', $restaurant->comment ?? '') }}
                </textarea>
                @error('comment')
                    <div>{{ $message }}</div>
                @enderror
            </div>

            <input type="hidden" name="shop_id" value="{{ $shop_id }}">
            <button type="submit" class="btn btn-outline-dark">確認画面へ</button>
            <!-- 登録/編集画面（ここまで） -->

        </form>
        <!-- 登録/編集フォーム（ここまで） -->
        <!-- メインボディ（ここまで） -->
        
    </div>
    <!-- メインコンテンツ（ここまで） -->

</div>
<!-- コンテンツ大枠(ここまで) -->
@endsection