<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use Database\Seeders\RolesTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserLoginTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seederを使ってロールを作成
        $this->seed(RolesTableSeeder::class);
    }

    /** @test */
    public function 一般ユーザーはログインできる()
    {
        $user = User::factory()->create([
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);
        $user->roles()->attach(Role::where('name', 'user')->first());

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function 管理者は一般ログインから弾かれる()
    {
        $admin = User::factory()->create([
            'password' => Hash::make('adminpass'),
            'email_verified_at' => now(),
        ]);
        $admin->roles()->attach(Role::where('name', 'admin')->first());

        $response = $this->from('/login')->post('/login', [
            'email' => $admin->email,
            'password' => 'adminpass',
        ]);

        $response->assertRedirect('/login'); // ログイン画面に戻る
        $response->assertSessionHasErrors(['email']);
        $this->assertGuest(); // ログインしていないこと
    }
}
