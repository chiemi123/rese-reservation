<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Shop;


class FavoriteController extends Controller
{
    public function store(Shop $shop)
    {
        $user = Auth::user();

        // すでにお気に入りしているか確認
        if (!$user->favorites->contains($shop->id)) {
            $user->favorites()->attach($shop->id);
        }

        return back();
    }

    public function destroy(Shop $shop)
    {
        Auth::user()->favorites()->detach($shop->id);
        return back();
    }
}
