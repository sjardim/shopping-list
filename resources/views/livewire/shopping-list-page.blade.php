<div class="flex flex-col min-h-screen">

    <x-shopping-list.header
        :list="$list"
        :mode="$mode"
        :locale="$locale"
        :share-token="$shareToken"
    />

    @php
        $total = $list->totalCount();
        $pending = $list->pendingCount();
        $bought = $total - $pending;
        $progress = $total > 0 ? round(($bought / $total) * 100) : 0;
    @endphp

    <x-shopping-list.progress-card
        :pending="$pending"
        :total="$total"
        :progress="$progress"
        :total-spent="$this->totalSpent"
        :mode="$mode"
        :list="$list"
    />

    <x-shopping-list.notes-input />

    <main class="flex-1 px-5 pb-48 mt-3 space-y-6">

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
            @foreach($this->itemsByCategory['pending'] as $category => $items)
                @php $categoryEnum = \App\Enums\Category::tryFrom($category); @endphp
                <section>
                    <h2 class="flex items-center gap-1.5 text-[11px] font-bold text-[#6b6055] uppercase tracking-widest mb-3">
                        <span>{{ $categoryEnum?->emoji() }}</span>
                        <span>{{ $categoryEnum?->label() ?? $category }}</span>
                    </h2>
                    <div class="bg-white rounded-xl overflow-hidden divide-y divide-[#f4f0e8]">
                        @foreach($items as $item)
                            <x-shopping-list.item-row :item="$item" :mode="$mode" />
                        @endforeach
                    </div>
                </section>
            @endforeach

            @if(count($this->itemsByCategory['bought']) > 0)
                <section>
                    <h2 class="flex items-center gap-1.5 text-[11px] font-bold text-[#6b6055] uppercase tracking-widest mb-3">
                        <span>✓</span>
                        <span>{{ __('app.in_cart') }}</span>
                    </h2>
                    <div class="bg-white/60 rounded-xl overflow-hidden divide-y divide-[#f4f0e8]">
                        @foreach($this->itemsByCategory['bought'] as $item)
                            <x-shopping-list.bought-row :item="$item" :mode="$mode" />
                        @endforeach
                    </div>
                </section>
            @endif
        @endif
    </main>

    @if($mode === 'owner')
        <x-shopping-list.quick-add :suggestions="$this->catalogSuggestions" />
        <x-bottom-nav active-tab="list" :item-count="$pending ?? 0" />
        <x-shopping-list.price-modal :editing="$this->editingItem" :price-history="$this->priceHistory" :list-store="$list->store" />
        <x-shopping-list.save-recipe-modal />
    @endif

</div>
