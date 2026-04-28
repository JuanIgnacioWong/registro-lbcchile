<x-admin-layout>
    <x-slot name="heading">Divisiones / Categorias</x-slot>

    <div class="mb-4 flex justify-end"><a href="{{ route('admin.divisions.create') }}" class="rounded bg-red-600 px-4 py-2 text-white">Nueva division</a></div>

    <div class="overflow-hidden rounded-xl bg-white shadow-sm">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50"><tr><th class="px-4 py-3 text-left">Temporada</th><th class="px-4 py-3 text-left">Nombre</th><th class="px-4 py-3 text-left">Slug</th><th class="px-4 py-3 text-center">Estado</th><th class="px-4 py-3 text-center">Acciones</th></tr></thead>
            <tbody>
            @forelse($divisions as $division)
                <tr class="border-t">
                    <td class="px-4 py-3">{{ $division->season->year }}</td>
                    <td class="px-4 py-3">{{ $division->name }}</td>
                    <td class="px-4 py-3">{{ $division->slug }}</td>
                    <td class="px-4 py-3 text-center">{!! $division->is_active ? '<span class="rounded bg-emerald-100 px-2 py-1 text-xs text-emerald-700">Activa</span>' : '<span class="rounded bg-slate-100 px-2 py-1 text-xs text-slate-700">Inactiva</span>' !!}</td>
                    <td class="px-4 py-3 text-center">
                        <a class="text-blue-600" href="{{ route('admin.divisions.edit', $division) }}">Editar</a>
                        <form class="inline" method="POST" action="{{ route('admin.divisions.destroy', $division) }}">@csrf @method('DELETE')<button class="ml-2 text-red-600" onclick="return confirm('Eliminar division?')">Eliminar</button></form>
                    </td>
                </tr>
            @empty
                <tr><td class="px-4 py-4 text-slate-500" colspan="5">Sin divisiones.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $divisions->links() }}</div>
</x-admin-layout>
