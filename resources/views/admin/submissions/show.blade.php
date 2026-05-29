<x-admin-layout>
    <x-slot name="heading">Detalle de Antecedente</x-slot>

    <x-breadcrumb :items="[
        ['label' => 'Antecedentes', 'href' => route('admin.submissions.index')],
        ['label' => 'Detalle #'.$submission->id],
    ]" />

    <x-card>
        <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-4 text-sm">
            <div><p class="text-slate-500">Temporada</p><p class="font-semibold">{{ $submission->season->year }} · {{ $submission->season->name }}</p></div>
            <div><p class="text-slate-500">División</p><p class="font-semibold">{{ $submission->division->name }}</p></div>
            <div><p class="text-slate-500">Club</p><p class="font-semibold">{{ $submission->club->name }}</p></div>
            <div><p class="text-slate-500">Versión activa</p><p class="font-semibold">{{ $submission->active_version ?? 'N/A' }}</p></div>
            <div><p class="text-slate-500">Responsable</p><p class="font-semibold">{{ $submission->responsible_name }}</p></div>
            <div><p class="text-slate-500">Teléfono</p><p class="font-semibold">{{ $submission->phone }}</p></div>
            <div><p class="text-slate-500">Email</p><p class="font-semibold">{{ $submission->email }}</p></div>
            <div><p class="text-slate-500">Pago</p><p class="font-semibold">{{ $submission->payment_status }}</p></div>
        </div>
    </x-card>

    <x-card title="Historial de versiones" subtitle="Cada versión conserva archivos y trazabilidad completa.">
        @if($submission->versions->isEmpty())
            <x-empty-state title="Sin versiones" description="Todavía no hay archivos cargados para este antecedente." />
        @else
            <x-table>
                <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-4 py-3">Versión</th>
                        <th class="px-4 py-3">Estado</th>
                        <th class="px-4 py-3">Observaciones</th>
                        <th class="px-4 py-3">Archivos</th>
                        <th class="px-4 py-3 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($submission->versions as $version)
                        @php
                            $statusTone = match ($version->status) {
                                'accepted' => 'success',
                                'rejected' => 'danger',
                                'under_review' => 'warning',
                                'replaced' => 'muted',
                                default => 'info',
                            };
                        @endphp
                        <tr class="border-t border-slate-100 align-top">
                            <td class="px-4 py-3 font-semibold">{{ $version->version_number }}</td>
                            <td class="px-4 py-3"><x-badge :tone="$statusTone">{{ $version->status }}</x-badge></td>
                            <td class="px-4 py-3 text-sm text-slate-600">{{ $version->observations ?: '-' }}</td>
                            <td class="px-4 py-3 text-xs">
                                <a class="block text-sky-700 hover:underline" href="{{ route('admin.downloads.version', [$version, 'logo']) }}">Descargar logo</a>
                                <a class="block text-sky-700 hover:underline" href="{{ route('admin.downloads.version', [$version, 'receipt']) }}">Descargar comprobante</a>
                                <a class="block text-sky-700 hover:underline" href="{{ route('admin.downloads.version', [$version, 'roster']) }}">Descargar nómina</a>
                            </td>
                            <td class="px-4 py-3 text-right text-xs">
                                <form method="POST" action="{{ route('admin.submissions.versions.accept', $version) }}" class="inline">
                                    @csrf
                                    <button class="rounded-lg bg-emerald-600 px-2 py-1 font-semibold text-white">Aceptar</button>
                                </form>
                                <form method="POST" action="{{ route('admin.submissions.versions.reject', $version) }}" class="inline">
                                    @csrf
                                    <button class="ml-1 rounded-lg bg-amber-500 px-2 py-1 font-semibold text-white">Rechazar</button>
                                </form>
                                <form method="POST" action="{{ route('admin.submissions.versions.destroy', $version) }}" class="inline" onsubmit="return confirm('¿Eliminar versión? Esta acción se audita.')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="ml-1 rounded-lg bg-red-600 px-2 py-1 font-semibold text-white">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </x-table>
        @endif
    </x-card>
</x-admin-layout>
