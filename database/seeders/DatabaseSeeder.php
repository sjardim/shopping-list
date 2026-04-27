<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            $this->catalogSeederForLocale((string) config('app.locale')),
            $this->historySeederForRegion((string) config('lista.stores.region')),
        ]);
    }

    private function catalogSeederForLocale(string $locale): string
    {
        return match ($locale) {
            'pt_PT' => CatalogItemSeeder::class,
            'pt_BR' => CatalogItemSeederBr::class,
            'en_GB' => CatalogItemSeederGb::class,
            'es' => CatalogItemSeederEs::class,
            default => CatalogItemSeederEn::class,
        };
    }

    private function historySeederForRegion(string $region): string
    {
        return match ($region) {
            'pt' => ShoppingHistorySeeder::class,
            'br' => ShoppingHistorySeederBr::class,
            'uk' => ShoppingHistorySeederGb::class,
            'es' => ShoppingHistorySeederEs::class,
            default => ShoppingHistorySeederEn::class,
        };
    }
}
