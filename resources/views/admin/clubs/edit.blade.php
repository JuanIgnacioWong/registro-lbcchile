<x-admin-layout>
    <x-slot name="heading">Editar Club</x-slot>
    <form method="POST" enctype="multipart/form-data" action="{{ route('admin.clubs.update', $club) }}" class="max-w-3xl space-y-4 rounded-xl bg-white p-6 shadow-sm" x-data="{name:'{{ old('name', $club->name) }}', slug:'{{ old('slug', $club->slug) }}'}">
        @csrf @method('PUT')
        <div>
            <label class="text-sm font-medium">Temporada</label>
            <select name="season_id" class="mt-1 w-full rounded border-slate-300">
                @foreach($seasons as $season)<option value="{{ $season->id }}" @selected(old('season_id', $club->season_id) == $season->id)>{{ $season->year }} - {{ $season->name }}</option>@endforeach
            </select>
        </div>
        <div>
            <label class="text-sm font-medium">Division</label>
            <select name="division_id" class="mt-1 w-full rounded border-slate-300">
                @foreach($divisions as $division)<option value="{{ $division->id }}" @selected(old('division_id', $club->division_id) == $division->id)>{{ $division->season->year }} - {{ $division->name }}</option>@endforeach
            </select>
        </div>
        <div><label class="text-sm font-medium">Nombre</label><input name="name" x-model="name" class="mt-1 w-full rounded border-slate-300"></div>
        <div><label class="text-sm font-medium">Slug</label><input name="slug" x-model="slug" class="mt-1 w-full rounded border-slate-300"></div>
        <div><label class="text-sm font-medium">Logo referencial</label><input type="file" name="logo_path" class="mt-1 w-full rounded border-slate-300" accept=".png,.jpg,.jpeg,.webp,.svg"></div>
        <label class="flex items-center gap-2"><input type="checkbox" name="is_active" value="1" {{ old('is_active', $club->is_active) ? 'checked' : '' }}> Activo</label>
        <button class="rounded bg-red-600 px-4 py-2 text-white">Actualizar</button>
    </form>
</x-admin-layout>
