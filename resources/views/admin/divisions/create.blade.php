<x-admin-layout>
    <x-slot name="heading">Nueva Division</x-slot>
    <form method="POST" action="{{ route('admin.divisions.store') }}" class="max-w-3xl space-y-4 rounded-xl bg-white p-6 shadow-sm" x-data="{name:'{{ old('name') }}', slug:'{{ old('slug') }}'}">
        @csrf
        <div>
            <label class="text-sm font-medium">Temporada</label>
            <select name="season_id" class="mt-1 w-full rounded border-slate-300">
                @foreach($seasons as $season)<option value="{{ $season->id }}" @selected(old('season_id') == $season->id)>{{ $season->year }} - {{ $season->name }}</option>@endforeach
            </select>
        </div>
        <div><label class="text-sm font-medium">Nombre</label><input name="name" x-model="name" @input="if(!slug){slug = name.toLowerCase().replace(/[^a-z0-9]+/g,'-').replace(/^-|-$/g,'')}" class="mt-1 w-full rounded border-slate-300"></div>
        <div><label class="text-sm font-medium">Slug</label><input name="slug" x-model="slug" class="mt-1 w-full rounded border-slate-300"></div>
        <label class="flex items-center gap-2"><input type="checkbox" name="is_active" value="1" checked> Activa</label>
        <button class="rounded bg-red-600 px-4 py-2 text-white">Guardar</button>
    </form>
</x-admin-layout>
