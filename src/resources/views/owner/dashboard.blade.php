@extends('layouts.owner')

@section('css')
<link rel="stylesheet" href="{{ asset('css/owner/dashboard.css') }}">
@endsection

@section('title', 'オーナーダッシュボード')

@section('content')
<div class="dashboard-container">

    <h2 class="dashboard-title">ようこそ、{{ $user->name }} さん！</h2>

    <h3 class="dashboard-section-title">ダッシュボード概要</h3>
    <ul class="dashboard-links">
        <li><a href="{{ route('owner.shops.create') }}">店舗情報を登録</a></li>
        <li><a href="{{ route('owner.reservations.index') }}">予約一覧を見る</a></li>
    </ul>

    {{-- 店舗一覧（詳細カード表示） --}}
    <h3 class="dashboard-section-title">登録済みの店舗</h3>

    @if ($shops->isEmpty())
    <p>まだ店舗が登録されていません。</p>
    @else
    <div class="shop-grid">
        @foreach ($shops as $shop)
        <div class="shop-card">
            {{-- 画像 --}}
            @if ($shop->image)
            @if (Str::startsWith($shop->image, ['http://', 'https://']))
            {{-- 外部URLの場合 --}}
            <img src="{{ $shop->image }}" alt="{{ $shop->name }}" class="shop-image">
            @else
            {{-- ローカルのstorageに保存されている場合 --}}
            <img src="{{ asset('storage/' . $shop->image) }}" alt="{{ $shop->name }}" class="shop-image">
            @endif
            @else
            <div class="shop-image-placeholder">No Image</div>
            @endif

            {{-- 店舗情報 --}}
            <div class="shop-info">
                <h4 class="shop-name">{{ $shop->name }}</h4>
                <p class="shop-detail">ジャンル：{{ $shop->genre->name }}</p>
                <p class="shop-detail">エリア：{{ $shop->area->name }}</p>
            </div>

            {{-- 編集ボタン --}}
            <a href="{{ route('owner.shops.edit', ['shop' => $shop->id]) }}" class="edit-button">
                編集する
            </a>
        </div>
        @endforeach
    </div>

    {{-- ページネーション --}}
    <div class="pagination-wrapper">
        {{ $shops->links() }}
    </div>
    @endif

    <h3 class="dashboard-section-title">最新の予約（直近5件）</h3>

    @if ($reservations->isEmpty())
    <p>現在、予約はありません。</p>
    @else
    <table class="dashboard-table">
        <thead>
            <tr>
                <th>お客様名</th>
                <th>予約日時</th>
                <th>ステータス</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reservations as $reservation)
            <tr>
                <td>{{ $reservation->user->name ?? '不明なユーザー' }}</td>
                <td>{{ \Carbon\Carbon::parse($reservation->reserved_at)->format('Y年m月d日 H:i') }}</td>
                <td>
                    @if($reservation->status === 'canceled')
                    <span class="status-canceled">キャンセル</span>
                    @elseif($reservation->status === 'reserved')
                    <span class="status-reserved">予約済み</span>
                    @else
                    <span>未定</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <a href="{{ route('owner.reservations.index') }}" class="dashboard-footer-link">すべての予約を見る →</a>
    @endif

</div>
@endsection