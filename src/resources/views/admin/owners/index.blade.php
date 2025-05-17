@extends('layouts.admin')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/owners-index.css') }}">
@endsection

@section('content')
<div class="owner-list">
    <h1 class="owner-list__title">店舗代表者一覧</h1>

    <div class="owner-list__form-bar">
        <form method="GET" action="{{ route('admin.owners.index') }}" class="owner-list__search-form">
            <div class="owner-list__search-box">
                <span class="material-icons search-bar__icon">search</span>
                <input type="text" name="keyword" class="owner-list__search-input" placeholder="Search...">
            </div>
        </form>

        <a href="{{ route('admin.owners.create') }}" class="owner-list__create-button">＋ 新規登録</a>
    </div>

    <div class="table-wrapper">
        <table class="owner-list__table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>名前</th>
                    <th>メールアドレス</th>
                    <th>登録日</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($owners as $owner)
                <tr>
                    <td data-label="ID">{{ $owner->id }}</td>
                    <td data-label="名前">{{ $owner->name }}</td>
                    <td data-label="メールアドレス">{{ $owner->email }}</td>
                    <td data-label="登録日">{{ $owner->created_at->format('Y/m/d') }}</td>
                    <td data-label="操作">
                        <div class="owner-list__button-wrapper">
                            <form method="POST" action="{{ route('admin.owners.destroy', $owner->id) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="owner-list__delete-button">削除</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="owner-list__pagination">
        {{ $owners->links() }}
    </div>
</div>
@endsection