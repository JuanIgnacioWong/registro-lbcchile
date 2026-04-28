<x-admin-layout>
    <x-slot name="heading">Correcciones</x-slot>

    <form method="POST" action="{{ route('admin.corrections.store') }}" class="mb-6 grid gap-3 rounded-xl bg-white p-4 shadow-sm md:grid-cols-4">
        @csrf
        <div>
            <label class="text-sm font-medium">Temporada</label>
            <select name="season_id" class="mt-1 w-full rounded border-slate-300">
                @foreach($seasons as $season)<option value="{{ $season->id }}">{{ $season->year }}</option>@endforeach
            </select>
        </div>
        <div>
            <label class="text-sm font-medium">Division</label>
            <select name="division_id" class="mt-1 w-full rounded border-slate-300">
                @foreach($divisions as $division)<option value="{{ $division->id }}">{{ $division->season->year }} - {{ $division->name }}</option>@endforeach
            </select>
        </div>
        <div>
            <label class="text-sm font-medium">Club</label>
            <select name="club_id" class="mt-1 w-full rounded border-slate-300">
                @foreach($clubs as $club)<option value="{{ $club->id }}">{{ $club->season->year }} - {{ $club->division->name }} - {{ $club->name }}</option>@endforeach
            </select>
        </div>
        <div>
            <label class="text-sm font-medium">Expira (opcional)</label>
            <input type="datetime-local" name="expires_at" class="mt-1 w-full rounded border-slate-300">
        </div>
        <div class="md:col-span-4">
            <button class="rounded bg-red-600 px-4 py-2 text-white">Generar enlace seguro</button>
        </div>
    </form>

    <div class="overflow-hidden rounded-xl bg-white shadow-sm">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50"><tr><th class="px-4 py-3 text-left">Año</th><th class="px-4 py-3 text-left">Division</th><th class="px-4 py-3 text-left">Club</th><th class="px-4 py-3 text-left">Estado</th><th class="px-4 py-3 text-left">URL</th><th class="px-4 py-3 text-center">Acciones</th></tr></thead>
            <tbody>
                @forelse($links as $link)
                    @php $url = route('public.corrections.create', [$link->season->year, $link->division->slug, $link->club->slug, $link->token]); @endphp
                    <tr class="border-t align-top">
                        <td class="px-4 py-3">{{ $link->season->year }}</td>
                        <td class="px-4 py-3">{{ $link->division->name }}</td>
                        <td class="px-4 py-3">{{ $link->club->name }}</td>
                        <td class="px-4 py-3">{!! $link->is_active ? '<span class="rounded bg-emerald-100 px-2 py-1 text-xs text-emerald-700">Activo</span>' : '<span class="rounded bg-slate-100 px-2 py-1 text-xs text-slate-700">Inactivo</span>' !!}</td>
                        <td class="px-4 py-3 text-xs"><input readonly value="{{ $url }}" class="w-full rounded border-slate-300 bg-slate-50" onclick="this.select(); document.execCommand('copy');"></td>
                        <td class="px-4 py-3 text-center text-xs">
                            <form method="POST" action="{{ route('admin.corrections.toggle', $link) }}">@csrf<button class="text-blue-700">{{ $link->is_active ? 'Desactivar' : 'Activar' }}</button></form>
                            <form method="POST" action="{{ route('admin.corrections.regenerate', $link) }}">@csrf<button class="mt-1 text-amber-700">Regenerar token</button></form>
                        </td>
                    </tr>
                @empty
                    <tr><td class="px-4 py-4 text-slate-500" colspan="6">Sin enlaces.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $links->links() }}</div>
</x-admin-layout>
