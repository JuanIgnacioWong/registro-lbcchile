<x-admin-layout>
    <x-slot name="heading">Nueva Temporada</x-slot>

    <x-card title="Datos de temporada">
        <form method="POST" action="{{ route('admin.seasons.store') }}" class="max-w-2xl space-y-4">
            @csrf
            <x-input type="number" label="Año" name="year" :value="old('year')" min="2020" max="2100" required />
            <x-input label="Nombre" name="name" :value="old('name')" required />
            <label class="flex items-center gap-2 text-sm text-slate-700"><input type="checkbox" name="is_active" value="1" checked> Activa</label>
            <x-button type="submit">Guardar temporada</x-button>
        </form>
    </x-card>
</x-admin-layout>
