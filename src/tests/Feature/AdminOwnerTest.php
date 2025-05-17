<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;

class AdminOwnerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        // テスト用DBにロールを登録
        Role::firstOrCreate(['name' => 'shop_owner']);
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'user']);
    }

    /**
     * @test
     */
    public function 管理者が店舗代表者を登録できる()
    {
        // 管理者としてログイン（adminロールを持つユーザーを作成）
        $admin = User::factory()->create();
        $admin->roles()->attach(Role::firstOrCreate(['name' => 'admin'])->id);

        // ログイン状態でPOST（登録処理）
        $response = $this
            ->actingAs($admin, 'admin')
            ->post(route('admin.owners.store'), [
                'name' => 'テストオーナー',
                'email' => 'owner@example.com',
                'password' => 'password123',
                'password_confirmation' => 'password123',
            ]);

        // リダイレクト確認（一覧ページへ）
        $response->assertRedirect(route('admin.owners.index'));

        // ユーザーが作成されたか確認
        $this->assertDatabaseHas('users', ['email' => 'owner@example.com']);

        // user_role テーブルにロールがついているか確認
        $user = User::where('email', 'owner@example.com')->first();
        $this->assertTrue($user->roles->contains('name', 'shop_owner'));
    }

    /** @test */
    public function パスワード未入力の場合は登録できない()
    {
        $admin = User::factory()->create();
        $admin->roles()->attach(Role::firstOrCreate(['name' => 'admin'])->id);

        $response = $this
            ->actingAs($admin, 'admin')
            ->post(route('admin.owners.store'), [
                'name' => 'テストオーナー',
                'email' => 'no-password@example.com',
                'password' => '',
                'password_confirmation' => '',
            ]);

        $response->assertSessionHasErrors(['password']); // ← バリデーションエラーが出ることを確認
    }

    /** @test */
    public function 同じメールアドレスでは登録できない()
    {
        // すでに同じメールのユーザーを作成
        User::factory()->create([
            'email' => 'duplicate@example.com'
        ]);

        $admin = User::factory()->create();
        $admin->roles()->attach(Role::firstOrCreate(['name' => 'admin'])->id);

        $response = $this
            ->actingAs($admin, 'admin')
            ->post(route('admin.owners.store'), [
                'name' => 'ダブりユーザー',
                'email' => 'duplicate@example.com',
                'password' => 'password123',
                'password_confirmation' => 'password123',
            ]);

        $response->assertSessionHasErrors(['email']);
    }

    /** @test */
    public function 未ログイン状態では店舗代表者一覧にアクセスできない()
    {
        $response = $this->get(route('admin.owners.index'));

        // ミドルウェア auth:admin によって /login にリダイレクトされる
        $response->assertRedirect(route('admin.login')); // ルート名に合わせて変更してね
    }

    /** @test */
    public function 一覧画面にはshop_ownerロールのユーザーだけが表示される()
    {
        $admin = User::factory()->create();
        $admin->roles()->attach(Role::firstOrCreate(['name' => 'admin'])->id);

        $shopOwnerRole = Role::firstOrCreate(['name' => 'shop_owner']);
        $userRole = Role::firstOrCreate(['name' => 'user']);

        // 店舗代表者
        $owner = User::factory()->create(['name' => 'オーナー太郎']);
        $owner->roles()->attach($shopOwnerRole->id);

        // 一般ユーザー
        $user = User::factory()->create(['name' => '一般花子']);
        $user->roles()->attach($userRole->id);

        $response = $this
            ->actingAs($admin, 'admin')
            ->get(route('admin.owners.index'));

        $response->assertSee('オーナー太郎');
        $response->assertDontSee('一般花子');
    }

    /** @test */
    public function 管理者は店舗代表者をソフトデリートできる()
    {
        // 管理者とロール
        $admin = User::factory()->create();
        $admin->roles()->attach(Role::firstOrCreate(['name' => 'admin'])->id);

        // 店舗代表者のユーザー作成
        $owner = User::factory()->create([
            'name' => '削除対象オーナー',
            'email' => 'delete_me@example.com',
        ]);
        $owner->roles()->attach(Role::firstOrCreate(['name' => 'shop_owner'])->id);

        // 削除処理を実行
        $response = $this
            ->actingAs($admin, 'admin')
            ->delete(route('admin.owners.destroy', $owner->id));

        // 削除後、一覧にリダイレクトされることを確認
        $response->assertRedirect(route('admin.owners.index'));

        // ソフトデリートされているか確認（deleted_at が入っている）
        $this->assertSoftDeleted('users', ['id' => $owner->id]);

        // 通常クエリでは取得できないことも確認（論理削除されてる）
        $this->assertNull(User::find($owner->id));
    }
}
