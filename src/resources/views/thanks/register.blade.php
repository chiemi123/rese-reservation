@extends('layouts.app')

@section('content')
    <div class="thanks">
        <div class="thanks__card">
            <p class="thanks__message">会員登録ありがとうございます</p>
            <div class="thanks__button-wrapper">
                <a href="/login" class="thanks__login-button">ログインする</a>
            </div>
        </div>
    </div>
@endsection