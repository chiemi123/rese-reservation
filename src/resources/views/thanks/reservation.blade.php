@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/thanks/reservation.css') }}">
@endsection

@section('title', '予約完了')

@section('content')
<div class="reservation-done">
    <div class="reservation-done__card">
        <p class="reservation-done__message">ご予約ありがとうございます</p>
        <a href="/mypage" class="reservation-done__button">戻る</a>
    </div>
</div>
@endsection