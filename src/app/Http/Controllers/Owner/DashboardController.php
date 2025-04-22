<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Reservation;


class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $shops = $user->shops()->with(['area', 'genre'])->paginate(6);

        // 店舗代表者に紐づいた予約の最新5件を取得
        $reservations = Reservation::whereHas('shop', function ($query) use ($user) {
            $query->where('owner_id', $user->id);
        })
            ->with('shop') // Eagerロード（店舗名など表示したい場合に）
            ->latest()
            ->take(5)
            ->get();


        return view('owner.dashboard', compact('user', 'shops', 'reservations'));
    }
}
