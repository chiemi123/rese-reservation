@extends('layouts.owner')

@section('css')
<link rel="stylesheet" href="{{ asset('css/owner/form.css') }}">
@endsection

@section('title', isset($shop) ? '店舗情報編集' : '新規店舗登録')

@section('content')
<h1 class="page-title">{{ isset($shop) ? '店舗情報編集' : '新規店舗登録' }}</h1>

<form
    method="POST"
    action="{{ isset($shop)
        ? route('owner.shops.update', ['shop' => $shop->id])
        : route('owner.shops.store') }}"
    enctype="multipart/form-data">
    @csrf
    @if (isset($shop))
    @method('PUT')
    @endif

    {{-- 店舗名 --}}
    <div class="form-group">
        <label for="name">店舗名</label>
        <input
            id="name"
            type="text"
            name="name"
            value="{{ old('name', $shop->name ?? '') }}">
        @error('name')
        <div class="error">{{ $message }}</div>
        @enderror
    </div>

    {{-- 説明文 --}}
    <div class="form-group">
        <label for="description">説明</label>
        <textarea
            id="description"
            name="description"
            rows="5">{{ old('description', $shop->description ?? '') }}</textarea>
        @error('description')
        <div class="error">{{ $message }}</div>
        @enderror
    </div>

    {{-- エリア --}}
    <div class="form-group">
        <label for="area_id">エリア</label>
        <div class="select-wrapper">
            <select id="area_id" name="area_id">
                @foreach($areas as $area)
                <option
                    value="{{ $area->id }}"
                    {{ old('area_id', $shop->area_id ?? '') == $area->id ? 'selected' : '' }}>
                    {{ $area->name }}
                </option>
                @endforeach
            </select>
            <span class="search-bar__arrow">⮟</span>
        </div>
        @error('area_id')
        <div class="error">{{ $message }}</div>
        @enderror
    </div>

    {{-- ジャンル --}}
    <div class="form-group">
        <label for="genre_id">ジャンル</label>
        <div class="select-wrapper">
            <select id="genre_id" name="genre_id">
                @foreach($genres as $genre)
                <option
                    value="{{ $genre->id }}"
                    {{ old('genre_id', $shop->genre_id ?? '') == $genre->id ? 'selected' : '' }}>
                    {{ $genre->name }}
                </option>
                @endforeach
            </select>
            <span class="search-bar__arrow">⮟</span>
        </div>
        @error('genre_id')
        <div class="error">{{ $message }}</div>
        @enderror
    </div>

    {{-- 画像 --}}
    <div class="form-group">
        <label for="image">店舗画像</label>
        @if (!empty($shop->image))
        <div>
            @if (Str::startsWith($shop->image, ['http://', 'https://']))
            {{-- 外部URLの場合 --}}
            <img src="{{ $shop->image }}" alt="現在の画像" class="preview">
            @else
            {{-- ローカルstorageの場合 --}}
            <img src="{{ asset('storage/' . $shop->image) }}" alt="現在の画像" class="preview">
            @endif
        </div>
        @else
        <p>画像は登録されていません</p>
        @endif
        <input id="image" type="file" name="image">
        @error('image')
        <div class="error">{{ $message }}</div>
        @enderror
    </div>

    {{-- ボタン --}}
    <div class="form-group">
        <button type="submit" class="owner-btn-primary">
            {{ isset($shop) ? '更新する' : '登録する' }}
        </button>
    </div>
</form>
@endsection