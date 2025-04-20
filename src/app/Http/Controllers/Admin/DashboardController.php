<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Shop;
use App\Models\User;
use App\Models\Role;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        $todayReservations = Reservation::whereDate('created_at', $today)->count();

        $ownerRole = Role::where('name', 'shop_owner')->first();
        $ownerCount = $ownerRole ? $ownerRole->users()->count() : 0;
        $shopCount = Shop::count();
        $newUsers = User::orderBy('created_at', 'desc')->take(5)->get();

        return view('admin.dashboard', compact(
            'todayReservations',
            'ownerCount',
            'shopCount',
            'newUsers'
        ));
    }
}
