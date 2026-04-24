<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\info;
use function Laravel\Prompts\note;
use function Laravel\Prompts\password;
use function Laravel\Prompts\select;
use function Laravel\Prompts\text;
use function Laravel\Prompts\warning;

#[Signature('lista:install')]
#[Description('Interactive setup: pick locale, region, currency, and owner — writes .env, migrates, and seeds.')]
class InstallCommand extends Command
{
    private const LOCALES = [
        'en' => 'English',
        'en_GB' => 'English (UK)',
        'pt_PT' => 'Português (Portugal)',
        'pt_BR' => 'Português (Brasil)',
    ];

    private const REGIONS = [
        'pt' => 'Portugal',
        'us' => 'United States',
        'uk' => 'United Kingdom',
        'br' => 'Brazil',
    ];

    public function handle(): int
    {
        info('Welcome to Lista — interactive setup.');
        note('This command writes to .env and runs migrate + seed. Existing data is preserved (idempotent seeders).');

        $locale = select('Language', self::LOCALES, default: 'en');
        $region = select('Store region', self::REGIONS, default: $this->defaultRegionFor($locale));

        if ($region === 'uk') {
            note('UK uses the English catalog. Store hints in the seeded items point at US chains, so the dropdown will show UK stores but no item will have a preferred-store badge until you customise CatalogItemSeederEn or add your own.');
        }

        $currency = text('Currency symbol', default: $this->defaultCurrencyFor($region));

        $email = text(
            label: 'Admin email',
            default: 'admin@example.com',
            validate: fn (string $v) => filter_var($v, FILTER_VALIDATE_EMAIL) ? null : 'Please enter a valid email address.',
        );

        $name = text('Admin display name', default: 'Admin');

        $userPassword = password(
            label: 'Admin password',
            hint: 'Min 6 characters. Rotate after first login.',
            validate: fn (string $v) => strlen($v) >= 6 ? null : 'Password must be at least 6 characters.',
        );

        $this->renderSummary($locale, $region, $currency, $email, $name);

        if (! confirm('Apply these settings now?', default: true)) {
            warning('Aborted. Nothing changed.');

            return self::FAILURE;
        }

        $this->writeEnv([
            'APP_LOCALE' => $locale,
            'STORES_REGION' => $region,
            'CURRENCY_SYMBOL' => $currency,
        ]);

        // Refresh runtime config so the seeders pick up the new values immediately.
        config([
            'app.locale' => $locale,
            'lista.stores.region' => $region,
            'lista.currency.symbol' => $currency,
        ]);

        app()->setLocale($locale);

        $this->call('migrate', ['--force' => true]);

        // Create or update the admin directly (bypasses AdminUserSeeder so the
        // user gets the credentials they typed, not the dev defaults).
        User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => Hash::make($userPassword),
                'is_admin' => true,
            ]
        );

        $this->call('db:seed', ['--class' => $this->catalogSeederFor($region), '--force' => true]);
        $this->call('db:seed', ['--class' => $this->historySeederFor($region), '--force' => true]);

        info('All set. Run "composer run dev" and visit your local URL to log in.');
        note(sprintf('Login: %s / (the password you just set).', $email));

        return self::SUCCESS;
    }

    private function renderSummary(string $locale, string $region, string $currency, string $email, string $name): void
    {
        note(implode("\n", [
            'About to apply:',
            sprintf('  Language:  %s', self::LOCALES[$locale]),
            sprintf('  Region:    %s', self::REGIONS[$region]),
            sprintf('  Currency:  %s', $currency),
            sprintf('  Owner:     %s <%s>', $name, $email),
            sprintf('  Catalog:   %s', class_basename($this->catalogSeederFor($region))),
            sprintf('  History:   %s', class_basename($this->historySeederFor($region))),
        ]));
    }

    private function defaultRegionFor(string $locale): string
    {
        return match ($locale) {
            'pt_PT' => 'pt',
            'pt_BR' => 'br',
            'en_GB' => 'uk',
            default => 'us',
        };
    }

    private function defaultCurrencyFor(string $region): string
    {
        return match ($region) {
            'pt' => '€',
            'uk' => '£',
            'br' => 'R$',
            default => '$',
        };
    }

    private function catalogSeederFor(string $region): string
    {
        return match ($region) {
            'pt' => 'Database\\Seeders\\CatalogItemSeeder',
            'br' => 'Database\\Seeders\\CatalogItemSeederBr',
            default => 'Database\\Seeders\\CatalogItemSeederEn',
        };
    }

    private function historySeederFor(string $region): string
    {
        return match ($region) {
            'pt' => 'Database\\Seeders\\ShoppingHistorySeeder',
            'br' => 'Database\\Seeders\\ShoppingHistorySeederBr',
            default => 'Database\\Seeders\\ShoppingHistorySeederEn',
        };
    }

    /**
     * Replace or append the given KEY=value pairs in .env, preserving everything else.
     *
     * @param  array<string, string>  $values
     */
    private function writeEnv(array $values): void
    {
        $envPath = base_path('.env');

        if (! file_exists($envPath)) {
            $this->components->error('.env not found. Run `cp .env.example .env && php artisan key:generate` first.');
            throw new \RuntimeException('Missing .env file.');
        }

        $contents = file_get_contents($envPath);

        foreach ($values as $key => $value) {
            $line = sprintf('%s=%s', $key, $this->escapeEnvValue($value));
            $pattern = sprintf('/^%s=.*$/m', preg_quote($key, '/'));

            $contents = preg_match($pattern, $contents)
                ? preg_replace($pattern, $line, $contents)
                : rtrim($contents, "\n")."\n".$line."\n";
        }

        file_put_contents($envPath, $contents);
    }

    private function escapeEnvValue(string $value): string
    {
        if ($value === '' || preg_match('/[\s"\'#]/', $value)) {
            return '"'.str_replace('"', '\\"', $value).'"';
        }

        return $value;
    }
}
