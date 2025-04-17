<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Shop;
use Illuminate\Support\Carbon;

class ReservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'shop_id' => Shop::factory(),
            'reserved_at' => Carbon::now()->addDays(3)->setTime(18, 0), // 日時をまとめて指定
            'number_of_people' => $this->faker->numberBetween(1, 6),
            'status' => 'reserved',
        ];
    }
}
