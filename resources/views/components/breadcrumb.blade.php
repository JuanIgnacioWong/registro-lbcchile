@props(['items' => []])

<nav class="text-xs text-slate-500">
    <ol class="flex flex-wrap items-center gap-1">
        @foreach($items as $item)
            <li>
                @if(isset($item['href']))
                    <a href="{{ $item['href'] }}" class="hover:text-slate-700">{{ $item['label'] }}</a>
                @else
                    <span class="font-semibold text-slate-700">{{ $item['label'] }}</span>
                @endif
            </li>
            @if(! $loop->last)
                <li>/</li>
            @endif
        @endforeach
    </ol>
</nav>
