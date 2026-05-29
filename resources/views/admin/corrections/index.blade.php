<x-admin-layout>
    <x-slot name="heading">Correcciones Seguras</x-slot>

    <x-card title="Generar enlace" subtitle="Crea enlaces controlados por temporada, división y club.">
        <form method="POST" action="{{ route('admin.corrections.store') }}" class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            @csrf

            <x-select label="Temporada" name="season_id" required>
                @foreach($seasons as $season)
                    <option value="{{ $season->id }}">{{ $season->year }} - {{ $season->name }}</option>
                @endforeach
            </x-select>

            <x-select label="División" name="division_id" required>
                @foreach($divisions as $division)
                    <option value="{{ $division->id }}">{{ $division->season->year }} - {{ $division->name }}</option>
                @endforeach
            </x-select>

            <x-select label="Club" name="club_id" required>
                @foreach($clubs as $club)
                    <option value="{{ $club->id }}">{{ $club->season->year }} - {{ $club->division->name }} - {{ $club->name }}</option>
                @endforeach
            </x-select>

            <x-input type="datetime-local" label="Expira en (opcional)" name="expires_at" />

            <div class="md:col-span-2 xl:col-span-4">
                <x-button type="submit">Generar enlace seguro</x-button>
            </div>
        </form>
    </x-card>

    <x-card title="Historial de enlaces" subtitle="Activa, desactiva o regenera tokens sin perder trazabilidad.">
        @if($links->isEmpty())
            <x-empty-state title="Sin enlaces" description="Aún no se han generado enlaces de corrección." />
        @else
            <x-table>
                <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-4 py-3">Año</th>
                        <th class="px-4 py-3">División</th>
                        <th class="px-4 py-3">Club</th>
                        <th class="px-4 py-3">Estado</th>
                        <th class="px-4 py-3">Expira</th>
                        <th class="px-4 py-3">URL</th>
                        <th class="px-4 py-3 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($links as $link)
                        @php
                            $url = route('public.corrections.create', [$link->season->year, $link->division->slug, $link->club->slug, $link->token]);
                        @endphp
                        <tr class="border-t border-slate-100 align-top" x-data="{ copied: false }">
                            <td class="px-4 py-3">{{ $link->season->year }}</td>
                            <td class="px-4 py-3">{{ $link->division->name }}</td>
                            <td class="px-4 py-3 font-medium">{{ $link->club->name }}</td>
                            <td class="px-4 py-3">
                                <x-badge :tone="$link->is_active ? 'success' : 'muted'">{{ $link->is_active ? 'Activo' : 'Inactivo' }}</x-badge>
                            </td>
                            <td class="px-4 py-3 text-xs text-slate-500">{{ $link->expires_at?->format('Y-m-d H:i') ?? 'Sin vencimiento' }}</td>
                            <td class="px-4 py-3 text-xs">
                                <div class="flex items-start gap-2">
                                    <input readonly value="{{ $url }}" class="w-full rounded-lg border-slate-300 bg-slate-50" />
                                    <button
                                        type="button"
                                        class="rounded-lg border border-slate-300 px-2 py-1 font-semibold text-slate-700"
                                        @click="navigator.clipboard.writeText('{{ $url }}'); copied=true; setTimeout(()=>copied=false,1500)">
                                        Copiar
                                    </button>
                                </div>
                                <p x-show="copied" x-transition class="mt-1 text-emerald-700">Enlace copiado</p>
                            </td>
                            <td class="px-4 py-3 text-right text-xs">
                                <form method="POST" action="{{ route('admin.corrections.toggle', $link) }}" class="inline">
                                    @csrf
                                    <button class="rounded-lg bg-slate-800 px-2 py-1 font-semibold text-white">{{ $link->is_active ? 'Desactivar' : 'Activar' }}</button>
                                </form>
                                <form method="POST" action="{{ route('admin.corrections.regenerate', $link) }}" class="inline">
                                    @csrf
                                    <button class="ml-1 rounded-lg bg-amber-500 px-2 py-1 font-semibold text-white">Regenerar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </x-table>
            <div class="mt-4">{{ $links->links() }}</div>
        @endif
    </x-card>
</x-admin-layout>
