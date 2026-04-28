<x-admin-layout>
    <x-slot name="heading">Historial de Auditoria</x-slot>

    <div class="overflow-hidden rounded-xl bg-white shadow-sm">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50"><tr><th class="px-4 py-3 text-left">Fecha</th><th class="px-4 py-3 text-left">Usuario</th><th class="px-4 py-3 text-left">Accion</th><th class="px-4 py-3 text-left">Entidad</th><th class="px-4 py-3 text-left">Descripcion</th></tr></thead>
            <tbody>
                @forelse($logs as $log)
                    <tr class="border-t">
                        <td class="px-4 py-3">{{ $log->created_at?->format('Y-m-d H:i') }}</td>
                        <td class="px-4 py-3">{{ $log->user?->name ?? 'Sistema' }}</td>
                        <td class="px-4 py-3">{{ $log->action }}</td>
                        <td class="px-4 py-3">{{ $log->entity_type }} #{{ $log->entity_id }}</td>
                        <td class="px-4 py-3">{{ $log->description }}</td>
                    </tr>
                @empty
                    <tr><td class="px-4 py-4 text-slate-500" colspan="5">Sin registros de auditoria.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $logs->links() }}</div>
</x-admin-layout>
