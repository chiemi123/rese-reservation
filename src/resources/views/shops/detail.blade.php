@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/shops/detail.css') }}">
@endsection

@section('content')
<div class="shop">
    <div class="shop__detail">
        <div class="shop__header">
            <a href="{{ route('shops.index') }}" class="back-button">＜</a>
            <h2 class="shop__name">{{ $shop->name }}</h2>
        </div>
        <img src="{{ $shop->image }}" alt="{{ $shop->name }}" class="shop__image">

        <p class="shop__tags">#{{ $shop->area->name }} #{{ $shop->genre->name }}</p>
        <p class="shop__description">{{ $shop->description }}</p>
    </div>

    <div class="reservation-card">
        <h2 class="reservation-card__title">予約</h2>
        <form action="{{ route('reservations.store') }}" method="POST" class="reservation-card__form">
            @csrf
            <input type="hidden" name="shop_id" value="{{ $shop->id }}">

            {{-- 日付 --}}
            <input id="date" type="date" name="date" class="reservation-card__input" required>

            {{-- 時間 --}}
            <div class="select-wrapper">
                <select id="time" name="time" class="reservation-card__input" required>
                    <option value="" disabled selected>時間を選択</option>
                    @for ($hour = 10; $hour <= 22; $hour++) {{-- 10時〜22時まで --}}
                        @for ($minute=0; $minute < 60; $minute +=10) {{-- 10分刻み --}}
                        @php
                        $formattedTime=sprintf('%02d:%02d', $hour, $minute);
                        @endphp
                        <option value="{{ $formattedTime }}">{{ $formattedTime }}</option>
                        @endfor
                        @endfor
                </select>
                <span class="search-bar__arrow">⮟</span>
            </div>

            {{-- 人数 --}}
            <div class="select-wrapper">
                <select id="number" name="number" class="reservation-card__input" required>
                    <option value="" disabled selected>人数を選択</option>
                    @for($i = 1; $i <= 10; $i++)
                        <option value="{{ $i }}">{{ $i }}人</option>
                        @endfor
                </select>
                <span class="search-bar__arrow">⮟</span>
            </div>

            {{-- 選択内容表示（動的に表示されるようにJS対応） --}}
            <div class="reservation-card__summary">
                <div class="summary-row">
                    <span class="summary-label">Shop</span>
                    <span class="summary-value">{{ $shop->name }}</span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">Date</span>
                    <span class="summary-value" id="selected-date">-</span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">Time</span>
                    <span class="summary-value" id="selected-time">-</span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">Number</span>
                    <span class="summary-value" id="selected-number">-</span>
                </div>
            </div>

            {{-- ボタン --}}
            <button type="submit" class="reservation-card__button">予約する</button>
        </form>
    </div>
</div>

<script>
    const dateInput = document.getElementById('date');
    const timeInput = document.getElementById('time');
    const numberInput = document.getElementById('number');

    const selectedDate = document.getElementById('selected-date');
    const selectedTime = document.getElementById('selected-time');
    const selectedNumber = document.getElementById('selected-number');

    dateInput.addEventListener('change', () => {
        selectedDate.textContent = dateInput.value;
    });

    timeInput.addEventListener('change', () => {
        selectedTime.textContent = timeInput.value;
    });

    numberInput.addEventListener('change', () => {
        selectedNumber.textContent = numberInput.options[numberInput.selectedIndex].text;
    });
</script>

@endsection