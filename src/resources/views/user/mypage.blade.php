@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/user/mypage.css') }}">
@endsection

@section('content')
<div class="container">

    <!-- ユーザー名 -->
    <h2 class="page-title">{{ Auth::user()->name }}さん</h2>
    @if (session('message'))
    <div class="alert alert-success">
        ✅ {{ session('message') }}
    </div>
    @endif

    @if (request('status') === 'success')
    <div class="alert alert-success">
        ✅ お支払いが完了しました。ありがとうございました！
    </div>
    @elseif (request('status') === 'cancel')
    <div class="alert alert-danger">
        ❌ 決済がキャンセルされました。もう一度お試しください。
    </div>
    @endif

    {{-- 横並び用のラッパー --}}
    <div class="mypage-layout">
        <!-- 予約状況 -->
        <section class="section reservation-section">
            <h3 class="section-title">予約状況</h3>

            @forelse ($reservations as $reservation)
            <div class="reservation-card">
                <div class="reservation-header">
                    <span class="reservation-title">予約{{ $loop->iteration }}</span>
                    <form method="POST" action="{{ route('reservations.cancel', $reservation->id) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="cancel-button-light" onclick="return confirm('この予約をキャンセルしてもよろしいですか？')">
                            ❌ キャンセルする
                        </button>
                    </form>
                </div>
                <ul class="reservation-info">
                    <li><strong>店舗:</strong> {{ $reservation->shop->name }}</li>
                    <li><strong>日付:</strong> {{ $reservation->reserved_at->format('Y年m月d日') }}</li>
                    <li><strong>時間:</strong> {{ $reservation->reserved_at->format('H時i分') }}</li>
                    <li><strong>人数:</strong> {{ $reservation->number_of_people }}人</li>
                </ul>

                <a href="{{ route('reservations.qr', $reservation->id) }}" class="qr-link">▶ QRコードを表示</a>

                <div class="payment-button-wrapper">
                    @if ($reservation->payment && $reservation->payment->status === 'paid')
                    <p class="paid-label">✅ 支払い済み</p>
                    @else
                    <div class="payment-alert-box">
                        <p class="payment-alert-message">⚠ この予約はまだ決済が完了していません。</p>
                        <a href="{{ route('payment.checkout', $reservation->id) }}" class="payment-button">
                            💳 決済する
                        </a>
                    </div>
                    @endif
                </div>

                @if ($errors->any())
                <ul class="form-errors">
                    @foreach ($errors->all() as $error)
                    <li class="form-error">{{ $error }}</li>
                    @endforeach
                </ul>
                @endif
                {{-- ▼ レビュー表示トグルボタン --}}
                <button type="button" onclick="toggleForm('review-form-{{ $reservation->id }}')" class="toggle-button">
                    レビューを書く
                </button>

                {{-- ▼ レビュー投稿フォーム（最初は非表示） --}}
                <div id="review-form-{{ $reservation->id }}" class="review-form hidden">
                    <form action="{{ route('reviews.store') }}" method="POST" class="review-form">
                        @csrf
                        <input type="hidden" name="shop_id" value="{{ $reservation->shop->id }}">
                        <input type="hidden" name="reservation_id" value="{{ $reservation->id }}">

                        <div class="form-group">
                            <label for="rating-{{ $reservation->id }}">評価（1〜5）</label>
                            <select name="rating" id="rating-{{ $reservation->id }}" class="review-form__select" required>
                                <option value="" disabled selected>評価を選択</option>
                                @for ($i = 1; $i <= 5; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="comment-{{ $reservation->id }}">コメント</label>
                            <textarea name="comment" id="comment-{{ $reservation->id }}" class="review-form__textarea" required></textarea>
                        </div>

                        <button type="submit" class="review-form__submit">レビューを投稿する</button>
                    </form>
                </div>

                {{-- ▼ 予約変更トグルボタン --}}
                <button type="button" onclick="toggleForm('reservation-update-form-{{ $reservation->id }}')" class="toggle-button">
                    予約を変更する
                </button>

                {{-- ▼ 予約変更フォーム（最初は非表示） --}}
                <form method="POST" action="{{ route('reservations.update', $reservation->id) }}"
                    id="reservation-update-form-{{ $reservation->id }}"
                    class="reservation-update-form hidden">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="shop_id" value="{{ $reservation->shop->id }}">
                    <input type="hidden" name="reservation_id" value="{{ $reservation->id }}">

                    <div class="form-group">
                        <label for="date-{{ $reservation->id }}">日付</label>
                        <input id="date" type="date" name="date" class="reservation-card__input" required>
                    </div>

                    <div class="form-group select-wrapper">
                        <label for="time-{{ $reservation->id }}">時間</label>
                        <div class="select-container">
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
                    </div>

                    <div class="form-group select-wrapper">
                        <label for="number-{{ $reservation->id }}">人数</label>
                        <div class="select-container">
                            <select id="number" name="number" class="reservation-card__input" required>
                                <option value="" disabled selected>人数を選択</option>
                                @for($i = 1; $i <= 10; $i++)
                                    <option value="{{ $i }}">{{ $i }}人</option>
                                    @endfor
                            </select>
                            <span class="search-bar__arrow">⮟</span>
                        </div>
                    </div>

                    <button type="submit" class="update-button">予約を変更する</button>
                </form>
            </div>
            @empty
            <p class="empty-message">予約がありません。</p>
            @endforelse
        </section>

        <!-- お気に入り店舗 -->
        <section class="section favorite-section">
            <h3 class="section-title">お気に入り店舗</h3>
            <div class="favorites-grid">
                @forelse ($favoriteShops as $shop)
                <div class="shop-card">
                    <img src="{{ $shop->image ?? '/images/default.jpg' }}" alt="{{ $shop->name }}" class="shop-image">

                    <div class="shop-details">
                        <h4 class="shop-name">{{ $shop->name }}</h4>
                        <p class="shop-tags">#{{ $shop->area->name }} #{{ $shop->genre->name }}</p>

                        <div class="shop-actions">
                            <a href="{{ route('shops.show', $shop->id) }}" class="details-button">詳しくみる</a>

                            @if (in_array($shop->id, $favorites))
                            <form method="POST" action="{{ route('unfavorite', $shop) }}" class="favorite-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="favorite-button favorite-button--active" title="お気に入り解除">
                                    <span class="material-icons favorite-icon">favorite</span>
                                </button>
                            </form>
                            @else
                            <form method="POST" action="{{ route('favorite', $shop) }}" class="favorite-form">
                                @csrf
                                <button type="submit" class="favorite-button" title="お気に入り追加">
                                    <span class="material-icons favorite-icon">favorite_border</span>
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <p class="empty-message">お気に入り店舗はありません。</p>
                @endforelse
            </div>
        </section>

        <section class="section review-section">
            <h3 class="section-title">あなたのレビュー</h3>

            @if ($reviews->isEmpty())
            <p class="empty-message">まだレビューを投稿していません。</p>
            @else
            <div class="review-list">
                @foreach ($reviews as $review)
                <div class="review-card">
                    <p><strong>{{ $review->shop->name }}</strong> へのレビュー</p>
                    <p>評価：{{ $review->rating }}/5</p>
                    <p>{{ $review->comment }}</p>
                </div>
                @endforeach
            </div>
            @endif
        </section>
    </div>
</div>

<script>
    function toggleForm(id) {
        const target = document.getElementById(id);
        target.classList.toggle('hidden');
    }
</script>

@endsection