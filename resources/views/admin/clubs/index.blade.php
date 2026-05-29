<x-admin-layout>
    <x-slot name="heading">Clubes</x-slot>

    <div class="flex justify-end">
        <a href="{{ route('admin.clubs.create') }}" class="btn-primary">Nuevo club</a>
    </div>

    <x-card>
        @if($clubs->isEmpty())
            <x-empty-state title="Sin clubes" description="Crea clubes activos para que aparezcan en inscripción pública." />
        @else
            <x-table>
                <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-4 py-3">Temporada</th>
                        <th class="px-4 py-3">División</th>
                        <th class="px-4 py-3">Club</th>
                        <th class="px-4 py-3">Slug</th>
                        <th class="px-4 py-3">Estado</th>
                        <th class="px-4 py-3 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($clubs as $club)
                        <tr class="border-t border-slate-100">
                            <td class="px-4 py-3">{{ $club->season->year }}</td>
                            <td class="px-4 py-3">{{ $club->division->name }}</td>
                            <td class="px-4 py-3 font-semibold">{{ $club->name }}</td>
                            <td class="px-4 py-3">{{ $club->slug }}</td>
                            <td class="px-4 py-3"><x-badge :tone="$club->is_active ? 'success' : 'muted'">{{ $club->is_active ? 'Activo' : 'Inactivo' }}</x-badge></td>
                            <td class="px-4 py-3 text-right text-sm">
                                <a href="{{ route('admin.clubs.edit', $club) }}" class="text-sky-700 hover:underline">Editar</a>
                                <form class="inline" method="POST" action="{{ route('admin.clubs.destroy', $club) }}" onsubmit="return confirm('¿Eliminar club?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="ml-2 text-red-700 hover:underline">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </x-table>
            <div class="mt-4">{{ $clubs->links() }}</div>
        @endif
    </x-card>
</x-admin-layout>
