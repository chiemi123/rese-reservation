<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReviewsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('reviews')->insert([
            [
                'user_id' => 2,
                'shop_id' => 1,
                'reservation_id' => 1,
                'rating' => 5,
                'comment' => 'とても美味しくてサービスも良かったです！',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'shop_id' => 2,
                'reservation_id' => 2,
                'rating' => 4,
                'comment' => '雰囲気も良く、友達と楽しく過ごせました。',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
