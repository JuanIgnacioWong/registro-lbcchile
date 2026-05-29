<x-admin-layout>
    <x-slot name="heading">Antecedentes</x-slot>

    <x-card title="Filtros" subtitle="Refina por temporada, división, club, estado y fecha.">
        <form method="GET" class="grid gap-3 md:grid-cols-3 xl:grid-cols-6" x-data="{ open: true }">
            <x-select label="Temporada" name="season_id">
                <option value="">Todas</option>
                @foreach($seasons as $season)
                    <option value="{{ $season->id }}" @selected(request('season_id') == $season->id)>{{ $season->year }}</option>
                @endforeach
            </x-select>

            <x-select label="División" name="division_id">
                <option value="">Todas</option>
                @foreach($divisions as $division)
                    <option value="{{ $division->id }}" @selected(request('division_id') == $division->id)>{{ $division->name }}</option>
                @endforeach
            </x-select>

            <x-select label="Club" name="club_id">
                <option value="">Todos</option>
                @foreach($clubs as $club)
                    <option value="{{ $club->id }}" @selected(request('club_id') == $club->id)>{{ $club->name }}</option>
                @endforeach
            </x-select>

            <x-select label="Pago" name="payment_status">
                <option value="">Todos</option>
                <option value="pending" @selected(request('payment_status') === 'pending')>Pendiente</option>
                <option value="in_review" @selected(request('payment_status') === 'in_review')>En revisión</option>
                <option value="paid" @selected(request('payment_status') === 'paid')>Pagado</option>
            </x-select>

            <x-select label="Estado envío" name="submission_status">
                <option value="">Todos</option>
                @foreach(['received','under_review','accepted','rejected','replaced'] as $status)
                    <option value="{{ $status }}" @selected(request('submission_status') === $status)>{{ $status }}</option>
                @endforeach
            </x-select>

            <x-input label="Texto libre" name="q" :value="request('q')" placeholder="Club, responsable, correo" />

            <x-input type="date" label="Desde" name="from_date" :value="request('from_date')" />
            <x-input type="date" label="Hasta" name="to_date" :value="request('to_date')" />

            <div class="md:col-span-3 xl:col-span-6 flex flex-wrap gap-2 pt-2">
                <x-button type="submit">Filtrar</x-button>
                <a class="btn-secondary" href="{{ route('admin.submissions.index') }}">Limpiar</a>
            </div>
        </form>
    </x-card>

    <x-card title="Listado de antecedentes">
        @if($submissions->isEmpty())
            <x-empty-state title="Sin antecedentes" description="No hay registros para los filtros seleccionados." />
        @else
            <x-table>
                <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-4 py-3">Año</th>
                        <th class="px-4 py-3">División</th>
                        <th class="px-4 py-3">Club</th>
                        <th class="px-4 py-3">Responsable</th>
                        <th class="px-4 py-3">Envíos</th>
                        <th class="px-4 py-3">Pago</th>
                        <th class="px-4 py-3">Estado</th>
                        <th class="px-4 py-3">Actualizado</th>
                        <th class="px-4 py-3 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($submissions as $submission)
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
                        <tr class="border-t border-slate-100 align-top">
                            <td class="px-4 py-3">{{ $submission->season->year }}</td>
                            <td class="px-4 py-3">{{ $submission->division->name }}</td>
                            <td class="px-4 py-3 font-medium">{{ $submission->club->name }}</td>
                            <td class="px-4 py-3 text-sm">
                                <p>{{ $submission->responsible_name }}</p>
                                <p class="text-slate-500">{{ $submission->email }}</p>
                            </td>
                            <td class="px-4 py-3">{{ $submission->versions_count }}/{{ $submission->max_allowed_submissions }}</td>
                            <td class="px-4 py-3">
                                <form method="POST" action="{{ route('admin.submissions.payment-status', $submission) }}" class="flex items-center gap-2">
                                    @csrf
                                    <select name="payment_status" class="rounded-xl border-slate-300 text-xs">
                                        <option value="pending" @selected($submission->payment_status === 'pending')>pending</option>
                                        <option value="in_review" @selected($submission->payment_status === 'in_review')>in_review</option>
                                        <option value="paid" @selected($submission->payment_status === 'paid')>paid</option>
                                    </select>
                                    <button class="rounded-lg bg-slate-800 px-2 py-1 text-xs font-semibold text-white">OK</button>
                                </form>
                                <x-badge class="mt-2" :tone="$paymentTone">{{ $submission->payment_status }}</x-badge>
                            </td>
                            <td class="px-4 py-3">
                                <x-badge :tone="$statusTone">{{ $latestStatus }}</x-badge>
                            </td>
                            <td class="px-4 py-3 text-slate-500">{{ $submission->updated_at?->format('Y-m-d H:i') }}</td>
                            <td class="px-4 py-3 text-right text-xs">
                                <a class="block text-sky-700 hover:underline" href="{{ route('admin.submissions.show', $submission) }}">Ver detalle</a>
                                <a class="mt-1 block text-slate-700 hover:underline" href="{{ route('admin.downloads.submission-all', $submission) }}">Descargar todo</a>
                                <form class="mt-2" method="POST" action="{{ route('admin.submissions.extra-slot', $submission) }}">
                                    @csrf
                                    <input type="hidden" name="reason" value="Habilitado desde listado de antecedentes">
                                    <button class="text-amber-700 hover:underline">Habilitar envío extra</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </x-table>
            <div class="mt-4">{{ $submissions->links() }}</div>
        @endif
    </x-card>
</x-admin-layout>
