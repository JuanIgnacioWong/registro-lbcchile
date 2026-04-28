<x-admin-layout>
    <x-slot name="heading">Detalle de Antecedente</x-slot>

    <div class="mb-4 rounded-xl bg-white p-4 shadow-sm">
        <p class="text-sm"><strong>Temporada:</strong> {{ $submission->season->year }} | <strong>Division:</strong> {{ $submission->division->name }} | <strong>Club:</strong> {{ $submission->club->name }}</p>
        <p class="text-sm"><strong>Responsable:</strong> {{ $submission->responsible_name }} | <strong>Telefono:</strong> {{ $submission->phone }} | <strong>Email:</strong> {{ $submission->email }}</p>
        <p class="text-sm"><strong>Pago:</strong> {{ $submission->payment_status }} | <strong>Version activa:</strong> {{ $submission->active_version ?? 'N/A' }}</p>
    </div>

    <div class="overflow-hidden rounded-xl bg-white shadow-sm">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50"><tr><th class="px-4 py-3 text-left">Version</th><th class="px-4 py-3 text-left">Estado</th><th class="px-4 py-3 text-left">Observaciones</th><th class="px-4 py-3 text-left">Archivos</th><th class="px-4 py-3 text-center">Acciones</th></tr></thead>
            <tbody>
                @foreach($submission->versions as $version)
                    <tr class="border-t align-top">
                        <td class="px-4 py-3">{{ $version->version_number }}</td>
                        <td class="px-4 py-3">{{ $version->status }}</td>
                        <td class="px-4 py-3">{{ $version->observations ?: '-' }}</td>
                        <td class="px-4 py-3 text-xs">
                            <a class="block underline" href="{{ route('admin.downloads.version', [$version, 'logo']) }}">Descargar logo</a>
                            <a class="block underline" href="{{ route('admin.downloads.version', [$version, 'receipt']) }}">Descargar comprobante</a>
                            <a class="block underline" href="{{ route('admin.downloads.version', [$version, 'roster']) }}">Descargar nomina</a>
                        </td>
                        <td class="px-4 py-3 text-center text-xs">
                            <form method="POST" action="{{ route('admin.submissions.versions.accept', $version) }}">@csrf<button class="text-emerald-700">Aceptar</button></form>
                            <form method="POST" action="{{ route('admin.submissions.versions.reject', $version) }}">@csrf<button class="mt-1 text-amber-700">Rechazar</button></form>
                            <form method="POST" action="{{ route('admin.submissions.versions.destroy', $version) }}" onsubmit="return confirm('Eliminar version?')">@csrf @method('DELETE')<button class="mt-1 text-red-700">Eliminar</button></form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-admin-layout>
