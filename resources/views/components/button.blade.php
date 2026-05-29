@props([
    'type' => 'button',
    'variant' => 'primary',
])

@php
    $classes = match ($variant) {
        'secondary' => 'btn-secondary',
        'danger' => 'inline-flex items-center justify-center rounded-xl bg-danger px-4 py-2 text-sm font-semibold text-white transition hover:bg-red-500',
        default => 'btn-primary',
    };
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</button>
