<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Creates a default admin account for fresh installs run via `db:seed`.
 *
 * For production / interactive setup use `php artisan lista:install`
 * or `php artisan lista:make-admin` to set real credentials. Both
 * commands bypass this seeder and write directly to the users table.
 *
 * Idempotent: re-running does not overwrite an existing user.
 */
class AdminUserSeeder extends Seeder
{
    public const DEFAULT_EMAIL = 'admin@example.com';

    public const DEFAULT_NAME = 'Admin';

    public const DEFAULT_PASSWORD = 'password';

    public function run(): void
    {
        User::firstOrCreate(
            ['email' => self::DEFAULT_EMAIL],
            [
                'name' => self::DEFAULT_NAME,
                'password' => Hash::make(self::DEFAULT_PASSWORD),
                'is_admin' => true,
            ]
        );
    }
}
