@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
@endsection

@section('content')
<div class="admin-unpaid-container">
    <h2 class="admin-unpaid-title">未決済予約一覧</h2>

    @if ($unpaidReservations->isEmpty())
    <p class="admin-unpaid-message">現在、未決済の予約はありません。</p>
    @else
    <table class="admin-unpaid-table">
        <thead>
            <tr>
                <th>ユーザー</th>
                <th>店舗</th>
                <th>予約日時</th>
                <th>人数</th>
                <th>決済ステータス</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($unpaidReservations as $reservation)
            <tr>
                <td>{{ $reservation->user->name }}</td>
                <td>{{ $reservation->shop->name }}</td>
                <td>{{ $reservation->reserved_at->format('Y年m月d日 H:i') }}</td>
                <td>{{ $reservation->number_of_people }}人</td>
                <td>
                    @if ($reservation->payment)
                    <span class="status status-{{ $reservation->payment->status }}">
                        {{ $reservation->payment->status }}
                    </span>
                    @else
                    <span class="status status-unregistered">未登録</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>
@endsection