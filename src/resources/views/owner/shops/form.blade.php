@php
$suppressFlashMessages = true;
@endphp

@extends('layouts.owner')

@section('css')
<link rel="stylesheet" href="{{ asset('css/owner/form.css') }}">
@endsection

@section('title', isset($shop) ? '店舗情報編集' : '新規店舗登録')

@section('content')
<h1 class="page-title">{{ isset($shop) ? '店舗情報編集' : '新規店舗登録' }}</h1>

<form
    class="shop-form"
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
    <div class="shop-form__group">
        <label for="name">店舗名</label>
        <input
            id="name"
            type="text"
            name="name"
            value="{{ old('name', $shop->name ?? '') }}">
        @error('name')
        <div class="shop-form__error">{{ $message }}</div>
        @enderror
    </div>

    {{-- 説明文 --}}
    <div class="shop-form__group">
        <label for="description">説明</label>
        <textarea
            id="description"
            name="description"
            rows="5">{{ old('description', $shop->description ?? '') }}</textarea>
        @error('description')
        <div class="shop-form__error">{{ $message }}</div>
        @enderror
    </div>

    {{-- エリア --}}
    <div class="shop-form__group">
        <label for="area_id">エリア</label>
        <div class="shop-form__select-wrapper">
            <select id="area_id" name="area_id">
                @foreach($areas as $area)
                <option
                    value="{{ $area->id }}"
                    {{ old('area_id', $shop->area_id ?? '') == $area->id ? 'selected' : '' }}>
                    {{ $area->name }}
                </option>
                @endforeach
            </select>
            <span class="shop-form__select-arrow">⮟</span>
        </div>
        @error('area_id')
        <div class="shop-form__error">{{ $message }}</div>
        @enderror
    </div>

    {{-- ジャンル --}}
    <div class="shop-form__group">
        <label for="genre_id">ジャンル</label>
        <div class="shop-form__select-wrapper">
            <select id="genre_id" name="genre_id">
                @foreach($genres as $genre)
                <option
                    value="{{ $genre->id }}"
                    {{ old('genre_id', $shop->genre_id ?? '') == $genre->id ? 'selected' : '' }}>
                    {{ $genre->name }}
                </option>
                @endforeach
            </select>
            <span class="shop-form__select-arrow">⮟</span>
        </div>
        @error('genre_id')
        <div class="shop-form__error">{{ $message }}</div>
        @enderror
    </div>

    {{-- 画像 --}}
    <div class="shop-form__group">
        <label for="image">店舗画像</label>
        @if (!empty($shop->image))
        <div>
            @if (Str::startsWith($shop->image, ['http://', 'https://']))
            <img src="{{ $shop->image }}" alt="現在の画像" class="shop-form__image-preview">
            @else
            @php
            $disk = env('FILESYSTEM_DRIVER', 'public');
            $url = $disk === 's3'
            ? Storage::disk('s3')->url($shop->image)
            : asset('storage/' . $shop->image);
            @endphp
            <img src="{{ $url }}" alt="現在の画像" class="shop-form__image-preview">
            @endif
        </div>
        @endif
        <input id="image" type="file" name="image">
        @error('image')
        <div class="shop-form__error">{{ $message }}</div>
        @enderror
    </div>

    {{-- ボタン --}}
    <div class="shop-form__group">
        <button type="submit" class="shop-form__submit-btn">
            {{ isset($shop) ? '更新する' : '登録する' }}
        </button>
    </div>
</form>

@endsection