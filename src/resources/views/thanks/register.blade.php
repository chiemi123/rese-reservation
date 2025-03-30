@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/thanks/register.css') }}">
@endsection

@section('title', '会員登録完了')

@section('content')
<div class="thanks">
    <div class="thanks__card">
        <p class="thanks__message">会員登録ありがとうございます</p>
        <div class="thanks__button-wrapper">
            <div class="thanks__button-wrapper">
                @guest
                <a href="/login" class="thanks__login-button">ログインする</a>
                @else
                <a href="/" class="thanks__login-button">トップページへ</a>
                @endguest
            </div>
        </div>
    </div>
</div>
@endsection