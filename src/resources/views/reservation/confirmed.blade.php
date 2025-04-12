@extends('layouts.app')

@section('content')
<div class="container">
    <h2>{{ $message }}</h2>
    <a href="{{ route('shops.index') }}" class="btn">トップに戻る</a>
</div>
@endsection