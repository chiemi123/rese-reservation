@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
<div class="page-container">
    <div class="search-bar-wrapper">
        {{-- 検索・絞り込みフォーム --}}
        <form method="GET" action="{{ route('shops.index') }}" class="search-bar">
            <div class="search-bar__item">
                <select name="area" class="search-bar__select">
                    <option value="">All area</option>
                    @foreach ($areas as $area)
                    <option value="{{ $area->id }}" {{ request('area') == $area->id ? 'selected' : '' }}>{{ $area->name }}</option>
                    @endforeach
                </select>
                <span class="search-bar__arrow">⮟</span>
            </div>

            <div class="search-bar__item">
                <select name="genre" class="search-bar__select">
                    <option value="">All genre</option>
                    @foreach ($genres as $genre)
                    <option value="{{ $genre->id }}" {{ request('genre') == $genre->id ? 'selected' : '' }}>{{ $genre->name }}</option>
                    @endforeach
                </select>
                <span class="search-bar__arrow">⮟</span>
            </div>

            <div class="search-bar__item search-bar__item--input">
                <span class="material-icons search-bar__icon">search</span>
                <input type="text" name="word" value="{{ request('word') }}" placeholder="Search ..." class="search-bar__input">
            </div>
        </form>
    </div>

    {{-- 飲食店カード一覧 --}}
    <div class="shop-list">
        @forelse ($shops as $shop)
        <div class="shop-card">
            <img src="{{ $shop->image }}" class="shop-card__image" alt="{{ $shop->name }}">
            <div class="shop-card__body">
                <h3 class="shop-card__title">{{ $shop->name }}</h3>
                <p class="shop-card__tags">#{{ $shop->area->name }} #{{ $shop->genre->name }}</p>
                <div class="shop-card__footer">
                    <a href="{{ route('shops.show', $shop->id) }}" class="shop-card__link">詳しくみる</a>
                    @if (Auth::check())
                    @if (in_array($shop->id, $favorites))
                    <form method="POST" action="{{ route('unfavorite', $shop) }}" class="favorite-form">
                        @csrf @method('DELETE')
                        <button type="submit" class="favorite-button favorite-button--active" title="お気に入り解除"><span class="material-icons favorite-icon">favorite</span></button>
                    </form>
                    @else
                    <form method="POST" action="{{ route('favorite', $shop) }}" class="favorite-form">
                        @csrf
                        <button type="submit" class="favorite-button" title="お気に入り追加"><span class="material-icons favorite-icon">favorite</span></button>
                    </form>
                    @endif
                    @else
                    <a href="/login" class="favorite-button" title="ログインしてお気に入り">
                        <span class="material-icons favorite-icon">favorite_border</span>
                    </a>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <p>該当する店舗が見つかりませんでした。</p>
        @endforelse
    </div>

    {{-- ページネーション（Tailwind使用） --}}
    <div class="pagination-container">
        {{ $shops->appends(request()->query())->links() }}
    </div>
</div>
@endsection