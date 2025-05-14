<p>{{ $reservation->user->name }} 様</p>

<p>以下の予約が明日となっております。</p>

<ul>
    <li>店舗名：{{ $reservation->shop->name }}</li>
    <li>日時：{{ $reservation->reserved_at->format('Y年m月d日 H:i') }}</li>
    <li>人数：{{ $reservation->number_of_people }}人</li>
</ul>

<p>ご来店をお待ちしております。</p>