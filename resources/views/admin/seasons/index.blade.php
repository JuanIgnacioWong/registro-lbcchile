<x-admin-layout>
    <x-slot name="heading">Temporadas</x-slot>

    <div class="flex justify-end">
        <a href="{{ route('admin.seasons.create') }}" class="btn-primary">Nueva temporada</a>
    </div>

    <x-card>
        @if($seasons->isEmpty())
            <x-empty-state title="Sin temporadas" description="Crea la primera temporada para habilitar divisiones y clubes." />
        @else
            <x-table>
                <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-4 py-3">Año</th>
                        <th class="px-4 py-3">Nombre</th>
                        <th class="px-4 py-3">Estado</th>
                        <th class="px-4 py-3 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($seasons as $season)
                        <tr class="border-t border-slate-100">
                            <td class="px-4 py-3 font-semibold">{{ $season->year }}</td>
                            <td class="px-4 py-3">{{ $season->name }}</td>
                            <td class="px-4 py-3"><x-badge :tone="$season->is_active ? 'success' : 'muted'">{{ $season->is_active ? 'Activa' : 'Inactiva' }}</x-badge></td>
                            <td class="px-4 py-3 text-right text-sm">
                                <a href="{{ route('admin.seasons.edit', $season) }}" class="text-sky-700 hover:underline">Editar</a>
                                <form class="inline" method="POST" action="{{ route('admin.seasons.destroy', $season) }}" onsubmit="return confirm('¿Eliminar temporada?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="ml-2 text-red-700 hover:underline">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </x-table>
            <div class="mt-4">{{ $seasons->links() }}</div>
        @endif
    </x-card>
</x-admin-layout>
