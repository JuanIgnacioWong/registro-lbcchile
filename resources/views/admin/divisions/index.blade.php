<x-admin-layout>
    <x-slot name="heading">Divisiones</x-slot>

    <div class="flex justify-end">
        <a href="{{ route('admin.divisions.create') }}" class="btn-primary">Nueva división</a>
    </div>

    <x-card>
        @if($divisions->isEmpty())
            <x-empty-state title="Sin divisiones" description="Crea divisiones por temporada para habilitar clubes." />
        @else
            <x-table>
                <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-4 py-3">Temporada</th>
                        <th class="px-4 py-3">Nombre</th>
                        <th class="px-4 py-3">Slug</th>
                        <th class="px-4 py-3">Estado</th>
                        <th class="px-4 py-3 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($divisions as $division)
                        <tr class="border-t border-slate-100">
                            <td class="px-4 py-3">{{ $division->season->year }}</td>
                            <td class="px-4 py-3 font-semibold">{{ $division->name }}</td>
                            <td class="px-4 py-3">{{ $division->slug }}</td>
                            <td class="px-4 py-3"><x-badge :tone="$division->is_active ? 'success' : 'muted'">{{ $division->is_active ? 'Activa' : 'Inactiva' }}</x-badge></td>
                            <td class="px-4 py-3 text-right text-sm">
                                <a href="{{ route('admin.divisions.edit', $division) }}" class="text-sky-700 hover:underline">Editar</a>
                                <form class="inline" method="POST" action="{{ route('admin.divisions.destroy', $division) }}" onsubmit="return confirm('¿Eliminar división?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="ml-2 text-red-700 hover:underline">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </x-table>
            <div class="mt-4">{{ $divisions->links() }}</div>
        @endif
    </x-card>
</x-admin-layout>
