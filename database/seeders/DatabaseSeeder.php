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
            ['email' => 'sergio@sergiojardim.com'],
            [
                'name' => 'Sergio',
                'password' => Hash::make('secret'),
            ]
        );

        $this->call([
            // CatalogItemSeeder::class,
            CatalogItemSeederEn::class,
            ShoppingHistorySeeder::class,
        ]);
    }
}
