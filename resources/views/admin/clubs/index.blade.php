<x-admin-layout>
    <x-slot name="heading">Clubes</x-slot>

    <div class="mb-4 flex justify-end"><a href="{{ route('admin.clubs.create') }}" class="rounded bg-red-600 px-4 py-2 text-white">Nuevo club</a></div>

    <div class="overflow-hidden rounded-xl bg-white shadow-sm">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50"><tr><th class="px-4 py-3 text-left">Temporada</th><th class="px-4 py-3 text-left">Division</th><th class="px-4 py-3 text-left">Club</th><th class="px-4 py-3 text-left">Slug</th><th class="px-4 py-3 text-center">Estado</th><th class="px-4 py-3 text-center">Acciones</th></tr></thead>
            <tbody>
            @forelse($clubs as $club)
                <tr class="border-t">
                    <td class="px-4 py-3">{{ $club->season->year }}</td>
                    <td class="px-4 py-3">{{ $club->division->name }}</td>
                    <td class="px-4 py-3">{{ $club->name }}</td>
                    <td class="px-4 py-3">{{ $club->slug }}</td>
                    <td class="px-4 py-3 text-center">{!! $club->is_active ? '<span class="rounded bg-emerald-100 px-2 py-1 text-xs text-emerald-700">Activo</span>' : '<span class="rounded bg-slate-100 px-2 py-1 text-xs text-slate-700">Inactivo</span>' !!}</td>
                    <td class="px-4 py-3 text-center">
                        <a class="text-blue-600" href="{{ route('admin.clubs.edit', $club) }}">Editar</a>
                        <form class="inline" method="POST" action="{{ route('admin.clubs.destroy', $club) }}">@csrf @method('DELETE')<button class="ml-2 text-red-600" onclick="return confirm('Eliminar club?')">Eliminar</button></form>
                    </td>
                </tr>
            @empty
                <tr><td class="px-4 py-4 text-slate-500" colspan="6">Sin clubes.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $clubs->links() }}</div>
</x-admin-layout>
