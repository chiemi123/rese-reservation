<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Rese Owner</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <script src="https://cdn.tailwindcss.com"></script>
    @yield('css')
</head>

<body>
    <header>
        <div class="header__left">
            <!-- ハンバーガーメニュー -->
            <div class="header__icon">
                <input id="drawer__input" class="drawer__hidden" type="checkbox">
                <label for="drawer__input" class="drawer__open"><span></span></label>

                <!-- 管理者メニュー -->
                <nav class="nav__content">
                    <ul class="nav__list">
                        <li class="nav__item">
                            <a class="nav__item-link" href="{{ route('owner.dashboard') }}">Owner Dashboard</a>
                        </li>
                        <li class="nav__item">
                            <a class="nav__item-link" href="{{ route('owner.shops.create') }}">店舗情報を登録</a>
                        </li>
                        @auth
                        <li class="nav__item">
                            <form method="POST" action="{{ route('admin.logout') }}">
                                @csrf
                                <button type="submit" class="nav__item-link logout-btn">Logout</button>
                            </form>
                        </li>
                        @endauth
                    </ul>
                </nav>
            </div>

            <!-- ロゴ -->
            <div class="header__logo">Rese <small style="font-size: 0.7em;">Owner</small></div>

            <!-- フラッシュメッセージ表示 -->
            @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif

            @if (session('error'))
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>

        @yield('header')
    </header>

    <main>
        @yield('content')
    </main>
</body>

</html>