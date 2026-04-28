<x-admin-layout>
    <x-slot name="heading">Temporadas</x-slot>

    <div class="mb-4 flex justify-end">
        <a href="{{ route('admin.seasons.create') }}" class="rounded bg-red-600 px-4 py-2 text-white">Nueva temporada</a>
    </div>

    <div class="overflow-hidden rounded-xl bg-white shadow-sm">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50"><tr><th class="px-4 py-3 text-left">Año</th><th class="px-4 py-3 text-left">Nombre</th><th class="px-4 py-3">Estado</th><th class="px-4 py-3">Acciones</th></tr></thead>
            <tbody>
            @forelse($seasons as $season)
                <tr class="border-t">
                    <td class="px-4 py-3">{{ $season->year }}</td>
                    <td class="px-4 py-3">{{ $season->name }}</td>
                    <td class="px-4 py-3 text-center">{!! $season->is_active ? '<span class="rounded bg-emerald-100 px-2 py-1 text-xs text-emerald-700">Activo</span>' : '<span class="rounded bg-slate-100 px-2 py-1 text-xs text-slate-700">Inactivo</span>' !!}</td>
                    <td class="px-4 py-3 text-center">
                        <a class="text-blue-600" href="{{ route('admin.seasons.edit', $season) }}">Editar</a>
                        <form class="inline" method="POST" action="{{ route('admin.seasons.destroy', $season) }}">@csrf @method('DELETE')<button class="ml-2 text-red-600" onclick="return confirm('Eliminar temporada?')">Eliminar</button></form>
                    </td>
                </tr>
            @empty
                <tr><td class="px-4 py-4 text-slate-500" colspan="4">Sin temporadas.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $seasons->links() }}</div>
</x-admin-layout>
