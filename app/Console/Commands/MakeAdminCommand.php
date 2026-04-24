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
use function Laravel\Prompts\text;
use function Laravel\Prompts\warning;

#[Signature('lista:make-admin {email? : Email of the user to create or promote} {--name= : Display name when creating a new user}')]
#[Description('Create a new admin user, or promote an existing user to admin. Prompts for any missing argument.')]
class MakeAdminCommand extends Command
{
    public function handle(): int
    {
        $email = $this->argument('email') ?? text(
            label: 'Admin email',
            validate: fn (string $v) => filter_var($v, FILTER_VALIDATE_EMAIL) ? null : 'Please enter a valid email address.',
        );

        $existing = User::where('email', $email)->first();

        if ($existing !== null) {
            return $this->promote($existing);
        }

        return $this->createNew($email);
    }

    private function promote(User $user): int
    {
        if ($user->isAdmin()) {
            warning(sprintf('%s is already an admin. Nothing to do.', $user->email));

            return self::SUCCESS;
        }

        if (! confirm(sprintf('Promote existing user %s to admin?', $user->email), default: true)) {
            return self::FAILURE;
        }

        $user->update(['is_admin' => true]);
        info(sprintf('%s is now an admin.', $user->email));

        return self::SUCCESS;
    }

    private function createNew(string $email): int
    {
        $name = $this->option('name') ?? text('Display name', default: 'Admin');

        $userPassword = password(
            label: 'Password',
            hint: 'Min 6 characters.',
            validate: fn (string $v) => strlen($v) >= 6 ? null : 'Password must be at least 6 characters.',
        );

        User::create([
            'email' => $email,
            'name' => $name,
            'password' => Hash::make($userPassword),
            'is_admin' => true,
        ]);

        info(sprintf('Created admin %s.', $email));
        note('You can log in immediately with the password you just set.');

        return self::SUCCESS;
    }
}
