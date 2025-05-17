@extends('layouts.admin')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/owners-create.css') }}">
@endsection


@section('content')
<div class="owner-form">
    <h1 class="owner-form__title">店舗代表者登録</h1>

    <form action="{{ route('admin.owners.store') }}" method="POST" class="owner-form__form">
        @csrf

        <div class="owner-form__group">
            <label for="name" class="owner-form__label">名前</label>
            <input type="text" name="name" id="name" class="owner-form__input" value="{{ old('name') }}">
        </div>

        <div class="owner-form__group">
            <label for="email" class="owner-form__label">メールアドレス</label>
            <input type="email" name="email" id="email" class="owner-form__input" value="{{ old('email') }}">
        </div>

        <div class="owner-form__group">
            <label for="password" class="owner-form__label">パスワード</label>
            <input type="password" name="password" id="password" class="owner-form__input">
        </div>

        <div class="owner-form__group">
            <label for="password_confirmation" class="owner-form__label">パスワード（確認）</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="owner-form__input">
        </div>

        <div class="owner-form__submit-wrapper">
            <button type="submit" class="owner-form__submit">登録</button>
        </div>
    </form>

    <div class="owner-form__back">
        <a href="{{ route('admin.owners.index') }}" class="owner-form__back-button">戻る</a>
    </div>

</div>
@endsection