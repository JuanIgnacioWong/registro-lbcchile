<x-admin-layout>
    <x-slot name="heading">Editar Club</x-slot>

    <x-card title="Datos del club">
        <form method="POST" enctype="multipart/form-data" action="{{ route('admin.clubs.update', $club) }}" class="max-w-3xl space-y-4" x-data="{name:'{{ old('name', $club->name) }}', slug:'{{ old('slug', $club->slug) }}'}">
            @csrf
            @method('PUT')
            <x-select label="Temporada" name="season_id" required>
                @foreach($seasons as $season)
                    <option value="{{ $season->id }}" @selected(old('season_id', $club->season_id) == $season->id)>{{ $season->year }} - {{ $season->name }}</option>
                @endforeach
            </x-select>
            <x-select label="División" name="division_id" required>
                @foreach($divisions as $division)
                    <option value="{{ $division->id }}" @selected(old('division_id', $club->division_id) == $division->id)>{{ $division->season->year }} - {{ $division->name }}</option>
                @endforeach
            </x-select>
            <x-input label="Nombre" name="name" x-model="name" required />
            <x-input label="Slug" name="slug" x-model="slug" required />
            <x-file-input label="Logo referencial" name="logo_path" accept=".png,.jpg,.jpeg,.webp,.svg" />
            <label class="flex items-center gap-2 text-sm text-slate-700"><input type="checkbox" name="is_active" value="1" {{ old('is_active', $club->is_active) ? 'checked' : '' }}> Activo</label>
            <x-button type="submit">Actualizar club</x-button>
        </form>
    </x-card>
</x-admin-layout>
