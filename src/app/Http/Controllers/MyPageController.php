<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MyPageController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $reservations = $user->reservations()->with('shop')->get();
        // お気に入り店舗の一覧（Shopモデルのコレクション）
        $favoriteShops = $user->favorites;

        // お気に入りのShop ID一覧（in_arrayで使うため）
        $favorites = $user->favorites()->pluck('shops.id')->toArray();

        $reviews = $user->reviews()->with('shop')->latest()->get();

        return view('user.mypage', compact('reservations', 'favoriteShops', 'favorites','reviews'));
    }
}
