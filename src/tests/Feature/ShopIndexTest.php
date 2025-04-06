<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Shop;
use App\Models\User;
use App\Models\Area;
use App\Models\Genre;

class ShopIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_ショップ一覧ページが表示される()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(), // ← メール確認済みにする！
        ]);

        $this->actingAs($user);


        // ダミー店舗を作成
        Shop::factory()->create([
            'name' => 'テスト寿司',
        ]);

        // 一覧ページへアクセス
        $response = $this->get(route('shops.index'));

        // ステータスコード確認
        $response->assertStatus(200);

        // 店舗名が表示されているか
        $response->assertSee('テスト寿司');
    }

    public function test_キーワード検索で店舗が絞り込まれる()
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $this->actingAs($user);

        Shop::factory()->create(['name' => '寿司太郎']);
        Shop::factory()->create(['name' => '焼肉一番']);

        $response = $this->get(route('shops.index', ['word' => '寿司']));
        $response->assertSee('寿司太郎');
        $response->assertDontSee('焼肉一番');
    }

    public function test_お気に入りに登録できる()
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $this->actingAs($user);

        $shop = Shop::factory()->create();

        $response = $this->post(route('favorite', $shop));
        $response->assertRedirect();

        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'shop_id' => $shop->id,
        ]);
    }

    public function test_お気に入りを解除できる()
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $this->actingAs($user);

        $shop = Shop::factory()->create();

        // 事前にお気に入り登録しておく
        $user->favorites()->attach($shop->id);

        // 削除リクエスト（DELETE送信）
        $response = $this->delete(route('unfavorite', $shop));

        $response->assertRedirect();

        // favorites テーブルから削除されているか確認
        $this->assertDatabaseMissing('favorites', [
            'user_id' => $user->id,
            'shop_id' => $shop->id,
        ]);
    }
}
