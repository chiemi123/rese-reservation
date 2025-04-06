<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Shop;

class ReservationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function ログインユーザーは予約を登録できる()
    {
        // テスト用ユーザーと店舗を作成
        $user = User::factory()->create(['email_verified_at' => now()]);
        $this->actingAs($user);

        $shop = Shop::factory()->create();


        // ログインして予約リクエストを送信
        $response = $this->actingAs($user)->post('/reservations', [
            'shop_id' => $shop->id,
            'date' => '2025-04-15',
            'time' => '18:00',
            'number' => 2,
        ]);

        // 予約成功後のリダイレクト確認
        $response->assertRedirect(route('reservation.thanks'));

        // DBに予約が登録されたか確認
        $this->assertDatabaseHas('reservations', [
            'user_id' => $user->id,
            'shop_id' => $shop->id,
        ]);
    }

    public function test_予約日が空だとバリデーションエラーになる()
    {
        $user = \App\Models\User::factory()->create(['email_verified_at' => now()]);
        $this->actingAs($user);

        $shop = \App\Models\Shop::factory()->create();

        $response = $this->from('/shops/' . $shop->id)->post('/reservations', [
            'shop_id' => $shop->id,
            'date' => '', // ← 不正な日付（空）
            'time' => '18:00',
            'number' => 2,
        ]);

        // バリデーションエラーになることを確認
        $response->assertSessionHasErrors(['date']);

        // 元のページにリダイレクトされることも確認（任意）
        $response->assertRedirect('/shops/' . $shop->id);
    }
}
