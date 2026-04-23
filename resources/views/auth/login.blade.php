<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Lista</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased">
    <div class="flex min-h-screen flex-col items-center justify-center px-4">
        <div class="w-full max-w-sm">
            {{-- Heading --}}
            <div class="mb-8 text-center">
                <h1 class="heading-serif text-3xl font-semibold text-[#1a1a1a]">Lista</h1>
                <p class="mt-1 text-sm text-[#6b6055]">{{ __('app.sign_in_heading') }}</p>
            </div>

            {{-- Card --}}
            <flux:card class="p-6">
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="space-y-4">
                        <flux:field>
                            <flux:label>{{ __('app.email') }}</flux:label>
                            <flux:input
                                type="email"
                                name="email"
                                :value="old('email')"
                                required
                                autofocus
                                autocomplete="email"
                                placeholder="you@example.com"
                            />
                            <flux:error name="email" />
                        </flux:field>

                        <flux:field>
                            <flux:label>{{ __('app.password') }}</flux:label>
                            <flux:input
                                type="password"
                                name="password"
                                required
                                autocomplete="current-password"
                                placeholder="••••••••"
                            />
                            <flux:error name="password" />
                        </flux:field>

                        <div class="flex items-center">
                            <flux:checkbox name="remember" :label="__('app.remember_me')" />
                        </div>

                        <flux:button type="submit" variant="primary" class="w-full">
                            {{ __('app.sign_in') }}
                        </flux:button>
                    </div>
                </form>
            </flux:card>
        </div>
    </div>

    @fluxScripts
</body>
</html>
