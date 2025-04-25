<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Reservation;
use App\Models\Shop;

class ReservationController extends Controller
{
    public function index(Request $request)
    {

        $ownerId = Auth::guard('owner')->id();

        $ownerShops = Shop::where('owner_id', $ownerId)->get();

        $query = Reservation::with(['user', 'shop'])
            ->whereHas('shop', fn($q) => $q->where('owner_id', $ownerId));

        if ($request->filled('shop_id')) {
            $query->where('shop_id', $request->shop_id);
        }

        // 店舗ごとに予約をグループ化し、日付でソート
        $reservations = $query->orderBy('shop_id')
            ->orderBy('reserved_at')
            ->get()
            ->groupBy(function ($reservation) {
                return $reservation->shop->name;
            });

        return view('owner.reservations.index', compact('reservations', 'ownerShops'));
    }
}
