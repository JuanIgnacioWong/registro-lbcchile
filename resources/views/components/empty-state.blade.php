@props(['title' => 'Sin registros', 'description' => 'No hay datos disponibles para esta vista.'])

<div class="rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-10 text-center">
    <h3 class="font-heading text-lg font-bold text-primary">{{ $title }}</h3>
    <p class="mt-2 text-sm text-slate-500">{{ $description }}</p>
</div>
