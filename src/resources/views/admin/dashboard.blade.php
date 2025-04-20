@extends('layouts.admin')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/dashboard.css') }}">
@endsection


@section('content')
<div class="dashboard">
    <h1 class="dashboard__title">管理者ダッシュボード</h1>

    <ul class="dashboard__stats-list">
        <li class="dashboard__stat-item">
            <span class="dashboard__label">本日の予約数：</span>
            <span class="dashboard__value">{{ $todayReservations }} 件</span>
        </li>
        <li class="dashboard__stat-item">
            <span class="dashboard__label">登録オーナー数：</span>
            <span class="dashboard__value">{{ $ownerCount }} 人</span>
        </li>
        <li class="dashboard__stat-item">
            <span class="dashboard__label">登録店舗数：</span>
            <span class="dashboard__value">{{ $shopCount }} 店舗</span>
        </li>
    </ul>

    <h2 class="dashboard__subtitle">新規登録ユーザー（最近）</h2>
    <ul class="dashboard__user-list">
        @foreach ($newUsers as $user)
        <li class="dashboard__user-item">
            {{ $user->name }}（{{ $user->created_at->format('Y/m/d') }}）
        </li>
        @endforeach
    </ul>
</div>
@endsection