{{-- Notes for this trip. Wire model and submit live on the parent component. --}}
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
            class="w-full bg-white/60 rounded-lg pl-4 pr-12 py-2 text-sm text-[#1a1a1a] placeholder-[#b0a99a] outline-none resize-none border border-[#ede8df] focus:border-[#2f7d4f] transition-colors"
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
            class="absolute bottom-2 right-2 size-7 rounded-sm flex items-center justify-center transition-colors tap"
            aria-label="{{ __('app.start_voice_input') }}"
            data-voice-toggle
        >
            <flux:icon name="microphone" class="size-3.5" />
        </button>
    </div>
</div>
