@props(['type' => 'info', 'title' => null])

@php
    $classes = match ($type) {
        'success' => 'border-emerald-200 bg-emerald-50 text-emerald-800',
        'danger' => 'border-red-200 bg-red-50 text-red-800',
        'warning' => 'border-amber-200 bg-amber-50 text-amber-800',
        default => 'border-sky-200 bg-sky-50 text-sky-800',
    };
@endphp

<div {{ $attributes->merge(['class' => 'mb-4 rounded-2xl border px-4 py-3 '.$classes]) }}>
    @if($title)
        <p class="mb-1 text-sm font-semibold">{{ $title }}</p>
    @endif
    <div class="text-sm">{{ $slot }}</div>
</div>
