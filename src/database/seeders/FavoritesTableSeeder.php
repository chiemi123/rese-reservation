<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FavoritesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('favorites')->insert([
            ['user_id' => 2, 'shop_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => 2, 'shop_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => 2, 'shop_id' => 3, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
