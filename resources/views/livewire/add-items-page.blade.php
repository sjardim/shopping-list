<div class="flex flex-col min-h-screen">

    {{-- Header --}}
    <header class="sticky top-0 z-40 bg-[#f1ebd9]/95 backdrop-blur-sm px-4 pt-10 pb-3">
        <div class="flex items-center justify-between mb-3">
            <h1 class="heading-serif text-3xl font-bold text-[#1a1a1a]">{{ __('app.add_items_title') }}</h1>

            <flux:button wire:click="addToList" variant="ghost" size="sm">
                {{ __('app.done') }}
            </flux:button>
        </div>

        {{-- Search --}}
        <flux:input
            wire:model.live.debounce.300ms="searchQuery"
            placeholder="{{ __('app.search_placeholder') }}"
            icon="magnifying-glass"
            clearable
        />

        {{-- Tab switcher --}}
        <div class="flex gap-2 mt-3">
            <button
                wire:click="$set('activeTab', 'suggested')"
                class="flex-1 py-2.5 rounded-full text-sm font-semibold transition-colors tap {{ $activeTab === 'suggested' ? 'bg-[#1a1a1a] text-white' : 'bg-white text-[#6b6055] border border-[#e0d9cc]' }}"
            >
                {{ __('app.suggested') }}
            </button>
            <button
                wire:click="$set('activeTab', 'cook')"
                class="flex-1 py-2.5 rounded-full text-sm font-semibold transition-colors tap {{ $activeTab === 'cook' ? 'bg-[#1a1a1a] text-white' : 'bg-white text-[#6b6055] border border-[#e0d9cc]' }}"
            >
                {{ __('app.cook_something') }}
            </button>
        </div>
    </header>

    <main class="flex-1 px-4 pb-28 mt-2">

        @if($activeTab === 'suggested')

            @if($searchQuery !== '' && count($this->groupedCatalogItems) === 0)
                <div class="mt-16 text-center text-[#6b6055] fade-in-up">
                    <p class="text-3xl mb-2">🔍</p>
                    <p class="text-sm">{{ __('app.no_results', ['query' => $searchQuery]) }}</p>
                </div>
            @else
                @foreach($this->groupedCatalogItems as $category => $items)
                    @php $categoryEnum = \App\Enums\Category::tryFrom($category); @endphp
                    <section class="mt-5 fade-in-up">
                        <h2 class="flex items-center gap-1.5 text-[11px] font-bold text-[#6b6055] uppercase tracking-widest mb-2">
                            <span>{{ $categoryEnum?->emoji() }}</span>
                            <span>{{ $categoryEnum?->label() ?? $category }}</span>
                        </h2>

                        <div class="grid grid-cols-3 gap-2">
                            @foreach($items as $item)
                                @php
                                    $isSelected = in_array($item['id'], $selectedCatalogIds, true);
                                    $storeEnum  = $item['preferred_store'] ? \App\Enums\Store::tryFrom($item['preferred_store']) : null;
                                @endphp
                                <button
                                    wire:key="catalog-{{ $item['id'] }}"
                                    wire:click="toggleCatalogItem({{ $item['id'] }})"
                                    aria-pressed="{{ $isSelected ? 'true' : 'false' }}"
                                    class="relative flex flex-col items-center justify-between gap-1 p-3 rounded-2xl border transition-colors aspect-square tap
                                        {{ $isSelected
                                            ? 'bg-[#e3ede7] border-[#2f7d4f]/25'
                                            : 'bg-white border-[#f0ece3]' }}"
                                >
                                    {{-- Checkmark badge --}}
                                    @if($isSelected)
                                        <span class="absolute top-1.5 right-1.5 size-5 rounded-full bg-[#2f7d4f] flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-3 text-white" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                            </svg>
                                        </span>
                                    @endif

                                    {{-- Emoji --}}
                                    <span class="text-3xl mt-1">{{ $item['emoji'] ?: '🛒' }}</span>

                                    {{-- Name --}}
                                    <span class="text-[11px] font-medium text-center text-[#1a1a1a] leading-tight line-clamp-2">{{ $item['name'] }}</span>

                                    {{-- Store dot --}}
                                    @if($storeEnum)
                                        <span class="size-2 rounded-full shrink-0" style="background-color: {{ $storeEnum->color() }}"></span>
                                    @else
                                        <span class="size-2"></span>
                                    @endif
                                </button>
                            @endforeach
                        </div>
                    </section>
                @endforeach
            @endif

        @else

            {{-- Cook something --}}
            <div class="space-y-3 mt-4">
                @foreach($this->mealBundles as $key => $bundle)
                    <div wire:key="bundle-{{ $key }}" class="bg-white rounded-2xl px-4 py-3 shadow-sm fade-in-up">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="text-2xl">{{ $bundle['emoji'] }}</span>
                                <div>
                                    <p class="font-medium text-[#1a1a1a] text-sm">{{ $bundle['name'] }}</p>
                                    <p class="text-xs text-[#6b6055]">{{ __('app.ingredients', ['count' => count($bundle['items'])]) }}</p>
                                </div>
                            </div>
                            <flux:button
                                wire:click="applyMealBundle('{{ $key }}')"
                                variant="ghost"
                                size="sm"
                                icon="plus"
                                class="tap"
                                aria-label="{{ __('app.add_bundle', ['name' => $bundle['name']]) }}"
                            />
                        </div>
                        <div class="mt-2 flex flex-wrap gap-1">
                            @foreach($bundle['items'] as $ingredient)
                                <span class="text-xs bg-[#f7f3ec] text-[#6b6055] px-2 py-0.5 rounded-full">
                                    {{ $ingredient['name'] }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

        @endif

    </main>

    {{-- Bottom nav --}}
    <x-bottom-nav active-tab="add" :item-count="0" />

</div>
