<p>{{ $userName }}さん、こんにちは！</p>

@if ($isUpdate)
    <p>予約内容の変更を承りました。</p>
@else
    <p>ご予約ありがとうございます。</p>
@endif

<p>以下が最新の予約内容です。</p>

<p>予約日時：{{ $reservationTime }}</p>

<p>下記の「QRコードを見る」ボタンをクリックし、表示されたQRコードを店舗スタッフにご提示ください。</p>


<a href="{{ $qrUrl }}"
    style="display: inline-block; padding: 10px 20px; background-color: #3490dc; color: #fff; text-decoration: none; border-radius: 5px;">
    QRコードを見る
</a>

<p>ご不明な点がございましたら、いつでもお問い合わせください。</p>

<p>Rese運営チームより</p>