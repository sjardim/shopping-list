<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title>{{ $title ?? 'Lista' }}</title>

    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('icons/icon.svg') }}">
    <link rel="apple-touch-icon" href="{{ asset('icons/icon.svg') }}">
    <meta name="theme-color" content="#eae2cf">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="Lista">

    <script>
        (function () {
            try {
                var prefs = JSON.parse(localStorage.getItem('lista-prefs') || '{}');
                var root = document.documentElement;
                root.style.setProperty('--ui-scale', prefs.uiScale || 1);
                root.style.setProperty('--list-scale', prefs.listScale || 1);
                if (prefs.highContrast) {
                    root.setAttribute('data-contrast', 'high');
                }
                if (prefs.bigTargets) {
                    root.setAttribute('data-targets', 'big');
                }
            } catch (e) {}
        })();

        document.addEventListener('alpine:init', function () {
            window.Alpine.store('prefs', {
                uiScale: 1,
                listScale: 1,
                highContrast: false,
                bigTargets: false,
                soundEnabled: true,
                init: function () {
                    try {
                        var saved = JSON.parse(localStorage.getItem('lista-prefs') || '{}');
                        this.uiScale = saved.uiScale || 1;
                        this.listScale = saved.listScale || 1;
                        this.highContrast = !!saved.highContrast;
                        this.bigTargets = !!saved.bigTargets;
                        this.soundEnabled = saved.soundEnabled !== false;
                    } catch (e) {}
                },
                setUiScale: function (value) {
                    this.uiScale = value;
                    this.listScale = value >= 1.2 ? 1.18 : 1;
                    this._apply();
                },
                toggleListScale: function () {
                    this.listScale = this.listScale > 1 ? 1 : 1.18;
                    this._apply();
                },
                toggleHighContrast: function () {
                    this.highContrast = !this.highContrast;
                    this._apply();
                },
                toggleBigTargets: function () {
                    this.bigTargets = !this.bigTargets;
                    this._apply();
                },
                toggleSound: function () {
                    this.soundEnabled = !this.soundEnabled;
                    this._apply();
                },
                _apply: function () {
                    var root = document.documentElement;
                    root.style.setProperty('--ui-scale', this.uiScale);
                    root.style.setProperty('--list-scale', this.listScale);
                    if (this.highContrast) {
                        root.setAttribute('data-contrast', 'high');
                    } else {
                        root.removeAttribute('data-contrast');
                    }
                    if (this.bigTargets) {
                        root.setAttribute('data-targets', 'big');
                    } else {
                        root.removeAttribute('data-targets');
                    }
                    try {
                        localStorage.setItem('lista-prefs', JSON.stringify({
                            uiScale: this.uiScale,
                            listScale: this.listScale,
                            highContrast: this.highContrast,
                            bigTargets: this.bigTargets,
                            soundEnabled: this.soundEnabled,
                        }));
                    } catch (e) {}
                },
            });
        });
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fluxAppearance
</head>
<body class="antialiased">
    <div class="min-h-screen flex flex-col">
        {{ $slot }}
    </div>

    @persist('toast')
        <flux:toast />
    @endpersist

    @fluxScripts
</body>
</html>
