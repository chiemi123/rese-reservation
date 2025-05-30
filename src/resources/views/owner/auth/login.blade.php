@extends('layouts.owner')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/login.css') }}">
@endsection

@section('title', '店舗代表者ログイン')

@section('content')

@if ($errors->any())
<p class="login__error">{{ $errors->first() }}</p>
@endif

<div class="login-form__content">
    <div class="login-form__heading">
        <h2>ownerlogin</h2>
    </div>

    <form class="form" method="POST" action="{{ route('owner.login') }}">
        @csrf

        <div class="form__group">
            <span class="input-icon"><i class="material-icons">email</i></span>
            <input type="email" name="email" placeholder="Email" required autofocus>
        </div>

        <div class="input-group">
            <span class="input-icon"><i class="material-icons">lock</i></span>
            <input type="password" name="password" placeholder="Password" required>
        </div>

        <div class="form__button">
            <button class="form__button-submit" type="submit">ログイン</button>
        </div>
    </form>
</div>
@endsection