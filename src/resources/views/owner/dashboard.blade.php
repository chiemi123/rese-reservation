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