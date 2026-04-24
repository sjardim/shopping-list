<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => config('lista.owner.email')],
            [
                'name' => config('lista.owner.name'),
                'password' => Hash::make(config('lista.owner.password')),
            ]
        );

        $this->call([
            // CatalogItemSeeder::class,
            CatalogItemSeederEn::class,
            ShoppingHistorySeeder::class,
        ]);
    }
}
