@props([
    'suggestions' => [],
])

<div class="fixed bottom-[4.5rem] left-0 right-0 px-5 pb-3" x-data="{ focused: false }">
    @if(count($suggestions) > 0)
        <div
            x-cloak
            x-show="focused"
            x-transition:enter="transition ease-out duration-100"
            x-transition:enter-start="opacity-0 translate-y-1"
            x-transition:enter-end="opacity-100 translate-y-0"
            class="mb-2 bg-white rounded-lg shadow-lg border border-[#ede8df] overflow-hidden"
        >
            @foreach($suggestions as $suggestion)
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
        class="flex gap-2 bg-white rounded-lg shadow-lg shadow-black/5 border border-[#ede8df] px-4 py-2"
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
            class="shrink-0 size-8 rounded-md flex items-center justify-center transition-colors tap"
            aria-label="{{ __('app.start_voice_input') }}"
            data-voice-toggle
        >
            <flux:icon name="microphone" class="size-4" />
        </button>
        <button
            type="submit"
            class="shrink-0 size-8 rounded-md bg-green-700 text-white flex items-center justify-center hover:bg-[#333] transition-colors tap"
            aria-label="{{ __('app.add_item') }}"
        >
            <flux:icon name="plus" class="size-4" />
        </button>
    </form>
    @error('quickAddName')
        <p role="alert" class="text-xs text-red-500 mt-1 px-1">{{ $message }}</p>
    @enderror
</div>
