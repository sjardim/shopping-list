<div class="flex flex-col min-h-screen">

    {{-- Header --}}
    <header class="px-5 pt-10 pb-4">
        <div class="flex items-start justify-between">
            <div>
                <h1 class="heading-serif text-3xl font-bold text-[#1a1a1a] leading-tight">{{ __('app.shopping_list') }}</h1>

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
                                icon:trailing="chevron-down"
                            >{{ $list->store?->label() ?? __('app.choose_store') }}</flux:button>

                            <flux:menu>
                                <flux:menu.item wire:click="updateStore('')">
                                    {{ __('app.no_store') }}
                                </flux:menu.item>
                                <flux:menu.separator />
                                @foreach(\App\Enums\Store::cases() as $store)
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

            {{-- Profile menu (owner) / Share button (shared) --}}
            @if($mode === 'owner')
                <flux:dropdown align="end">
                    <flux:button variant="ghost" size="sm" class="rounded-full! size-9 p-0! shrink-0">
                        <span class="size-8 rounded-full bg-[#1a1a1a] text-white text-sm font-bold flex items-center justify-center">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </span>
                    </flux:button>

                    <flux:menu class="min-w-52">
                        {{-- User info --}}
                        <div class="px-3 py-2">
                            <p class="text-sm font-semibold text-[#1a1a1a]">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-[#6b6055] truncate">{{ auth()->user()->email }}</p>
                        </div>

                        <flux:menu.separator />

                        {{-- Language --}}
                        <div class="px-3 py-1.5">
                            <p class="text-[10px] font-bold uppercase tracking-widest text-[#6b6055] mb-1">{{ __('app.language') }}</p>
                        </div>
                        <flux:menu.item wire:click="switchLocale('pt_PT')" icon="{{ $locale === 'pt_PT' ? 'check' : '' }}">
                            Português
                        </flux:menu.item>
                        <flux:menu.item wire:click="switchLocale('en')" icon="{{ $locale === 'en' ? 'check' : '' }}">
                            English
                        </flux:menu.item>

                        <flux:menu.separator />

                        {{-- Share list --}}
                        <flux:menu.item
                            icon="share"
                            x-on:click="navigator.clipboard ? navigator.clipboard.writeText('{{ route('list.shared', $shareToken) }}').then(() => $flux.toast('{{ __('app.link_copied') }}')) : $flux.toast('{{ route('list.shared', $shareToken) }}')"
                        >
                            {{ __('app.share_list') }}
                        </flux:menu.item>

                        <flux:menu.separator />

                        {{-- Sign out --}}
                        <flux:menu.item
                            icon="arrow-right-start-on-rectangle"
                            x-on:click="document.getElementById('logout-form').submit()"
                        >
                            {{ __('app.sign_out') }}
                        </flux:menu.item>
                    </flux:menu>
                </flux:dropdown>

                <form id="logout-form" method="POST" action="{{ route('logout') }}" class="hidden">
                    @csrf
                </form>
            @else
                <button
                    class="mt-1 size-9 rounded-full bg-white border border-[#e0d9cc] flex items-center justify-center text-[#6b6055] hover:text-[#1a1a1a] transition-colors"
                    x-on:click="navigator.clipboard.writeText('{{ route('list.shared', $shareToken) }}').then(() => $flux.toast('{{ __('app.link_copied') }}'))"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 1 0 0 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186 9.566-5.314m-9.566 7.5 9.566 5.314m0 0a2.25 2.25 0 1 0 3.935 2.186 2.25 2.25 0 0 0-3.935-2.186Zm0-12.814a2.25 2.25 0 1 0 3.933-2.185 2.25 2.25 0 0 0-3.933 2.185Z" />
                    </svg>
                </button>
            @endif
        </div>
    </header>

    {{-- Progress card (only when there are items) --}}
    @php
        $total = $list->totalCount();
        $pending = $list->pendingCount();
        $bought = $total - $pending;
        $progress = $total > 0 ? round(($bought / $total) * 100) : 0;
    @endphp

    @if($total > 0)
        <div class="mx-5 rounded-3xl p-5" style="background-color: #2f7d4f;">
            <p class="text-[10px] font-bold uppercase tracking-widest text-white/60">{{ __('app.shopping_now') }}</p>

            <div class="flex items-baseline gap-2 mt-1">
                <span class="text-6xl font-bold text-white leading-none">{{ $pending }}</span>
                <span class="text-white/80 text-base">{{ __('app.remaining', ['total' => $total]) }}</span>
            </div>

            <div class="mt-4 h-1.5 bg-white/20 rounded-full overflow-hidden">
                <div class="h-full bg-white rounded-full transition-all duration-300" style="width: {{ $progress }}%"></div>
            </div>

            @if($mode === 'owner')
                <div class="mt-4 flex gap-2">
                    <button
                        wire:click="finishTrip"
                        wire:confirm="{{ __('app.finish_trip_confirm') }}"
                        class="flex-1 bg-white text-[#1a1a1a] rounded-full py-2.5 text-sm font-semibold flex items-center justify-center gap-1.5"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                        </svg>
                        {{ __('app.finish_trip') }}
                    </button>
                    <button
                        wire:click="clearList"
                        wire:confirm="{{ __('app.clear_confirm') }}"
                        class="bg-white/20 text-white rounded-full px-5 py-2.5 text-sm font-semibold flex items-center gap-1.5 hover:bg-white/30 transition-colors"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                        </svg>
                        {{ __('app.clear') }}
                    </button>
                </div>
            @endif
        </div>
    @endif

    {{-- List body --}}
    <main class="flex-1 px-5 pb-32 mt-5 space-y-6">

        @if($total === 0)
            <div class="mt-16 text-center text-[#6b6055]">
                <p class="text-4xl mb-3">🛒</p>
                <p class="text-base font-medium text-[#1a1a1a]">{{ __('app.list_empty') }}</p>
                <p class="text-sm mt-1">{{ __('app.tap_to_add') }}</p>
            </div>
        @else
            {{-- Pending items grouped by category --}}
            @foreach($this->itemsByCategory['pending'] as $category => $items)
                @php $categoryEnum = \App\Enums\Category::tryFrom($category); @endphp
                <section>
                    <h2 class="flex items-center gap-1.5 text-[11px] font-bold text-[#6b6055] uppercase tracking-widest mb-3">
                        <span>{{ $categoryEnum?->emoji() }}</span>
                        <span>{{ $categoryEnum?->label() ?? $category }}</span>
                    </h2>
                    <div class="bg-white rounded-3xl overflow-hidden divide-y divide-[#f4f0e8]">
                        @foreach($items as $item)
                            @php $storeEnum = $item['preferred_store'] ? \App\Enums\Store::tryFrom($item['preferred_store']) : null; @endphp
                            <div wire:key="item-{{ $item['id'] }}" class="flex items-center gap-3 px-4 py-3.5">
                                {{-- Square checkbox --}}
                                <button
                                    wire:click="toggleItem({{ $item['id'] }})"
                                    class="shrink-0 size-6 rounded-lg border-2 border-[#d5cdbc] hover:border-[#2f7d4f] transition-colors"
                                ></button>

                                {{-- Emoji container --}}
                                <div class="shrink-0 size-10 rounded-xl bg-[#f4f0e8] flex items-center justify-center text-lg">
                                    {{ $item['emoji'] ?: '🛒' }}
                                </div>

                                {{-- Name + store --}}
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-[#1a1a1a] text-[15px]">{{ $item['name'] }}</p>
                                    @if($storeEnum)
                                        <p class="flex items-center gap-1 text-xs text-[#6b6055] mt-0.5">
                                            <span class="size-2 rounded-full shrink-0" style="background-color: {{ $storeEnum->color() }}"></span>
                                            {{ __('app.usually', ['store' => $storeEnum->label()]) }}
                                        </p>
                                    @else
                                        <p class="text-xs text-[#9b9080] mt-0.5">
                                            {{ rtrim(rtrim(number_format((float)$item['quantity'], 2, '.', ''), '0'), '.') }} {{ $item['unit'] }}
                                        </p>
                                    @endif
                                </div>

                                {{-- Remove (owner only) --}}
                                @if($mode === 'owner')
                                    <button
                                        wire:click="removeItem({{ $item['id'] }})"
                                        wire:confirm="{{ __('app.remove_confirm', ['name' => $item['name']]) }}"
                                        class="shrink-0 size-7 rounded-full flex items-center justify-center text-[#c5bdb0] hover:text-[#e53935] hover:bg-red-50 transition-colors"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </section>
            @endforeach

            {{-- Bought items --}}
            @if(count($this->itemsByCategory['bought']) > 0)
                <section>
                    <h2 class="flex items-center gap-1.5 text-[11px] font-bold text-[#6b6055] uppercase tracking-widest mb-3">
                        <span>✓</span>
                        <span>{{ __('app.in_cart') }}</span>
                    </h2>
                    <div class="bg-white/60 rounded-3xl overflow-hidden divide-y divide-[#f4f0e8]">
                        @foreach($this->itemsByCategory['bought'] as $item)
                            <div wire:key="bought-{{ $item['id'] }}" class="flex items-center gap-3 px-4 py-3.5">
                                {{-- Filled checkbox --}}
                                <button
                                    wire:click="toggleItem({{ $item['id'] }})"
                                    class="shrink-0 size-6 rounded-lg bg-[#2f7d4f] flex items-center justify-center hover:bg-[#256b41] transition-colors"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                    </svg>
                                </button>

                                {{-- Emoji --}}
                                <div class="shrink-0 size-10 rounded-xl bg-[#f4f0e8]/60 flex items-center justify-center text-lg opacity-50">
                                    {{ $item['emoji'] ?: '🛒' }}
                                </div>

                                <p class="flex-1 font-semibold text-[15px] text-[#9b9080] line-through">{{ $item['name'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif
        @endif
    </main>

    {{-- Quick-add form (owner only) --}}
    @if($mode === 'owner')
        <div
            class="fixed bottom-[73px] left-0 right-0 px-5 pb-3"
            x-data="{ focused: false }"
        >
            {{-- Catalog suggestions --}}
            @if(count($this->catalogSuggestions) > 0)
                <div
                    x-show="focused"
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="opacity-0 translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="mb-2 bg-white rounded-2xl shadow-lg border border-[#ede8df] overflow-hidden"
                >
                    @foreach($this->catalogSuggestions as $suggestion)
                        @php $storeEnum = $suggestion['preferred_store'] ? \App\Enums\Store::tryFrom($suggestion['preferred_store']) : null; @endphp
                        <button
                            wire:click="selectCatalogSuggestion({{ $suggestion['id'] }})"
                            class="w-full flex items-center gap-3 px-4 py-3 text-left hover:bg-[#f7f3ec] active:bg-[#f0ece3] transition-colors border-b border-[#f4f0e8] last:border-0"
                        >
                            <span class="text-xl shrink-0">{{ $suggestion['emoji'] ?: '🛒' }}</span>
                            <span class="flex-1 text-sm font-medium text-[#1a1a1a]">{{ $suggestion['name'] }}</span>
                            @if($storeEnum)
                                <span class="size-2 rounded-full shrink-0" style="background-color: {{ $storeEnum->color() }}"></span>
                            @endif
                        </button>
                    @endforeach
                </div>
            @endif

            <form wire:submit="quickAdd" class="flex gap-2 bg-white rounded-2xl shadow-lg shadow-black/5 border border-[#ede8df] px-4 py-2">
                <input
                    wire:model.live.debounce.300ms="quickAddName"
                    type="text"
                    placeholder="{{ __('app.add_item_placeholder') }}"
                    autocomplete="off"
                    class="flex-1 bg-transparent text-[#1a1a1a] placeholder-[#b0a99a] text-sm outline-none"
                    x-on:focus="focused = true"
                    x-on:blur.debounce.200ms="focused = false"
                />
                <button
                    type="submit"
                    class="shrink-0 size-8 rounded-xl bg-[#1a1a1a] text-white flex items-center justify-center hover:bg-[#333] transition-colors"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                </button>
            </form>
            @error('quickAddName')
                <p class="text-xs text-red-500 mt-1 px-1">{{ $message }}</p>
            @enderror
        </div>
    @endif

    {{-- Bottom nav (owner only) --}}
    @if($mode === 'owner')
        <x-bottom-nav active-tab="list" :item-count="$pending ?? 0" />
    @endif

</div>
