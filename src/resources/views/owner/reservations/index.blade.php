@extends('layouts.owner')

@section('css')
<link rel="stylesheet" href="{{ asset('css/owner/index.css') }}">
@endsection

@section('content')
<div class="owner-reservations">

    <div class="owner-reservations__header">
        <h1 class="owner-reservations__title">予約一覧</h1>

        <form method="GET" action="{{ route('owner.reservations.index') }}" class="owner-reservations__filter-form">
            <label for="shop_id" class="owner-reservations__filter-label">店舗を選択：</label>
            <select name="shop_id" id="shop_id" onchange="this.form.submit()" class="owner-reservations__filter-select">
                <option value="">全店舗</option>
                @foreach ($ownerShops as $shop)
                <option value="{{ $shop->id }}" {{ request('shop_id') == $shop->id ? 'selected' : '' }}>
                    {{ $shop->name }}
                </option>
                @endforeach
            </select>
        </form>
    </div>

    <!-- テーブル形式（PC） -->
    <div class="owner-reservations__table-wrapper">
        <table class="owner-reservations__table">
            <thead>
                <tr class="owner-reservations__row owner-reservations__row--header">
                    <th class="owner-reservations__header-cell">予約者名</th>
                    <th class="owner-reservations__header-cell">予約日時</th>
                    <th class="owner-reservations__header-cell">人数</th>
                    <th class="owner-reservations__header-cell">ステータス</th>
                </tr>
            </thead>
            <tbody class="owner-reservations__tbody">
                @forelse ($reservations as $shopName => $shopReservations)
                <tr class="owner-reservations__row owner-reservations__row--shop">
                    <td colspan="5" class="owner-reservations__cell owner-reservations__cell--shop">{{ $shopName }}</td>
                </tr>
                @foreach ($shopReservations as $reservation)
                <tr class="owner-reservations__row">
                    <td class="owner-reservations__cell">{{ $reservation->user->name }}</td>
                    <td class="owner-reservations__cell">{{ \Carbon\Carbon::parse($reservation->reserved_at)->format('Y年m月d日 H:i') }}</td>
                    <td class="owner-reservations__cell">{{ $reservation->number_of_people }}名</td>
                    <td class="owner-reservations__cell">{{ $reservation->status }}</td>
                </tr>
                @endforeach
                @empty
                <tr class="owner-reservations__row">
                    <td colspan="4" class="owner-reservations__cell owner-reservations__cell--empty">
                        該当する予約はありません。
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- カード形式（スマホ） -->
    @foreach ($reservations as $shopName => $shopReservations)
    <div class="owner-reservations__card-shop-title">{{ $shopName }}</div>
    @foreach ($shopReservations as $reservation)
    <div class="owner-reservations__card">
        <div class="owner-reservations__card-row">
            <span class="owner-reservations__card-label">予約者名:</span>{{ $reservation->user->name }}
        </div>
        <div class="owner-reservations__card-row">
            <span class="owner-reservations__card-label">予約日時:</span>{{ \Carbon\Carbon::parse($reservation->reserved_at)->format('Y年m月d日 H:i') }}
        </div>
        <div class="owner-reservations__card-row">
            <span class="owner-reservations__card-label">人数:</span>{{ $reservation->number_of_people }}名
        </div>
        <div class="owner-reservations__card-row">
            <span class="owner-reservations__card-label">ステータス:</span>{{ $reservation->status }}
        </div>
    </div>
    @endforeach
    @endforeach

</div>
@endsection