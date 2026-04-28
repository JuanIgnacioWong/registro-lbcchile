<div class="rounded-xl border border-gray-200 bg-white p-4">
    <div class="mb-3 flex items-center justify-between gap-3">
        <h3 class="text-sm font-semibold text-gray-900">Ultimos antecedentes</h3>
        <input
            type="text"
            wire:model.live.debounce.300ms="search"
            placeholder="Buscar club..."
            class="rounded-md border-gray-300 text-sm focus:border-red-500 focus:ring-red-500"
        >
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="border-b text-left text-gray-500">
                    <th class="px-2 py-2">Club</th>
                    <th class="px-2 py-2">Temporada</th>
                    <th class="px-2 py-2">Division</th>
                    <th class="px-2 py-2">Envios</th>
                    <th class="px-2 py-2">Pago</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($submissions as $submission)
                    <tr class="border-b last:border-b-0">
                        <td class="px-2 py-2 font-medium text-gray-800">{{ $submission->club->name }}</td>
                        <td class="px-2 py-2">{{ $submission->season->year }}</td>
                        <td class="px-2 py-2">{{ $submission->division->name }}</td>
                        <td class="px-2 py-2">{{ $submission->versions_count }}/{{ $submission->max_allowed_submissions }}</td>
                        <td class="px-2 py-2">{{ $submission->payment_status }}</td>
                    </tr>
                @empty
                    <tr><td class="px-2 py-3 text-gray-500" colspan="5">Sin resultados.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
