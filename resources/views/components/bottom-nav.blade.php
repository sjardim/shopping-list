@props(['activeTab' => 'list', 'itemCount' => 0])

<nav class="fixed bottom-0 left-0 right-0 bg-[#f7f3ec] border-t border-[#e0d9cc] rounded-t-3xl px-6 py-3 z-50 shadow-[0_-4px_24px_rgba(0,0,0,0.06)]">
    <div class="flex items-center justify-between">
        {{-- List tab --}}
        <a href="{{ route('home') }}" class="flex items-center justify-center tap" aria-label="{{ __('app.nav_list') }}" @if($activeTab === 'list') aria-current="page" @endif>
            @if($activeTab === 'list')
                <span class="flex items-center gap-2 bg-[#1a1a1a] text-white rounded-full px-4 py-2 text-sm font-medium">
                    <flux:icon name="shopping-cart-simple" class="size-5" />
                    {{ __('app.nav_list') }}
                </span>
            @else
                <span class="relative flex items-center justify-center size-10 text-[#6b6055]">
                    <flux:icon name="shopping-cart-simple" class="size-6" />
                    @if($itemCount > 0)
                        <span class="absolute -top-0.5 -right-0.5 flex items-center justify-center size-5 rounded-full bg-[#2f7d4f] text-white text-[10px] font-bold">
                            {{ $itemCount }}
                        </span>
                    @endif
                </span>
            @endif
        </a>

        {{-- Add tab --}}
        <a href="{{ route('add') }}" class="flex items-center justify-center tap" aria-label="{{ __('app.nav_add') }}" @if($activeTab === 'add') aria-current="page" @endif>
            @if($activeTab === 'add')
                <span class="flex items-center gap-2 bg-[#1a1a1a] text-white rounded-full px-4 py-2 text-sm font-medium">
                    <flux:icon name="plus" class="size-5" />
                    {{ __('app.nav_add') }}
                </span>
            @else
                <span class="flex items-center justify-center size-10 text-[#6b6055]">
                    <flux:icon name="plus" class="size-6" />
                </span>
            @endif
        </a>

        {{-- History tab --}}
        <a href="{{ route('history') }}" class="flex items-center justify-center tap" aria-label="{{ __('app.nav_history') }}" @if($activeTab === 'history') aria-current="page" @endif>
            @if($activeTab === 'history')
                <span class="flex items-center gap-2 bg-[#1a1a1a] text-white rounded-full px-4 py-2 text-sm font-medium">
                    <flux:icon name="clock" class="size-5" />
                    {{ __('app.nav_history') }}
                </span>
            @else
                <span class="flex items-center justify-center size-10 text-[#6b6055]">
                    <flux:icon name="clock" class="size-6" />
                </span>
            @endif
        </a>
    </div>
</nav>
