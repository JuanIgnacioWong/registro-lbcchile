<x-admin-layout>
    <x-slot name="heading">Editar División</x-slot>

    <x-card title="Datos de división">
        <form method="POST" action="{{ route('admin.divisions.update', $division) }}" class="max-w-3xl space-y-4" x-data="{name:'{{ old('name', $division->name) }}', slug:'{{ old('slug', $division->slug) }}'}">
            @csrf
            @method('PUT')
            <x-select label="Temporada" name="season_id" required>
                @foreach($seasons as $season)
                    <option value="{{ $season->id }}" @selected(old('season_id', $division->season_id) == $season->id)>{{ $season->year }} - {{ $season->name }}</option>
                @endforeach
            </x-select>
            <x-input label="Nombre" name="name" x-model="name" required />
            <x-input label="Slug" name="slug" x-model="slug" required />
            <label class="flex items-center gap-2 text-sm text-slate-700"><input type="checkbox" name="is_active" value="1" {{ old('is_active', $division->is_active) ? 'checked' : '' }}> Activa</label>
            <x-button type="submit">Actualizar división</x-button>
        </form>
    </x-card>
</x-admin-layout>
