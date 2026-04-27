@props([
    'list',
    'mode',
    'locale',
    'shareToken',
])

<header class="px-5 pt-10 pb-4">
    <div class="flex items-start justify-between">
        <div>
            <h1 class="heading-serif text-2xl font-bold text-[#1a1a1a] leading-tight">{{ __('app.shopping_list') }}</h1>

            {{-- Store selector --}}
            <div class="flex items-center gap-2 mt-2">
                <span class="text-sm text-[#6b6055] font-medium">{{ __('app.at') }}</span>

                @if($mode === 'owner')
                    <flux:dropdown>
                        <flux:button
                            size="sm"
                            :style="$list->store
                                ? 'background-color:' . $list->store->color() . '; color:' . ($list->store->hasDarkText() ? '#1a1a1a' : '#ffffff') . '; border-color: transparent'
                                : ''"
                            class="rounded-full! font-semibold gap-1"
                            icon:trailing="caret-down"
                        >{{ $list->store?->label() ?? __('app.choose_store') }}</flux:button>

                        <flux:menu>
                            <flux:menu.item wire:click="updateStore('')">
                                {{ __('app.no_store') }}
                            </flux:menu.item>
                            <flux:menu.separator />
                            @foreach(\App\Support\Stores::active() as $store)
                                <flux:menu.item wire:click="updateStore('{{ $store->value }}')">
                                    <span class="flex items-center gap-2">
                                        <x-store-badge :store="$store" />
                                        {{ $store->label() }}
                                    </span>
                                </flux:menu.item>
                            @endforeach
                        </flux:menu>
                    </flux:dropdown>
                @elseif($list->store)
                    <span
                        class="rounded-full px-4 py-1.5 text-sm font-semibold"
                        style="background-color:{{ $list->store->color() }}; color:{{ $list->store->hasDarkText() ? '#1a1a1a' : '#ffffff' }}"
                    >
                        {{ $list->store->label() }}
                    </span>
                @endif
            </div>
        </div>

        @if($mode === 'owner')
            <flux:dropdown align="end">
                <flux:button variant="ghost" size="sm" class="rounded-full! size-9 p-0! shrink-0" aria-label="{{ __('app.open_menu') }}">
                    <span class="size-8 rounded-full bg-[#1a1a1a] text-white text-sm font-bold flex items-center justify-center">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </span>
                </flux:button>

                <flux:menu class="min-w-52">
                    <div class="px-3 py-2">
                        <p class="text-sm font-semibold text-[#1a1a1a]">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-[#6b6055] truncate">{{ auth()->user()->email }}</p>
                    </div>

                    <flux:menu.separator />

                    <div class="px-3 py-1.5">
                        <p class="text-[10px] font-bold uppercase tracking-widest text-[#6b6055] mb-1">{{ __('app.language') }}</p>
                    </div>
                    <flux:menu.item wire:click="switchLocale('pt_PT')" icon="{{ $locale === 'pt_PT' ? 'check' : '' }}">
                        🇵🇹 Português (Portugal)
                    </flux:menu.item>
                    <flux:menu.item wire:click="switchLocale('pt_BR')" icon="{{ $locale === 'pt_BR' ? 'check' : '' }}">
                        🇧🇷 Português (Brasil)
                    </flux:menu.item>
                    <flux:menu.item wire:click="switchLocale('en')" icon="{{ $locale === 'en' ? 'check' : '' }}">
                        🇺🇸 English
                    </flux:menu.item>
                    <flux:menu.item wire:click="switchLocale('en_GB')" icon="{{ $locale === 'en_GB' ? 'check' : '' }}">
                        🇬🇧 English (UK)
                    </flux:menu.item>
                    <flux:menu.item wire:click="switchLocale('es')" icon="{{ $locale === 'es' ? 'check' : '' }}">
                        🇪🇸 Español
                    </flux:menu.item>

                    <flux:menu.separator />

                    <x-text-size-controls />

                    <flux:menu.separator />

                    <flux:menu.item
                        icon="share-network"
                        x-on:click="navigator.clipboard ? navigator.clipboard.writeText('{{ route('list.shared', $shareToken) }}').then(() => $flux.toast({ text: '{{ __('app.link_copied') }}', duration: 8000 })) : $flux.toast({ text: '{{ route('list.shared', $shareToken) }}', duration: 12000 })"
                    >
                        {{ __('app.share_list') }}
                    </flux:menu.item>

                    <flux:menu.item icon="download-simple" href="{{ route('list.export', $shareToken) }}">
                        {{ __('app.export_json') }}
                    </flux:menu.item>

                    <flux:menu.item icon="printer" href="{{ route('list.print', $shareToken) }}" target="_blank">
                        {{ __('app.print_list') }}
                    </flux:menu.item>

                    <flux:modal.trigger name="save-recipe">
                        <flux:menu.item icon="bookmark-simple">
                            {{ __('app.save_as_recipe') }}
                        </flux:menu.item>
                    </flux:modal.trigger>

                    <flux:menu.separator />

                    <flux:menu.item icon="sign-out" x-on:click="document.getElementById('logout-form').submit()">
                        {{ __('app.sign_out') }}
                    </flux:menu.item>
                </flux:menu>
            </flux:dropdown>

            <form id="logout-form" method="POST" action="{{ route('logout') }}" class="hidden">
                @csrf
            </form>
        @else
            <flux:dropdown align="end">
                <flux:button variant="ghost" size="sm" class="mt-1 rounded-full! size-9 p-0! shrink-0 bg-white border border-[#e0d9cc]" aria-label="{{ __('app.open_settings') }}">
                    <flux:icon name="gear" class="size-4 text-[#6b6055]" />
                </flux:button>

                <flux:menu class="min-w-52">
                    <flux:menu.item
                        icon="share-network"
                        x-on:click="navigator.clipboard ? navigator.clipboard.writeText('{{ route('list.shared', $shareToken) }}').then(() => $flux.toast({ text: '{{ __('app.link_copied') }}', duration: 8000 })) : $flux.toast({ text: '{{ route('list.shared', $shareToken) }}', duration: 12000 })"
                    >
                        {{ __('app.share_list') }}
                    </flux:menu.item>

                    <flux:menu.separator />

                    <x-text-size-controls />
                </flux:menu>
            </flux:dropdown>
        @endif
    </div>
</header>
