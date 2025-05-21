@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/user/qrcode.css') }}">
@endsection

@section('content')
<div class="qr-container">
    <h1 class="qr-page-title">予約内容の確認</h1>

    <div class="reservation-box">
        <p><strong>店舗名：</strong>{{ $reservation->shop->name }}</p>
        <p><strong>日付：</strong>{{ $reservation->reserved_at->translatedFormat('Y年m月d日') }}</p>
        <p><strong>時間：</strong>{{ $reservation->reserved_at->translatedFormat('H時i分') }}</p>
        <p><strong>人数：</strong>{{ $reservation->number_of_people }}人</p>
    </div>

    <div class="qr-box">
        <p><strong>QRコード：</strong></p>
        <div class="qr-image">
            {!! $qr !!}
            <noscript>
                <p>QRコードを表示するにはJavaScriptを有効にしてください。</p>
            </noscript>
        </div>
        <p class="qr-note">※この画面を来店時にスタッフにお見せください</p>
    </div>

    <div class="back-button-area">
        <a href="{{ route('user.mypage') }}" class="qr-btn">マイページに戻る</a>
    </div>
</div>
@endsection