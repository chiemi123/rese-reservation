<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use App\Http\Requests\ReservationRequest;
use App\Models\Reservation;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
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

    public function cancel($id)
    {
        $reservation = Reservation::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $reservation->update(['status' => 'canceled']);

        return redirect()->route('user.mypage')->with('message', '予約をキャンセルしました。');
    }

    public function update(ReservationRequest $request, $id)
    {
        $reservation = Reservation::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        // バリデーション済みデータを取得
        $validated = $request->validated();

        // date + time → reserved_at に変換
        $reservedAt = $validated['date'] . ' ' . $validated['time'];

        $reservation->update([
            'shop_id' => $validated['shop_id'],
            'reserved_at' => $reservedAt,
            'number_of_people' => $validated['number'],
        ]);

        return redirect()->route('user.mypage')->with('message', '予約内容を変更しました。');
    }

    public function showQr($id)
    {
        $reservation = Reservation::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // サイン付きURLを発行（例: /reservations/confirm/123?signature=...）
        $signedUrl = URL::signedRoute('reservation.confirm', ['reservation' => $reservation->id]);

        // QRコードにサイン付きURLを入れる
        $qr = QrCode::size(200)->generate($signedUrl);

        return view('user.qrcode', compact('qr', 'reservation'));
    }

    public function confirm(Request $request, Reservation $reservation)
    {
        if ($reservation->status === 'used') {
            return view('reservation.confirmed', ['message' => 'この予約はすでに確認済みです。']);
        }

        $reservation->update(['status' => 'used']);

        return view('owner.reservations.confirmed', ['message' => '予約が確認されました。']);
    }
}
