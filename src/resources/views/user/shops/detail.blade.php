@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/shops/detail.css') }}">
@endsection

@section('content')
<div class="shop">
    <div class="shop__detail">
        <div class="shop__header">
            <a href="{{ route('shops.index') }}" class="back-button">＜</a>
            <h1 class="shop__name">{{ $shop->name }}</h1>
        </div>

        @if (!$shop->image)
        <div class="shop-image-placeholder">No Image</div>
        @else
        @php
        $imageUrl = Str::startsWith($shop->image, 'http') ? $shop->image : asset($shop->image);
        @endphp
        <img src="{{ $imageUrl }}" alt="{{ $shop->name }}" class="shop__image">>
        @endif

        <p class="shop__tags">#{{ $shop->area->name }} #{{ $shop->genre->name }}</p>
        <p class="shop__description">{{ $shop->description }}</p>

        <div class="shop-reviews">
            <h2 class="shop-reviews__title">レビュー一覧</h2>

            @if ($shop->reviews->isEmpty())
            <p>まだレビューはありません。</p>
            @else
            @foreach ($shop->reviews as $review)
            <div class="review-card">
                <p><strong>{{ $review->user->name }}</strong> さん</p>
                <p>評価：{{ $review->rating }}/5</p>
                <p>{{ $review->comment }}</p>
            </div>
            @endforeach
            @endif
        </div>
    </div>

    <div class="reservation-card">
        <h2 class="reservation-card__title">予約</h2>
        <form action="{{ route('reservations.store') }}" method="POST" class="reservation-card__form">
            @csrf
            <input type="hidden" name="shop_id" value="{{ $shop->id }}">

            {{-- 日付 --}}
            <input id="date" type="date" name="date" class="reservation-card__input">

            {{-- 時間 --}}
            <div class="select-wrapper">
                <select id="time" name="time" class="reservation-card__input">
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
            </div>

            {{-- 人数 --}}
            <div class="select-wrapper">
                <select id="number" name="number" class="reservation-card__input">
                    <option value="" disabled selected>人数を選択</option>
                    @for($i = 1; $i <= 10; $i++)
                        <option value="{{ $i }}">{{ $i }}人</option>
                        @endfor
                </select>
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
            <div class="reservation-card__button-wrapper">
                <button type="submit" class="reservation-card__button">予約する</button>
            </div>
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