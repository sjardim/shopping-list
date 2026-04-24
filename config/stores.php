<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Active store region
    |--------------------------------------------------------------------------
    |
    | Which regional Store enum populates the store picker. Slugs from other
    | regions still resolve when read from the database (so old lists keep
    | their badge), but only the active region's stores appear in the dropdown.
    |
    | Supported: "pt", "us", "uk"
    |
    */
    'region' => env('STORES_REGION', 'pt'),
];
