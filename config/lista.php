<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Active store region
    |--------------------------------------------------------------------------
    |
    | Which regional Store enum populates the store picker. Slugs from other
    | regions still resolve when read from the database (so old lists keep
    | their badges), but only the active region's stores appear in the dropdown.
    |
    | Supported: "pt", "us", "uk"
    |
    */
    'stores' => [
        'region' => env('STORES_REGION', 'pt'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Currency symbol
    |--------------------------------------------------------------------------
    |
    | Shown next to every price on lists and totals. Defaults to € (Euro);
    | typical alternatives: $ (USD), £ (GBP), R$ (BRL), kr (NOK/SEK), zł (PLN).
    |
    */
    'currency' => [
        'symbol' => env('CURRENCY_SYMBOL', '€'),
    ],
];
