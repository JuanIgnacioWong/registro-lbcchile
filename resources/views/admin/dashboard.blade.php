<x-admin-layout>
    <x-slot name="heading">Dashboard</x-slot>

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        <div class="rounded-xl bg-white p-4 shadow-sm"><p class="text-xs text-slate-500">Total clubes</p><p class="text-3xl font-bold">{{ $kpis['total_clubs'] }}</p></div>
        <div class="rounded-xl bg-white p-4 shadow-sm"><p class="text-xs text-slate-500">Antecedentes recibidos</p><p class="text-3xl font-bold">{{ $kpis['submissions_received'] }}</p></div>
        <div class="rounded-xl bg-white p-4 shadow-sm"><p class="text-xs text-slate-500">Pagos pendientes</p><p class="text-3xl font-bold">{{ $kpis['pending_payments'] }}</p></div>
        <div class="rounded-xl bg-white p-4 shadow-sm"><p class="text-xs text-slate-500">Pagos en revision</p><p class="text-3xl font-bold">{{ $kpis['in_review_payments'] }}</p></div>
        <div class="rounded-xl bg-white p-4 shadow-sm"><p class="text-xs text-slate-500">Pagados</p><p class="text-3xl font-bold">{{ $kpis['paid_payments'] }}</p></div>
        <div class="rounded-xl bg-white p-4 shadow-sm"><p class="text-xs text-slate-500">Correcciones pendientes</p><p class="text-3xl font-bold">{{ $kpis['pending_corrections'] }}</p></div>
    </div>

    <div class="mt-6">
        <livewire:admin.submission-table />
    </div>
</x-admin-layout>
