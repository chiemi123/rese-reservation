<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            [
                'name' => 'user',
                'display_name' => '利用者',
                'description' => '飲食店を検索・予約・評価できる一般ユーザー',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'shop_owner',
                'display_name' => '店舗代表',
                'description' => '店舗情報を管理・予約確認ができる飲食店の管理者',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'admin',
                'display_name' => '管理者',
                'description' => 'システム全体の管理・店舗代表者の登録・通知の送信などが可能',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
