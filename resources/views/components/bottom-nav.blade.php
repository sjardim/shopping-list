@props(['activeTab' => 'list', 'itemCount' => 0])

<nav class="fixed bottom-0 left-0 right-0 bg-[#f7f3ec] border-t border-[#e0d9cc] rounded-t-3xl px-6 py-3 z-50 shadow-[0_-4px_24px_rgba(0,0,0,0.06)]">
    <div class="flex items-center justify-between">
        {{-- List tab --}}
        <a href="{{ route('home') }}" class="flex items-center justify-center">
            @if($activeTab === 'list')
                <span class="flex items-center gap-2 bg-[#1a1a1a] text-white rounded-full px-4 py-2 text-sm font-medium">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                    </svg>
                    {{ __('app.nav_list') }}
                </span>
            @else
                <span class="relative flex items-center justify-center size-10 text-[#6b6055]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                    </svg>
                    @if($itemCount > 0)
                        <span class="absolute -top-0.5 -right-0.5 flex items-center justify-center size-5 rounded-full bg-[#2f7d4f] text-white text-[10px] font-bold">
                            {{ $itemCount }}
                        </span>
                    @endif
                </span>
            @endif
        </a>

        {{-- Add tab --}}
        <a href="{{ route('add') }}" class="flex items-center justify-center">
            @if($activeTab === 'add')
                <span class="flex items-center gap-2 bg-[#1a1a1a] text-white rounded-full px-4 py-2 text-sm font-medium">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    {{ __('app.nav_add') }}
                </span>
            @else
                <span class="flex items-center justify-center size-10 text-[#6b6055]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                </span>
            @endif
        </a>

        {{-- History tab --}}
        <a href="{{ route('history') }}" class="flex items-center justify-center">
            @if($activeTab === 'history')
                <span class="flex items-center gap-2 bg-[#1a1a1a] text-white rounded-full px-4 py-2 text-sm font-medium">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    {{ __('app.nav_history') }}
                </span>
            @else
                <span class="flex items-center justify-center size-10 text-[#6b6055]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </span>
            @endif
        </a>
    </div>
</nav>
