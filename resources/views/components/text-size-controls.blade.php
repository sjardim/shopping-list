{{-- Text-size controls for the profile/settings dropdown --}}
<div class="px-3 py-1.5">
    <p class="text-[10px] font-bold uppercase tracking-widest text-[#6b6055] mb-1">{{ __('app.text_size') }}</p>
</div>
<div class="px-3 pb-2 flex gap-1.5">
    @foreach([['v' => 1, 'size' => 'text-xs'], ['v' => 1.12, 'size' => 'text-sm'], ['v' => 1.25, 'size' => 'text-base']] as $opt)
        <button
            type="button"
            x-on:click.stop="$store.prefs.setUiScale({{ $opt['v'] }})"
            x-bind:class="$store.prefs.uiScale === {{ $opt['v'] }} ? 'bg-[#1a1a1a] text-white' : 'bg-[#f4f0e8] text-[#1a1a1a]'"
            class="flex-1 py-1.5 rounded-lg font-semibold tap {{ $opt['size'] }}"
            aria-label="{{ __('app.text_size_option', ['scale' => (string) $opt['v']]) }}"
        >A</button>
    @endforeach
</div>
<flux:menu.item icon="text-aa" x-on:click.stop="$store.prefs.toggleListScale()">
    <span class="flex items-center justify-between w-full">
        <span>{{ __('app.larger_list_items') }}</span>
        <span x-show="$store.prefs.listScale > 1" class="text-[#2f7d4f] font-bold">✓</span>
    </span>
</flux:menu.item>
<flux:menu.item icon="circle-half" x-on:click.stop="$store.prefs.toggleHighContrast()">
    <span class="flex items-center justify-between w-full">
        <span>{{ __('app.high_contrast') }}</span>
        <span x-show="$store.prefs.highContrast" class="text-[#2f7d4f] font-bold">✓</span>
    </span>
</flux:menu.item>
<flux:menu.item icon="cursor-click" x-on:click.stop="$store.prefs.toggleBigTargets()">
    <span class="flex items-center justify-between w-full">
        <span>{{ __('app.bigger_buttons') }}</span>
        <span x-show="$store.prefs.bigTargets" class="text-[#2f7d4f] font-bold">✓</span>
    </span>
</flux:menu.item>
<flux:menu.item icon="speaker-high" x-on:click.stop="$store.prefs.toggleSound()">
    <span class="flex items-center justify-between w-full">
        <span>{{ __('app.sound_effects') }}</span>
        <span x-show="$store.prefs.soundEnabled" class="text-[#2f7d4f] font-bold">✓</span>
    </span>
</flux:menu.item>
