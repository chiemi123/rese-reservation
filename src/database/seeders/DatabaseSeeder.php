<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RolesTableSeeder::class,
            UsersTableSeeder::class,
            AreasTableSeeder::class,
            GenresTableSeeder::class,
            ShopsTableSeeder::class,
            FavoritesTableSeeder::class,
            ReservationsTableSeeder::class,
            ReviewsTableSeeder::class,
        ]);
    }
}
