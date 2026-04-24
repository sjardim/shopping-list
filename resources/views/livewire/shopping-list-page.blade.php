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
                            Português
                        </flux:menu.item>
                        <flux:menu.item wire:click="switchLocale('en')" icon="{{ $locale === 'en' ? 'check' : '' }}">
                            English
                        </flux:menu.item>

                        <flux:menu.separator />

                        <x-text-size-controls />

                        <flux:menu.separator />

                        {{-- Share list --}}
                        <flux:menu.item
                            icon="share"
                            x-on:click="navigator.clipboard ? navigator.clipboard.writeText('{{ route('list.shared', $shareToken) }}').then(() => $flux.toast({ text: '{{ __('app.link_copied') }}', duration: 8000 })) : $flux.toast({ text: '{{ route('list.shared', $shareToken) }}', duration: 12000 })"
                        >
                            {{ __('app.share_list') }}
                        </flux:menu.item>

                        {{-- Export JSON --}}
                        <flux:menu.item
                            icon="arrow-down-tray"
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
                            <flux:menu.item icon="bookmark-square">
                                {{ __('app.save_as_recipe') }}
                            </flux:menu.item>
                        </flux:modal.trigger>

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
                <flux:dropdown align="end">
                    <flux:button variant="ghost" size="sm" class="mt-1 rounded-full! size-9 p-0! shrink-0 bg-white border border-[#e0d9cc]" aria-label="{{ __('app.open_settings') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-[#6b6055]" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.343 3.94c.09-.542.56-.94 1.11-.94h1.093c.55 0 1.02.398 1.11.94l.149.894c.07.424.384.764.78.93.398.164.855.142 1.205-.108l.737-.527a1.125 1.125 0 0 1 1.45.12l.773.774c.39.389.44 1.002.12 1.45l-.527.737c-.25.35-.272.806-.107 1.204.165.397.505.71.93.78l.893.15c.543.09.94.559.94 1.109v1.094c0 .55-.397 1.02-.94 1.11l-.894.149c-.424.07-.764.383-.929.78-.165.398-.143.854.107 1.204l.527.738c.32.447.269 1.06-.12 1.45l-.774.773a1.125 1.125 0 0 1-1.449.12l-.738-.527c-.35-.25-.806-.272-1.203-.107-.398.165-.71.505-.781.929l-.149.894c-.09.542-.56.94-1.11.94h-1.094c-.55 0-1.019-.398-1.11-.94l-.148-.894c-.071-.424-.384-.764-.781-.93-.398-.164-.854-.142-1.204.108l-.738.527c-.447.32-1.06.269-1.45-.12l-.773-.774a1.125 1.125 0 0 1-.12-1.45l.527-.737c.25-.35.273-.806.108-1.204-.165-.397-.505-.71-.93-.78l-.894-.15c-.542-.09-.94-.559-.94-1.109v-1.094c0-.55.398-1.02.94-1.11l.894-.149c.424-.07.764-.383.93-.78.165-.398.143-.854-.108-1.204l-.526-.738a1.125 1.125 0 0 1 .12-1.45l.773-.773a1.125 1.125 0 0 1 1.45-.12l.737.527c.35.25.807.272 1.204.107.397-.165.71-.505.78-.929l.15-.894Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                    </flux:button>

                    <flux:menu class="min-w-52">
                        {{-- Share --}}
                        <flux:menu.item
                            icon="share"
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
                    <span class="font-semibold text-white">€{{ number_format($this->totalSpent, 2) }}</span>
                    {{ __('app.spent_so_far') }}
                </p>
            @endif

            @if($mode === 'owner')
                <div class="mt-4 flex gap-2">
                    <button
                        wire:click="finishTrip"
                        wire:confirm="{{ __('app.finish_trip_confirm') }}"
                        class="flex-1 bg-white text-[#1a1a1a] rounded-full py-2.5 text-sm font-semibold flex items-center justify-center gap-1.5 tap"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                        </svg>
                        {{ __('app.finish_trip') }}
                    </button>
                    <button
                        wire:click="clearList"
                        wire:confirm="{{ __('app.clear_confirm') }}"
                        class="bg-white/20 text-white rounded-full px-5 py-2.5 text-sm font-semibold flex items-center gap-1.5 hover:bg-white/30 transition-colors tap"
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

    {{-- Notes for this trip --}}
    <div class="px-5 mt-3">
        <textarea
            wire:model.live.debounce.500ms="notes"
            wire:change="updateNotes"
            placeholder="{{ __('app.notes_placeholder') }}"
            rows="2"
            class="w-full bg-white/60 rounded-2xl px-4 py-2 text-sm text-[#1a1a1a] placeholder-[#b0a99a] outline-none resize-none border border-[#ede8df] focus:border-[#2f7d4f] transition-colors"
        ></textarea>
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
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                        </svg>
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
                            @php $storeEnum = $item['preferred_store'] ? \App\Enums\Store::tryFrom($item['preferred_store']) : null; @endphp
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

                                {{-- Remove (owner only) --}}
                                @if($mode === 'owner')
                                    <button
                                        wire:click="removeItem({{ $item['id'] }})"
                                        wire:confirm="{{ __('app.remove_confirm', ['name' => $item['name']]) }}"
                                        class="shrink-0 size-7 rounded-full flex items-center justify-center text-[#c5bdb0] hover:text-[#e53935] hover:bg-red-50 transition-colors tap"
                                        aria-label="{{ __('app.remove_item', ['name' => $item['name']]) }}"
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
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                    </svg>
                                </button>

                                {{-- Emoji --}}
                                <div class="shrink-0 size-10 rounded-xl bg-[#f4f0e8]/60 flex items-center justify-center text-lg opacity-50">
                                    {{ $item['emoji'] ?: '🛒' }}
                                </div>

                                <p class="flex-1 font-semibold text-[#9b9080] line-through list-text">{{ $item['name'] }}</p>

                                @if($mode === 'owner')
                                    <button
                                        type="button"
                                        x-on:click="
                                            const current = '{{ $item['price'] ?? '' }}';
                                            const raw = window.prompt('{{ __('app.set_price_prompt', ['name' => $item['name']]) }}', current);
                                            if (raw !== null) {
                                                const value = raw.replace(',', '.').trim();
                                                $wire.setItemPrice({{ $item['id'] }}, value === '' ? null : parseFloat(value));
                                            }
                                        "
                                        class="shrink-0 text-xs font-semibold tap rounded-full px-2.5 py-1 transition-colors {{ $item['price'] ? 'bg-[#e3ede7] text-[#2f7d4f]' : 'bg-[#f4f0e8] text-[#9b9080]' }}"
                                        aria-label="{{ __('app.set_price', ['name' => $item['name']]) }}"
                                    >
                                        @if($item['price'])
                                            €{{ number_format((float) $item['price'], 2) }}
                                        @else
                                            +€
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
                    x-show="focused"
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="opacity-0 translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="mb-2 bg-white rounded-2xl shadow-lg border border-[#ede8df] overflow-hidden"
                >
                    @foreach($this->catalogSuggestions as $suggestion)
                        @php $storeEnum = $suggestion['preferred_store'] ? \App\Enums\Store::tryFrom($suggestion['preferred_store']) : null; @endphp
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
                    x-show="supported"
                    x-on:click="toggle"
                    x-bind:class="recording ? 'bg-[#e53935] text-white animate-pulse' : 'bg-[#f4f0e8] text-[#1a1a1a] hover:bg-[#ede8df]'"
                    class="shrink-0 size-8 rounded-xl flex items-center justify-center transition-colors tap"
                    aria-label="{{ __('app.start_voice_input') }}"
                    data-voice-toggle
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 18.75a6 6 0 0 0 6-6v-1.5m-6 7.5a6 6 0 0 1-6-6v-1.5m6 7.5v3.75m-3.75 0h7.5M12 15.75a3 3 0 0 1-3-3V4.5a3 3 0 1 1 6 0v8.25a3 3 0 0 1-3 3Z" />
                    </svg>
                </button>
                <button
                    type="submit"
                    class="shrink-0 size-8 rounded-xl bg-[#1a1a1a] text-white flex items-center justify-center hover:bg-[#333] transition-colors tap"
                    aria-label="{{ __('app.add_item') }}"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
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
