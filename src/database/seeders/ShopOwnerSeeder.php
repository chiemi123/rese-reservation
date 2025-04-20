<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class ShopOwnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $shopOwnerRoleId = Role::firstOrCreate(['name' => 'shop_owner'])->id;

        User::factory()
            ->count(20)
            ->create()
            ->each(function ($user) use ($shopOwnerRoleId) {
                $user->roles()->attach($shopOwnerRoleId);
            });
    }
}
