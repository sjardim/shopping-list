<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $list->name }}</title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: Georgia, 'Times New Roman', serif;
            background: #fff;
            color: #000;
            margin: 0;
            padding: 1.5rem;
            line-height: 1.4;
        }
        h1 { margin: 0 0 0.25rem 0; font-size: 1.75rem; }
        h2 {
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin: 1.5rem 0 0.5rem 0;
            border-bottom: 2px solid #000;
            padding-bottom: 0.25rem;
        }
        .meta { color: #555; font-size: 0.95rem; margin-bottom: 1rem; }
        .notes {
            border: 1px solid #ccc;
            padding: 0.75rem 1rem;
            margin: 1rem 0;
            font-style: italic;
            background: #fafafa;
        }
        ul { list-style: none; padding: 0; margin: 0; }
        li {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 0;
            border-bottom: 1px dashed #ddd;
            font-size: 1.05rem;
        }
        .check {
            display: inline-block;
            width: 1.25rem;
            height: 1.25rem;
            border: 2px solid #000;
            border-radius: 0.125rem;
            flex-shrink: 0;
        }
        .check.bought { background: #000; position: relative; }
        .check.bought::after {
            content: '';
            position: absolute;
            left: 0.3rem;
            top: 0.05rem;
            width: 0.35rem;
            height: 0.7rem;
            border-right: 2px solid #fff;
            border-bottom: 2px solid #fff;
            transform: rotate(45deg);
        }
        .name { flex: 1; }
        .name.bought { text-decoration: line-through; color: #888; }
        .qty { color: #555; font-size: 0.95rem; }
        .footer { margin-top: 2rem; font-size: 0.8rem; color: #888; }
        @media print {
            body { padding: 1cm; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <h1>{{ $list->name }}</h1>
    <p class="meta">
        {{ $list->store?->label() ?? __('app.no_store') }}
        @if($list->completed_at)
            · {{ $list->completed_at->format('d M Y') }}
        @endif
        · {{ $list->items->count() }} {{ __('app.items', ['count' => $list->items->count()]) }}
    </p>

    @if($list->notes)
        <div class="notes">{{ $list->notes }}</div>
    @endif

    @foreach($itemsByCategory as $category => $items)
        @php $categoryEnum = \App\Enums\Category::tryFrom($category); @endphp
        <h2>{{ $categoryEnum?->emoji() }} {{ $categoryEnum?->label() ?? $category }}</h2>
        <ul>
            @foreach($items as $item)
                <li>
                    <span class="check {{ $item->is_bought ? 'bought' : '' }}"></span>
                    <span class="name {{ $item->is_bought ? 'bought' : '' }}">
                        {{ $item->emoji }} {{ $item->name }}
                    </span>
                    <span class="qty">
                        {{ rtrim(rtrim(number_format((float) $item->quantity, 2, '.', ''), '0'), '.') }} {{ $item->unit }}
                    </span>
                </li>
            @endforeach
        </ul>
    @endforeach

    <p class="footer no-print">{{ __('app.print_hint') }}</p>

    <script>window.print();</script>
</body>
</html>
