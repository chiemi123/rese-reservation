<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReservationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('reservations')->insert([
            [
                'user_id' => 2,
                'shop_id' => 1,
                'reserved_at' => now()->addDays(3)->setTime(19, 0),
                'number_of_people' => 2,
                'status' => 'reserved',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'shop_id' => 2,
                'reserved_at' => now()->addDays(5)->setTime(12, 30),
                'number_of_people' => 3,
                'status' => 'reserved',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
