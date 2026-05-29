<x-admin-layout>
    <x-slot name="heading">Historial de Auditoría</x-slot>

    <x-card title="Registro de acciones" subtitle="Eventos críticos de administración, pagos, correcciones y versiones.">
        @if($logs->isEmpty())
            <x-empty-state title="Sin registros de auditoría" description="Cuando se ejecuten acciones administrativas, aparecerán en esta tabla." />
        @else
            <x-table>
                <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-4 py-3">Fecha</th>
                        <th class="px-4 py-3">Usuario</th>
                        <th class="px-4 py-3">Acción</th>
                        <th class="px-4 py-3">Entidad</th>
                        <th class="px-4 py-3">Descripción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($logs as $log)
                        <tr class="border-t border-slate-100">
                            <td class="px-4 py-3 text-sm text-slate-600">{{ $log->created_at?->format('Y-m-d H:i:s') }}</td>
                            <td class="px-4 py-3">{{ $log->user?->name ?? 'Sistema' }}</td>
                            <td class="px-4 py-3 font-medium">{{ $log->action }}</td>
                            <td class="px-4 py-3">{{ $log->entity_type }}{{ $log->entity_id ? ' #'.$log->entity_id : '' }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $log->description }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </x-table>
            <div class="mt-4">{{ $logs->links() }}</div>
        @endif
    </x-card>
</x-admin-layout>
