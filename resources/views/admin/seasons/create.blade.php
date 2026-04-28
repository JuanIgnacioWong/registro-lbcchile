<x-admin-layout>
    <x-slot name="heading">Nueva Temporada</x-slot>
    <form method="POST" action="{{ route('admin.seasons.store') }}" class="max-w-2xl space-y-4 rounded-xl bg-white p-6 shadow-sm">
        @csrf
        <div><label class="text-sm font-medium">Año</label><input name="year" type="number" value="{{ old('year') }}" class="mt-1 w-full rounded border-slate-300"></div>
        <div><label class="text-sm font-medium">Nombre</label><input name="name" value="{{ old('name') }}" class="mt-1 w-full rounded border-slate-300"></div>
        <label class="flex items-center gap-2"><input type="checkbox" name="is_active" value="1" checked> Activa</label>
        <button class="rounded bg-red-600 px-4 py-2 text-white">Guardar</button>
    </form>
</x-admin-layout>
