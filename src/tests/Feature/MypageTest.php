<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Reservation;
use App\Models\Shop;


class MypageTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_reservations_and_favorites()
    {
        // ユーザー作成
        $user = User::factory()->create();

        // 店舗と予約作成
        $shop = Shop::factory()->create();
        $reservation = Reservation::factory()->create([
            'user_id' => $user->id,
            'shop_id' => $shop->id,
        ]);

        // お気に入り登録（中間テーブルに挿入）
        $user->favorites()->attach($shop->id);

        // ログイン状態でmypageへアクセス
        $response = $this->actingAs($user)->get('/mypage');

        // 表示されるべき内容を確認
        $response->assertStatus(200);
        $response->assertSee($shop->name);                    // お気に入り店舗名
        $response->assertSee('予約状況');                      // 表示文言
        $response->assertSee($reservation->date);             // 予約日
        $response->assertSee('キャンセル');                   // キャンセルボタン
        $response->assertSee('QRコード');                    // QRコード関連
    }

    public function test_user_can_cancel_a_reservation()
    {
        $user = User::factory()->create();
        $reservation = Reservation::factory()->create([
            'user_id' => $user->id,
            'status' => 'reserved',
        ]);

        $response = $this->actingAs($user)->delete("/reservations/{$reservation->id}/cancel");

        $response->assertRedirect('/mypage'); // 例えばマイページに戻る想定
        $this->assertDatabaseHas('reservations', [
            'id' => $reservation->id,
            'status' => 'canceled',
        ]);
    }

    public function test_user_can_add_and_remove_favorite_shop()
    {
        $user = User::factory()->create();
        $shop = Shop::factory()->create();

        // お気に入り追加
        $this->actingAs($user)->post("/favorite/{$shop->id}");
        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'shop_id' => $shop->id,
        ]);

        // お気に入り削除
        $this->actingAs($user)->delete("/favorite/{$shop->id}");
        $this->assertDatabaseMissing('favorites', [
            'user_id' => $user->id,
            'shop_id' => $shop->id,
        ]);
    }

    public function test_qr_code_is_displayed_on_mypage()
    {
        $user = User::factory()->create();
        $reservation = Reservation::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get('/mypage');

        $response->assertStatus(200);
        $response->assertSee('data:image/png;base64', false); // 厳密一致を無効に
    }

    public function test_reservation_status_updates_after_payment()
    {
        $user = User::factory()->create();
        $reservation = Reservation::factory()->create([
            'user_id' => $user->id,
            'status' => 'reserved',
        ]);

        // Stripeの処理をモック（必要なら Service 層などもFake化）
        // ここでは支払い成功後のルートを直接叩く例
        $response = $this->actingAs($user)->post("/reservations/{$reservation->id}/payment/success");

        $response->assertRedirect('/mypage');

        $this->assertDatabaseHas('reservations', [
            'id' => $reservation->id,
            'status' => 'paid',
        ]);
    }


    public function test_guest_is_redirected_from_mypage()
    {
        $response = $this->get('/mypage');
        $response->assertRedirect('/login');  // ゲストはログインページへ
    }
}
