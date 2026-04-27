@props([
    'pending' => 0,
    'total' => 0,
    'progress' => 0,
    'totalSpent' => 0.0,
    'mode' => 'owner',
    'list',
])

@if($total > 0)
    <div class="mx-5 rounded-xl p-5" style="background-color: #2f7d4f;">
        <p class="text-[10px] font-bold uppercase tracking-widest text-white/60">{{ __('app.shopping_now') }}</p>

        <div class="flex items-baseline gap-2 mt-1">
            <span class="text-6xl font-bold text-white leading-none">{{ $pending }}</span>
            <span class="text-white/80 text-base">{{ __('app.remaining', ['total' => $total]) }}</span>
        </div>

        <div class="mt-4 h-1.5 bg-white/20 rounded-full overflow-hidden">
            <div class="h-full bg-white rounded-full transition-all duration-300" style="width: {{ $progress }}%"></div>
        </div>

        @if($totalSpent > 0)
            <p class="mt-3 text-sm text-white/80">
                <span class="font-semibold text-white">{{ config('lista.currency.symbol') }}{{ number_format($totalSpent, 2) }}</span>
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
