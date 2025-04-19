@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/owners-index.css') }}">
@endsection

@section('content')
<div class="owner-list">
    <h1 class="owner-list__title">店舗代表者一覧</h1>

    <div class="owner-list__actions">
        <a href="{{ route('admin.owners.create') }}" class="owner-list__create-button">＋ 新規登録</a>
    </div>

    <form method="GET" action="{{ route('admin.owners.index') }}" class="owner-list__search-form">
        <input type="text" name="keyword" value="{{ $keyword ?? '' }}" class="owner-list__search-input" placeholder="名前で検索">
        <button type="submit" class="owner-list__search-button">検索</button>
    </form>

    <table class="owner-list__table">
        <thead>
            <tr>
                <th>ID</th>
                <th>名前</th>
                <th>メールアドレス</th>
                <th>登録日</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($owners as $owner)
            <tr>
                <td>{{ $owner->id }}</td>
                <td>{{ $owner->name }}</td>
                <td>{{ $owner->email }}</td>
                <td>{{ $owner->created_at->format('Y/m/d') }}</td>
                <td class="owner-list__actions">
                    <form action="{{ route('admin.owners.destroy', $owner->id) }}" method="POST" onsubmit="return confirm('本当に削除しますか？')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="owner-list__delete-button">削除</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="owner-list__pagination">
        {{ $owners->links() }}
    </div>
</div>
@endsection