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
            <div class="header__icon" id="menuToggle">
                <div class="drawer__open">
                    <span></span>
                </div>

                <!-- メニュー表示内容 -->
                <nav class="nav__content" id="sideMenu">
                    <ul class="nav__list">
                        <li class="nav__item">
                            <a class="nav__link" href="{{ route('owner.dashboard') }}">Owner Dashboard</a>
                        </li>
                        <li class="nav__item">
                            <a class="nav__link" href="{{ route('owner.shops.create') }}">Register Store Info</a>
                        </li>
                        @auth
                        <li class="nav__item">
                            <form method="POST" action="{{ route('owner.logout') }}">
                                @csrf
                                <button type="submit" class="nav__button">Logout</button>
                            </form>
                        </li>
                        @endauth
                    </ul>
                </nav>
            </div>

            <!-- ロゴ -->
            <div class="header__logo">Rese Owner</div>
        </div>

        @yield('header')
    </header>

    <main>
        {{-- 共通メッセージ表示エリア --}}
        @if (!isset($suppressFlashMessages) || !$suppressFlashMessages)
        <div class="flash-message-wrapper">
            {{-- 手動でフラッシュした成功メッセージ --}}
            @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif

            {{-- 一般的な成功メッセージ用（"message"キー対応） --}}
            @if (session('message'))
            <div class="alert alert-success">
                {{ session('message') }}
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
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>
        @endif

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
                if (e.target.classList.contains('nav__link')) {
                    sideMenu.classList.remove('active');
                }
            });
        }
    </script>

</body>

</html>