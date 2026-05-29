@props(['label', 'value', 'tone' => 'info'])

<x-card class="relative overflow-hidden">
    <div class="absolute right-0 top-0 h-16 w-16 rounded-bl-[2rem] bg-sky-50"></div>
    <p class="text-sm font-medium text-slate-500">{{ $label }}</p>
    <p class="mt-2 font-heading text-3xl font-extrabold text-primary">{{ $value }}</p>
</x-card>
