<x-admin-layout>
    <x-slot name="heading">Editar Temporada</x-slot>
    <form method="POST" action="{{ route('admin.seasons.update', $season) }}" class="max-w-2xl space-y-4 rounded-xl bg-white p-6 shadow-sm">
        @csrf @method('PUT')
        <div><label class="text-sm font-medium">Año</label><input name="year" type="number" value="{{ old('year', $season->year) }}" class="mt-1 w-full rounded border-slate-300"></div>
        <div><label class="text-sm font-medium">Nombre</label><input name="name" value="{{ old('name', $season->name) }}" class="mt-1 w-full rounded border-slate-300"></div>
        <label class="flex items-center gap-2"><input type="checkbox" name="is_active" value="1" {{ old('is_active', $season->is_active) ? 'checked' : '' }}> Activa</label>
        <button class="rounded bg-red-600 px-4 py-2 text-white">Actualizar</button>
    </form>
</x-admin-layout>
