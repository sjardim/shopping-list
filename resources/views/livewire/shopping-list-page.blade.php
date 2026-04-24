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

            {{-- Profile menu (owner) / Share button (shared) --}}
            @if($mode === 'owner')
                <flux:dropdown align="end">
                    <flux:button variant="ghost" size="sm" class="rounded-full! size-9 p-0! shrink-0" aria-label="{{ __('app.open_menu') }}">
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

                        {{-- Share list --}}
                        <flux:menu.item
                            icon="share-network"
                            x-on:click="navigator.clipboard ? navigator.clipboard.writeText('{{ route('list.shared', $shareToken) }}').then(() => $flux.toast({ text: '{{ __('app.link_copied') }}', duration: 8000 })) : $flux.toast({ text: '{{ route('list.shared', $shareToken) }}', duration: 12000 })"
                        >
                            {{ __('app.share_list') }}
                        </flux:menu.item>

                        {{-- Export JSON --}}
                        <flux:menu.item
                            icon="download-simple"
                            href="{{ route('list.export', $shareToken) }}"
                        >
                            {{ __('app.export_json') }}
                        </flux:menu.item>

                        {{-- Print --}}
                        <flux:menu.item
                            icon="printer"
                            href="{{ route('list.print', $shareToken) }}"
                            target="_blank"
                        >
                            {{ __('app.print_list') }}
                        </flux:menu.item>

                        {{-- Save as recipe --}}
                        <flux:modal.trigger name="save-recipe">
                            <flux:menu.item icon="bookmark-simple">
                                {{ __('app.save_as_recipe') }}
                            </flux:menu.item>
                        </flux:modal.trigger>

                        <flux:menu.separator />

                        {{-- Sign out --}}
                        <flux:menu.item
                            icon="sign-out"
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
                <flux:dropdown align="end">
                    <flux:button variant="ghost" size="sm" class="mt-1 rounded-full! size-9 p-0! shrink-0 bg-white border border-[#e0d9cc]" aria-label="{{ __('app.open_settings') }}">
                        <flux:icon name="gear" class="size-4 text-[#6b6055]" />
                    </flux:button>

                    <flux:menu class="min-w-52">
                        {{-- Share --}}
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

            @if($this->totalSpent > 0)
                <p class="mt-3 text-sm text-white/80">
                    <span class="font-semibold text-white">{{ config('lista.currency.symbol') }}{{ number_format($this->totalSpent, 2) }}</span>
                    {{ __('app.spent_so_far') }}
                </p>
            @endif

            @if($mode === 'owner')
                <div class="mt-4 flex gap-2">
                    <button
                        wire:click="finishTrip"
                        wire:confirm="{{ $list->store ? __('app.finish_trip_confirm') : __('app.finish_trip_no_store_confirm') }}"
                        class="flex-1 bg-white text-[#1a1a1a] rounded-full py-2.5 text-sm font-semibold flex items-center justify-center gap-1.5 tap"
                    >
                        <flux:icon name="check" class="size-4" />
                        {{ __('app.finish_trip') }}
                    </button>
                    <button
                        wire:click="clearList"
                        wire:confirm="{{ __('app.clear_confirm') }}"
                        class="bg-white/20 text-white rounded-full px-5 py-2.5 text-sm font-semibold flex items-center gap-1.5 hover:bg-white/30 transition-colors tap"
                    >
                        <flux:icon name="trash" class="size-4" />
                        {{ __('app.clear') }}
                    </button>
                </div>
            @endif
        </div>
    @endif

    {{-- Notes for this trip --}}
    <div class="px-5 mt-3">
        <div
            x-data="{
                ...voiceInput('{{ str_replace('_', '-', app()->getLocale()) }}'),
                saved: false,
                savedTimer: null,
                showSaved() {
                    this.saved = true;
                    clearTimeout(this.savedTimer);
                    this.savedTimer = setTimeout(() => { this.saved = false; }, 2000);
                },
            }"
            x-on:notes-saved.window="showSaved()"
            class="relative"
        >
            <textarea
                wire:model.live.debounce.500ms="notes"
                wire:change="updateNotes"
                placeholder="{{ __('app.notes_placeholder') }}"
                rows="2"
                class="w-full bg-white/60 rounded-2xl pl-4 pr-12 py-2 text-sm text-[#1a1a1a] placeholder-[#b0a99a] outline-none resize-none border border-[#ede8df] focus:border-[#2f7d4f] transition-colors"
            ></textarea>
            <span
                x-cloak
                x-show="saved"
                x-transition.opacity
                class="absolute top-2 right-12 inline-flex items-center gap-1 text-[11px] font-semibold text-[#2f7d4f] bg-[#e3ede7] rounded-full px-2 py-0.5 pointer-events-none"
            >
                <flux:icon name="check" class="size-3" />
                {{ __('app.saved_badge') }}
            </span>
            <button
                type="button"
                x-cloak
                x-show="supported"
                x-on:click="toggle"
                x-bind:class="recording ? 'bg-[#e53935] text-white animate-pulse' : 'bg-[#f4f0e8] text-[#1a1a1a] hover:bg-[#ede8df]'"
                class="absolute bottom-2 right-2 size-7 rounded-lg flex items-center justify-center transition-colors tap"
                aria-label="{{ __('app.start_voice_input') }}"
                data-voice-toggle
            >
                <flux:icon name="microphone" class="size-3.5" />
            </button>
        </div>
    </div>

    {{-- List body --}}
    <main class="flex-1 px-5 pb-32 mt-3 space-y-6">

        @if($total === 0)
            <div class="mt-16 text-center text-[#6b6055] fade-in-up">
                <p class="text-4xl mb-3">🛒</p>
                <p class="text-base font-medium text-[#1a1a1a]">{{ __('app.list_empty') }}</p>
                <p class="text-sm mt-1">{{ __('app.tap_to_add') }}</p>

                @if($mode === 'owner' && $this->recentlyFinishedList)
                    <button
                        wire:click="undoFinishTrip"
                        class="tap mt-6 inline-flex items-center gap-2 bg-[#1a1a1a] text-white rounded-full px-5 py-2.5 text-sm font-semibold"
                    >
                        <flux:icon name="arrow-bend-up-left" class="size-4" />
                        {{ __('app.undo_finish_trip') }}
                    </button>
                @endif
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
                            @php $storeEnum = $item['preferred_store'] ? \App\Support\Stores::tryFrom($item['preferred_store']) : null; @endphp
                            <div
                                wire:key="item-{{ $item['id'] }}"
                                x-data="{ sliding: false }"
                                x-bind:class="sliding && '-translate-x-full opacity-0 bg-stone-100'"
                                class="flex items-center gap-3 px-4 py-3.5 fade-in-up transition-all duration-200 ease-out"
                            >
                                {{-- Square checkbox --}}
                                <button
                                    x-on:click="navigator.vibrate?.(8); window.lista?.sounds?.toggle(); sliding = true; setTimeout(() => $wire.toggleItem({{ $item['id'] }}), 200)"
                                    class="shrink-0 size-6 rounded-lg border-2 border-[#d5cdbc] hover:border-[#2f7d4f] transition-colors tap"
                                    aria-label="{{ __('app.mark_bought', ['name' => $item['name']]) }}"
                                ></button>

                                {{-- Emoji container --}}
                                <div class="shrink-0 size-10 rounded-xl bg-[#f4f0e8] flex items-center justify-center text-lg">
                                    {{ $item['emoji'] ?: '🛒' }}
                                </div>

                                {{-- Name + store --}}
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-[#1a1a1a] list-text">{{ $item['name'] }}</p>
                                    @if($storeEnum)
                                        <p class="flex items-center gap-1 text-[#6b6055] mt-0.5 list-text-sm">
                                            <span class="size-2 rounded-full shrink-0" style="background-color: {{ $storeEnum->color() }}"></span>
                                            {{ __('app.usually', ['store' => $storeEnum->label()]) }}
                                        </p>
                                    @else
                                        <p class="text-[#9b9080] mt-0.5 list-text-sm">
                                            {{ rtrim(rtrim(number_format((float)$item['quantity'], 2, '.', ''), '0'), '.') }} {{ $item['unit'] }}
                                        </p>
                                    @endif
                                </div>

                                {{-- Price + remove (owner only) --}}
                                @if($mode === 'owner')
                                    <button
                                        type="button"
                                        wire:click="openPriceEditor({{ $item['id'] }})"
                                        class="shrink-0 text-xs font-semibold tap rounded-full px-2.5 py-1 transition-colors {{ $item['price'] ? 'bg-[#e3ede7] text-[#2f7d4f]' : 'bg-[#f4f0e8] text-[#9b9080]' }}"
                                        aria-label="{{ __('app.set_price', ['name' => $item['name']]) }}"
                                    >
                                        @if($item['price'])
                                            {{ config('lista.currency.symbol') }}{{ number_format((float) $item['price'], 2) }}
                                        @else
                                            +{{ config('lista.currency.symbol') }}
                                        @endif
                                    </button>
                                    <button
                                        wire:click="removeItem({{ $item['id'] }})"
                                        wire:confirm="{{ __('app.remove_confirm', ['name' => $item['name']]) }}"
                                        class="shrink-0 size-7 rounded-full flex items-center justify-center text-[#c5bdb0] hover:text-[#e53935] hover:bg-red-50 transition-colors tap"
                                        aria-label="{{ __('app.remove_item', ['name' => $item['name']]) }}"
                                    >
                                        <flux:icon name="x" class="size-3.5" />
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
                            <div
                                wire:key="bought-{{ $item['id'] }}"
                                x-data="{ sliding: false }"
                                x-bind:class="sliding && '-translate-x-full opacity-0 bg-stone-100'"
                                class="flex items-center gap-3 px-4 py-3.5 fade-in-up transition-all duration-200 ease-out"
                            >
                                {{-- Filled checkbox --}}
                                <button
                                    x-on:click="navigator.vibrate?.(8); window.lista?.sounds?.toggle(); sliding = true; setTimeout(() => $wire.toggleItem({{ $item['id'] }}), 200)"
                                    class="shrink-0 size-6 rounded-lg bg-[#2f7d4f] flex items-center justify-center hover:bg-[#256b41] transition-colors tap"
                                    aria-label="{{ __('app.mark_unbought', ['name' => $item['name']]) }}"
                                >
                                    <flux:icon name="check" class="size-3.5 text-white" />
                                </button>

                                {{-- Emoji --}}
                                <div class="shrink-0 size-10 rounded-xl bg-[#f4f0e8]/60 flex items-center justify-center text-lg opacity-50">
                                    {{ $item['emoji'] ?: '🛒' }}
                                </div>

                                <p class="flex-1 font-semibold text-[#9b9080] line-through list-text">{{ $item['name'] }}</p>

                                @if($mode === 'owner')
                                    <button
                                        type="button"
                                        wire:click="openPriceEditor({{ $item['id'] }})"
                                        class="shrink-0 text-xs font-semibold tap rounded-full px-2.5 py-1 transition-colors {{ $item['price'] ? 'bg-[#e3ede7] text-[#2f7d4f]' : 'bg-[#f4f0e8] text-[#9b9080]' }}"
                                        aria-label="{{ __('app.set_price', ['name' => $item['name']]) }}"
                                    >
                                        @if($item['price'])
                                            {{ config('lista.currency.symbol') }}{{ number_format((float) $item['price'], 2) }}
                                        @else
                                            +{{ config('lista.currency.symbol') }}
                                        @endif
                                    </button>
                                @endif
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
            class="fixed bottom-[4.5rem] left-0 right-0 px-5 pb-3"
            x-data="{ focused: false }"
        >
            {{-- Catalog suggestions --}}
            @if(count($this->catalogSuggestions) > 0)
                <div
                    x-cloak
                    x-show="focused"
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="opacity-0 translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="mb-2 bg-white rounded-2xl shadow-lg border border-[#ede8df] overflow-hidden"
                >
                    @foreach($this->catalogSuggestions as $suggestion)
                        @php $storeEnum = $suggestion['preferred_store'] ? \App\Support\Stores::tryFrom($suggestion['preferred_store']) : null; @endphp
                        <button
                            wire:key="suggestion-{{ $suggestion['id'] }}"
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

            <form
                wire:submit="quickAdd"
                x-data="voiceInput('{{ str_replace('_', '-', app()->getLocale()) }}')"
                class="flex gap-2 bg-white rounded-2xl shadow-lg shadow-black/5 border border-[#ede8df] px-4 py-2"
            >
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
                    type="button"
                    x-cloak
                    x-show="supported"
                    x-on:click="toggle"
                    x-bind:class="recording ? 'bg-[#e53935] text-white animate-pulse' : 'bg-[#f4f0e8] text-[#1a1a1a] hover:bg-[#ede8df]'"
                    class="shrink-0 size-8 rounded-xl flex items-center justify-center transition-colors tap"
                    aria-label="{{ __('app.start_voice_input') }}"
                    data-voice-toggle
                >
                    <flux:icon name="microphone" class="size-4" />
                </button>
                <button
                    type="submit"
                    class="shrink-0 size-8 rounded-xl bg-[#1a1a1a] text-white flex items-center justify-center hover:bg-[#333] transition-colors tap"
                    aria-label="{{ __('app.add_item') }}"
                >
                    <flux:icon name="plus" class="size-4" />
                </button>
            </form>
            @error('quickAddName')
                <p role="alert" class="text-xs text-red-500 mt-1 px-1">{{ $message }}</p>
            @enderror
        </div>
    @endif

    {{-- Bottom nav (owner only) --}}
    @if($mode === 'owner')
        <x-bottom-nav active-tab="list" :item-count="$pending ?? 0" />
    @endif

    {{-- Price editor with history (owner only) --}}
    @if($mode === 'owner')
        <flux:modal name="edit-price" class="md:w-96">
            @php $editing = $this->editingItem; @endphp
            @if($editing)
                <form wire:submit="submitPrice" class="space-y-5">
                    <div>
                        <flux:heading size="lg" class="flex items-center gap-2">
                            <span>{{ $editing->emoji ?: '🛒' }}</span>
                            <span>{{ $editing->name }}</span>
                        </flux:heading>
                        <flux:subheading>{{ __('app.set_price_prompt', ['name' => $editing->name, 'currency' => config('lista.currency.symbol')]) }}</flux:subheading>
                    </div>

                    <flux:field>
                        <flux:label>{{ __('app.price_label', ['currency' => config('lista.currency.symbol')]) }}</flux:label>
                        <flux:input wire:model="editingPrice" type="text" inputmode="decimal" autofocus placeholder="0.00" />
                    </flux:field>

                    @if($this->priceHistory->isNotEmpty())
                        <div class="space-y-2">
                            <p class="text-[10px] font-bold uppercase tracking-widest text-[#6b6055]">{{ __('app.price_history') }}</p>
                            <div class="rounded-xl border border-[#ede8df] divide-y divide-[#f4f0e8] overflow-hidden">
                                @foreach($this->priceHistory as $entry)
                                    @php $storeEnum = $entry->store ? \App\Support\Stores::tryFrom($entry->store) : null; @endphp
                                    <div class="flex items-center justify-between gap-3 px-3 py-2 text-sm">
                                        <span class="flex items-center gap-2 min-w-0">
                                            @if($storeEnum)
                                                <x-store-badge :store="$storeEnum" />
                                            @else
                                                <span class="size-5"></span>
                                            @endif
                                            <span class="text-[#1a1a1a] truncate">{{ $storeEnum?->label() ?? __('app.no_store') }}</span>
                                        </span>
                                        <span class="flex items-center gap-3 shrink-0">
                                            <span class="text-xs text-[#6b6055]">{{ $entry->bought_at->format('d M Y') }}</span>
                                            <span class="font-semibold text-[#2f7d4f]">{{ config('lista.currency.symbol') }}{{ number_format($entry->price, 2) }}</span>
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @elseif($editing->catalog_item_id !== null)
                        <p class="text-xs text-[#6b6055] italic">{{ __('app.no_price_history') }}</p>
                    @endif

                    <div class="flex justify-end gap-2">
                        <flux:modal.close>
                            <flux:button variant="ghost">{{ __('app.cancel') }}</flux:button>
                        </flux:modal.close>
                        <flux:button type="submit" variant="primary">{{ __('app.save') }}</flux:button>
                    </div>
                </form>
            @endif
        </flux:modal>
    @endif

    {{-- Save as recipe modal (owner only) --}}
    @if($mode === 'owner')
        <flux:modal name="save-recipe" class="md:w-96">
            <form wire:submit="saveAsRecipe" class="space-y-5">
                <div>
                    <flux:heading size="lg">{{ __('app.save_as_recipe') }}</flux:heading>
                    <flux:subheading>{{ __('app.save_as_recipe_hint') }}</flux:subheading>
                </div>

                <flux:field>
                    <flux:label>{{ __('app.recipe_name') }}</flux:label>
                    <flux:input wire:model="newRecipeName" autofocus />
                    <flux:error name="newRecipeName" />
                </flux:field>

                <flux:field>
                    <flux:label>{{ __('app.recipe_emoji') }}</flux:label>
                    <flux:input wire:model="newRecipeEmoji" maxlength="8" class="!text-2xl !text-center" />
                    <flux:error name="newRecipeEmoji" />
                </flux:field>

                <div class="flex justify-end gap-2">
                    <flux:modal.close>
                        <flux:button variant="ghost">{{ __('app.cancel') }}</flux:button>
                    </flux:modal.close>
                    <flux:button type="submit" variant="primary">{{ __('app.save') }}</flux:button>
                </div>
            </form>
        </flux:modal>
    @endif

</div>
