@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/verify-email.css') }}">
@endsection

@section('content')
<div class="email-verification">
    <h2 class="email-verification__title">メールアドレスを確認してください</h2>

    <p class="email-verification__text">
        登録されたメールアドレス宛に確認メールを送信しました。<br>
        メール内のリンクをクリックして、認証を完了してください。
    </p>

    @if (session('status') == 'verification-link-sent')
    <div class="email-verification__message">
        新しい確認リンクをメールアドレスに送信しました！
    </div>
    @endif

    @if (session('message'))
    <div class="email-verification__message">
        {{ session('message') }}
    </div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="btn resend-verification-button">確認メールを再送信</button>
    </form>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn logout-button">ログアウト</button>
    </form>
</div>
@endsection