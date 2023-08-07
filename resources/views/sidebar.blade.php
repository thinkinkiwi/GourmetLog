<!-- サイドバー（ここから） -->
<div class="sidebar">

    <!-- サイドバータイトル表示（ここから） -->
    <h2><a href="{{ url('/dashboard') }}">Gourmet Log</a></h2>
    <!-- サイドバータイトル表示（ここまで） -->

    <br>

    <!-- MENU表示（ここから） -->
    <hr>
        <p>MENU</p>
    <hr>
    <!-- MENU表示（ここまで） -->

    <!-- MENUコンテンツ表示（ここから） -->
    <ul>
        <li><a href="{{ url('/list') }}">お店リスト</a></li>
        <li><a href="{{ route('edit') }}">お店登録/編集</a></li>
        <li><a href="{{ url('/category') }}">カテゴリー管理</a></li>
    </ul>
    <!-- MENUコンテンツ表示（ここまで） -->

    <!-- ユーザー名表示（ここから） -->
    <div class="user-name">
        <a href="#" id="navbarDropdown" class="user-dropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
            {{ Auth::user()->name }}
        </a>
        <!-- ログアウト表示（ここから） -->
        <div class="logout-text">
            <a class="dropdown-item" href="{{ route('logout') }}"
                onclick="event.preventDefault();
                document.getElementById('logout-form').submit();">
                {{ __('Logout') }}
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
            </form>
        </div>
        <!-- ログアウト表示（ここまで） -->
    </div>
    <!-- ユーザー名表示（ここまで） -->

</div>
<!-- サイドバー（ここまで） -->