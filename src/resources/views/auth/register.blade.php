@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/register.css') }}">
@endsection

@section('content')
<div class="register-form__content">
    <div class="register-form__heading">
        <h2>Registrotion</h2>
    </div>

    @if ($errors->any())
    <div class="form__errors">
        <ul>
            @foreach ($errors->all() as $error)
            <li style="color:red;">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form class="form" method="POST" action="{{ route('register') }}">
        @csrf

        <div class="form__group">
            <span class="input-icon"><i class="material-icons">person</i></span>
            <input type="text" name="name" value="{{ old('name') }}" placeholder="Username" required>
        </div>

        <div class="input-group">
            <span class="input-icon"><i class="material-icons">email</i></span>
            <input type="email" name="email" value="{{ old('email') }}" placeholder="Email" required>
        </div>

        <div class="input-group">
            <span class="input-icon"><i class="material-icons">lock</i></span>
            <input type="password" name="password" placeholder="Password" required>
        </div>

        <div class="input-group">
            <span class="input-icon"><i class="material-icons">lock</i></span>
            <input type="password" name="password_confirmation" placeholder="Confirm Password" required>
        </div>

        <div class="form__button">
            <button class="form__button-submit" type="submit">登録</button>
        </div>
    </form>
</div>
@endsection