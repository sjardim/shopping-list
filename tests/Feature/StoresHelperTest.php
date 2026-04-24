<?php

use App\Enums\StorePt;
use App\Enums\StoreUk;
use App\Enums\StoreUs;
use App\Support\Stores;

test('active() returns the cases of the configured region', function () {
    config()->set('lista.stores.region', 'pt');
    expect(Stores::active())->toBe(StorePt::cases());

    config()->set('lista.stores.region', 'us');
    expect(Stores::active())->toBe(StoreUs::cases());

    config()->set('lista.stores.region', 'uk');
    expect(Stores::active())->toBe(StoreUk::cases());
});

test('tryFrom() resolves slugs from the active region first', function () {
    config()->set('lista.stores.region', 'us');

    expect(Stores::tryFrom('walmart'))->toBe(StoreUs::Walmart);
});

test('tryFrom() falls back to other regions for unknown slugs', function () {
    config()->set('lista.stores.region', 'us');

    // continente only exists in StorePt
    expect(Stores::tryFrom('continente'))->toBe(StorePt::Continente);
});

test('tryFrom() returns null for unknown slugs', function () {
    expect(Stores::tryFrom('not-a-real-store'))->toBeNull();
    expect(Stores::tryFrom(''))->toBeNull();
    expect(Stores::tryFrom(null))->toBeNull();
});

test('tryFrom() prefers active region for slugs that exist in multiple regions', function () {
    // "lidl" exists in both StorePt and StoreUk
    config()->set('lista.stores.region', 'pt');
    expect(Stores::tryFrom('lidl'))->toBe(StorePt::Lidl);

    config()->set('lista.stores.region', 'uk');
    expect(Stores::tryFrom('lidl'))->toBe(StoreUk::Lidl);
});
