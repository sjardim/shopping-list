<div class="flex flex-col min-h-screen">

    {{-- Header --}}
    <header class="sticky top-0 z-40 bg-[#f1ebd9]/95 backdrop-blur-sm px-4 pt-10 pb-3">
        <h1 class="heading-serif text-xl font-semibold text-[#1a1a1a]">{{ __('app.history_title') }}</h1>
    </header>

    {{-- List --}}
    <main class="flex-1 px-4 pb-28 mt-2">
        @if($this->completedLists->isEmpty())
            <div class="mt-16 text-center text-[#6b6055] fade-in-up">
                <p class="text-4xl mb-3">📋</p>
                <p class="text-base font-medium">{{ __('app.no_past_lists') }}</p>
                <p class="text-sm mt-1">{{ __('app.history_empty_hint') }}</p>
            </div>
        @else
            <div class="space-y-3">
                @foreach($this->completedLists as $list)
                    <div wire:key="history-{{ $list->id }}" class="bg-white rounded-2xl px-4 py-3 shadow-sm fade-in-up">
                        <div class="flex items-start justify-between gap-2">
                            <div class="flex items-center gap-2 min-w-0">
                                @if($list->store)
                                    <x-store-badge :store="$list->store" />
                                @endif
                                <div class="min-w-0">
                                    <p class="font-medium text-[#1a1a1a] text-sm truncate">{{ $list->name }}</p>
                                    <p class="text-xs text-[#6b6055]">
                                        {{ $list->completed_at?->format('d M Y') }}
                                        · {{ __('app.items', ['count' => $list->items_count]) }}
                                        @if($list->bought_count < $list->items_count)
                                            · <span class="text-amber-600">{{ __('app.skipped', ['count' => $list->items_count - $list->bought_count]) }}</span>
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center gap-1 shrink-0">
                                <flux:button
                                    wire:click="repeatList({{ $list->id }})"
                                    wire:confirm="{{ __('app.repeat_list_confirm') }}"
                                    variant="ghost"
                                    size="sm"
                                    icon="arrow-path"
                                    class="tap"
                                    aria-label="{{ __('app.repeat_list', ['name' => $list->name]) }}"
                                />
                                <flux:button
                                    wire:click="deleteList({{ $list->id }})"
                                    wire:confirm="{{ __('app.delete_list_confirm') }}"
                                    variant="ghost"
                                    size="sm"
                                    icon="trash"
                                    class="text-[#c5bdb0] hover:text-[#e53935] tap"
                                    aria-label="{{ __('app.delete_list', ['name' => $list->name]) }}"
                                />
                            </div>
                        </div>

                        {{-- Item preview --}}
                        @if($list->items->isNotEmpty())
                            <div class="mt-2 flex flex-wrap gap-1">
                                @foreach($list->items->take(6) as $item)
                                    <span class="text-xs bg-[#f7f3ec] text-[#6b6055] px-2 py-0.5 rounded-full {{ $item->is_bought ? '' : 'line-through opacity-50' }}">
                                        {{ $item->emoji }} {{ $item->name }}
                                    </span>
                                @endforeach
                                @if($list->items->count() > 6)
                                    <span class="text-xs text-[#6b6055] px-2 py-0.5">{{ __('app.more', ['count' => $list->items->count() - 6]) }}</span>
                                @endif
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </main>

    {{-- Bottom nav --}}
    <x-bottom-nav active-tab="history" :item-count="0" />

</div>
