<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservation;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\ReservationReminder;
use Carbon\Carbon;

class SendReservationReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reservations:send-reminders';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '明日の予約に対するリマインダーメールを送信します';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Log::info('send-reminders コマンドが実行されました');

        $tomorrow = Carbon::tomorrow()->startOfDay();
        $endOfTomorrow = Carbon::tomorrow()->endOfDay();

        $reservations = Reservation::whereBetween('reserved_at', [$tomorrow, $endOfTomorrow])
            ->where('status', 'reserved')
            ->with('user', 'shop')
            ->get();

        if ($reservations->count()) {
            Log::info('明日の予約あり：' . $reservations->count() . ' 件');
        } else {
            Log::info('明日の予約なし：送信スキップ');
        }

        foreach ($reservations as $reservation) {
            Mail::to($reservation->user->email)->queue(new ReservationReminder($reservation));
            Log::info('リマインダー送信：予約ID ' . $reservation->id);
        }

        $this->info('リマインダー送信完了');
    }
}
