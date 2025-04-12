<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class GenreFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->randomElement([
                '寿司',
                '焼肉',
                'イタリアン',
                'カフェ',
                'フレンチ',
                '中華',
                '和食',
                '洋食',
                '韓国料理',
                'ベトナム料理'
            ]),
        ];
    }
}
