<x-admin-layout>
    <x-slot name="heading">Dashboard General</x-slot>

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <x-kpi-card label="Total clubes" :value="$kpis['total_clubs']" />
        <x-kpi-card label="Antecedentes recibidos" :value="$kpis['submissions_received']" />
        <x-kpi-card label="Pagos pendientes" :value="$kpis['pending_payments']" />
        <x-kpi-card label="Pagos en revisión" :value="$kpis['in_review_payments']" />
        <x-kpi-card label="Pagados" :value="$kpis['paid_payments']" />
        <x-kpi-card label="Correcciones pendientes" :value="$kpis['pending_corrections']" />
        <x-kpi-card label="Envíos rechazados" :value="$kpis['rejected_versions']" />
        <x-kpi-card label="Envíos aceptados" :value="$kpis['accepted_versions']" />
    </div>

    <x-card title="Actividad reciente" subtitle="Resumen rápido de los últimos clubes con movimiento.">
        @if($recentSubmissions->isEmpty())
            <x-empty-state title="Sin actividad reciente" description="Cuando existan envíos, aparecerán aquí para revisión rápida." />
        @else
            <x-table>
                <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-4 py-3">Año</th>
                        <th class="px-4 py-3">División</th>
                        <th class="px-4 py-3">Club</th>
                        <th class="px-4 py-3">Envíos</th>
                        <th class="px-4 py-3">Pago</th>
                        <th class="px-4 py-3">Estado</th>
                        <th class="px-4 py-3">Actualización</th>
                        <th class="px-4 py-3 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentSubmissions as $submission)
                        @php
                            $latestStatus = $submission->versions->first()?->status ?? 'received';
                            $statusTone = match ($latestStatus) {
                                'accepted' => 'success',
                                'rejected' => 'danger',
                                'under_review' => 'warning',
                                default => 'info',
                            };
                            $paymentTone = match ($submission->payment_status) {
                                'paid' => 'success',
                                'in_review' => 'warning',
                                default => 'danger',
                            };
                        @endphp
                        <tr class="border-t border-slate-100">
                            <td class="px-4 py-3">{{ $submission->season->year }}</td>
                            <td class="px-4 py-3">{{ $submission->division->name }}</td>
                            <td class="px-4 py-3 font-medium">{{ $submission->club->name }}</td>
                            <td class="px-4 py-3">{{ $submission->versions_count }}/{{ $submission->max_allowed_submissions }}</td>
                            <td class="px-4 py-3"><x-badge :tone="$paymentTone">{{ $submission->payment_status }}</x-badge></td>
                            <td class="px-4 py-3"><x-badge :tone="$statusTone">{{ $latestStatus }}</x-badge></td>
                            <td class="px-4 py-3 text-slate-500">{{ $submission->updated_at?->format('Y-m-d H:i') }}</td>
                            <td class="px-4 py-3 text-right">
                                <a class="text-sky-700 hover:underline" href="{{ route('admin.submissions.show', $submission) }}">Ver detalle</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </x-table>
        @endif
    </x-card>
</x-admin-layout>
