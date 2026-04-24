<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Creates the single owner account using the OWNER_* env vars.
 * Idempotent: re-running does not overwrite an existing user.
 */
class OwnerUserSeeder extends Seeder
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
    }
}
