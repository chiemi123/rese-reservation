<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;

class AdminReservationController extends Controller
{
    public function unpaid()
    {
        $unpaidReservations = Reservation::whereDoesntHave('payment')
            ->orWhereHas('payment', function ($query) {
                $query->where('status', '!=', 'paid');
            })
            ->with(['user', 'shop'])
            ->get();

        return view('admin.reservations.unpaid', compact('unpaidReservations'));
    }
}
