<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userRoleId = DB::table('roles')->where('name', 'user')->value('id');
        $shopOwnerRoleId = DB::table('roles')->where('name', 'shop_owner')->value('id');
        $adminRoleId = DB::table('roles')->where('name', 'admin')->value('id');

        // ① 店舗代表者（最初に作成 → ID=1）
        $shopOwner = User::create([
            'name' => '店舗代表者',
            'email' => 'owner@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        // ② 一般ユーザー
        $user = User::create([
            'name' => '一般ユーザー',
            'email' => 'user@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        // ③ 管理者
        $admin = User::create([
            'name' => '管理者',
            'email' => 'admin@example.com',
            'password' => Hash::make('password12345'),
            'email_verified_at' => now(),
        ]);

        // ④ ロール割り当て（user_role中間テーブル）
        DB::table('user_role')->insert([
            ['user_id' => $shopOwner->id, 'role_id' => $shopOwnerRoleId],
            ['user_id' => $user->id,      'role_id' => $userRoleId],
            ['user_id' => $admin->id,     'role_id' => $adminRoleId],
        ]);
    }
}
