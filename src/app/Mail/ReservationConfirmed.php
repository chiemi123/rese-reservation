<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ReservationConfirmed extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $user;
    public $reservation;
    public $qrCodeHtml;
    public $isUpdate;

    public function __construct($user, $reservation, $isUpdate = false)
    {
        $this->user = $user;
        $this->reservation = $reservation;
        $this->isUpdate = $isUpdate;

        // QRコードトークンがなければ作成
        $qrCode = $reservation->qrCode ?? $reservation->qrCode()->create([
            'token' => uniqid(),
        ]);

        // inline方式：SVGやHTMLで直接QRコードを生成
        $this->qrCodeHtml = \QrCode::size(200)->generate($qrCode->token);
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('【Rese】予約が完了しました')
            ->view('emails.reservation')
            ->with([
                'userName' => $this->user->name,
                'reservationTime' => $this->reservation->reserved_at->translatedFormat('Y年m月d日 H:i'),
                'qrCodeHtml' => $this->qrCodeHtml,
                'qrUrl' => route('reservations.qr', ['id' => $this->reservation->id]),
                'isUpdate' => $this->isUpdate,
            ]);
    }
}
