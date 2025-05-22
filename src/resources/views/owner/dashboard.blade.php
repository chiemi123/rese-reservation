@extends('layouts.owner')

@section('css')
<link rel="stylesheet" href="{{ asset('css/owner/dashboard.css') }}">
@endsection

@section('title', 'オーナーダッシュボード')

@section('content')
<div class="dashboard-container">

    <h1 class="dashboard-title">ようこそ、{{ $user->name }} さん！</h1>

    <h2 class="dashboard-section-title">ダッシュボード概要</h2>
    <ul class="dashboard-links">
        <li><a href="{{ route('owner.shops.create') }}">店舗情報を登録</a></li>
        <li><a href="{{ route('owner.reservations.index') }}">予約一覧を見る</a></li>
    </ul>

    {{-- 店舗一覧（詳細カード表示） --}}
    <h3 class="dashboard-section-title">登録済みの店舗</h3>

    @if ($shops->isEmpty())
    <p>まだ店舗が登録されていません。</p>
    @else

    @php
    $disk = config('filesystems.default');
    @endphp

    <div class="shop-grid">
        @foreach ($shops as $shop)
        <div class="shop-card">
            {{-- 画像 --}}
            @if (!$shop->image)
            <div class="shop-image-placeholder">No Image</div>
            {{-- 外部URL --}}
            @elseif (\Illuminate\Support\Str::startsWith($shop->image, ['http://', 'https://']))
            <img src="{{ $shop->image }}" alt="{{ $shop->name }}" class="shop-image">
            {{-- S3の場合 --}}
            @elseif ($disk === 's3')
            <img src="{{ \Illuminate\Support\Facades\Storage::disk('s3')->url($shop->image) }}" alt="{{ $shop->name }}" class="shop-image">
            {{-- ローカルの場合 --}}
            @else
            <img src="{{ asset('storage/' . $shop->image) }}" alt="{{ $shop->name }}" class="shop-image">
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

</div>
@endsection