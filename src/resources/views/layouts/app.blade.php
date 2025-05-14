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
    <script src="https://cdn.tailwindcss.com"></script>
    @yield('css')
</head>

<body>
    <header>
        <div class="header__left">
            <!-- ハンバーガーメニュー -->
            <div class="header__icon" id="menuToggle">
                <div class="drawer__open">
                    <span></span>
                </div>

                <!-- メニュー表示内容 -->
                <nav class="nav__content" id="sideMenu">
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
        {{-- 共通メッセージ表示エリア --}}
        <div class="flash-message-wrapper">
            {{-- 手動でフラッシュした成功メッセージ --}}
            @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif

            {{-- 手動でフラッシュしたエラーメッセージ --}}
            @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
            @endif

            {{-- バリデーションエラー一覧 --}}
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>

        @yield('content')
    </main>

    <script>
        const menuToggle = document.getElementById('menuToggle');
        const sideMenu = document.getElementById('sideMenu');

        if (window.innerWidth < 768) {
            menuToggle.addEventListener('click', () => {
                sideMenu.classList.toggle('active');
            });

            sideMenu.addEventListener('click', (e) => {
                if (e.target.classList.contains('nav__item-link')) {
                    sideMenu.classList.remove('active');
                }
            });
        }
    </script>

</body>

</html>