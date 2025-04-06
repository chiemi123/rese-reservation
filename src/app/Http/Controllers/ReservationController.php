<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ReservationRequest;
use App\Models\Reservation;
use Carbon\Carbon;

class ReservationController extends Controller
{
    public function store(ReservationRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();

        // 日付と時間を結合して reserved_at に設定
        $data['reserved_at'] = Carbon::createFromFormat('Y-m-d H:i', $data['date'] . ' ' . $data['time']);

        // カラム名の整合性を取る
        $data['number_of_people'] = $data['number'];

        // 不要なキーは削除（任意）
        unset($data['date'], $data['time'], $data['number']);

        Reservation::create($data);

        return redirect()->route('reservation.thanks');
    }
}
