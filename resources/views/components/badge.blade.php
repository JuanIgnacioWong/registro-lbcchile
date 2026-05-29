@props(['tone' => 'muted'])

@php
    $toneClasses = match ($tone) {
        'success' => 'bg-emerald-100 text-emerald-700',
        'warning' => 'bg-amber-100 text-amber-700',
        'danger' => 'bg-red-100 text-red-700',
        'info' => 'bg-sky-100 text-sky-700',
        default => 'bg-slate-100 text-slate-700',
    };
@endphp

<span {{ $attributes->merge(['class' => 'badge '.$toneClasses]) }}>{{ $slot }}</span>
