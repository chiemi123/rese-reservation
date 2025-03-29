@extends('layouts.app') {{-- レイアウトを継承 --}}

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

    <form method="POST" action="{{ route('verification.send') }}" style="display: inline;">
        @csrf
        <button type="submit" class="btn btn--primary">確認メールを再送信</button>
    </form>

    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
        @csrf
        <button type="submit" class="btn btn--link">ログアウト</button>
    </form>
</div>
@endsection