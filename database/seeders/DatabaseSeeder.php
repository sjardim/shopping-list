<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            OwnerUserSeeder::class,
            // CatalogItemSeeder::class,
            CatalogItemSeederEn::class,
            ShoppingHistorySeeder::class,
        ]);
    }
}
