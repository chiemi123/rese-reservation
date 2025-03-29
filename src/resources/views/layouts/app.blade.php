<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Rese</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    @yield('css')
</head>

<body>
    <header>
        <div class="header__left">
            <!-- ハンバーガーメニュー -->
            <div class="header__icon">
                <input id="drawer__input" class="drawer__hidden" type="checkbox">
                <label for="drawer__input" class="drawer__open"><span></span></label>

                <!-- メニュー表示内容 -->
                <nav class="nav__content">
                    <ul class="nav__list">
                        <li class="nav__item">
                            <a class="nav__item-link" href="/">Home</a>
                        </li>

                        @auth
                        <!-- ログアウトは POST + CSRF -->
                        <li class="nav__item">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="nav__item-link logout-btn">Logout</button>
                            </form>
                        </li>
                        <li class="nav__item">
                            <a class="nav__item-link" href="/mypage">Mypage</a>
                        </li>
                        @else
                        <li class="nav__item">
                            <a class="nav__item-link" href="/register">Registration</a>
                        </li>
                        <li class="nav__item">
                            <a class="nav__item-link" href="/login">Login</a>
                        </li>
                        @endauth
                    </ul>
                </nav>
            </div>

            <!-- ロゴ -->
            <div class="header__logo">Rese</div>
        </div>

        @yield('header')
    </header>

    <main>
        @yield('content')
    </main>
</body>

</html>