@props(['title' => null, 'subtitle' => null])

<section {{ $attributes->merge(['class' => 'panel-card']) }}>
    @if($title)
        <header class="mb-4">
            <h3 class="font-heading text-lg font-bold text-primary">{{ $title }}</h3>
            @if($subtitle)
                <p class="mt-1 text-sm text-slate-500">{{ $subtitle }}</p>
            @endif
        </header>
    @endif

    {{ $slot }}
</section>
