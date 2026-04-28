<x-admin-layout>
    <x-slot name="heading">Antecedentes</x-slot>

    <form method="GET" class="mb-4 grid gap-3 rounded-xl bg-white p-4 shadow-sm md:grid-cols-6">
        <div>
            <label class="text-xs text-slate-500">Temporada</label>
            <select name="season_id" class="mt-1 w-full rounded border-slate-300 text-sm">
                <option value="">Todas</option>
                @foreach($seasons as $season)
                    <option value="{{ $season->id }}" @selected(request('season_id') == $season->id)>{{ $season->year }}</option>
                @endforeach
            </select>
        </div>
        <div><label class="text-xs text-slate-500">Division ID</label><input name="division_id" value="{{ request('division_id') }}" class="mt-1 w-full rounded border-slate-300 text-sm"></div>
        <div><label class="text-xs text-slate-500">Club ID</label><input name="club_id" value="{{ request('club_id') }}" class="mt-1 w-full rounded border-slate-300 text-sm"></div>
        <div>
            <label class="text-xs text-slate-500">Pago</label>
            <select name="payment_status" class="mt-1 w-full rounded border-slate-300 text-sm">
                <option value="">Todos</option>
                <option value="pending" @selected(request('payment_status') === 'pending')>Pendiente</option>
                <option value="in_review" @selected(request('payment_status') === 'in_review')>En revision</option>
                <option value="paid" @selected(request('payment_status') === 'paid')>Pagado</option>
            </select>
        </div>
        <div><label class="text-xs text-slate-500">Desde</label><input type="date" name="from_date" value="{{ request('from_date') }}" class="mt-1 w-full rounded border-slate-300 text-sm"></div>
        <div><label class="text-xs text-slate-500">Hasta</label><input type="date" name="to_date" value="{{ request('to_date') }}" class="mt-1 w-full rounded border-slate-300 text-sm"></div>
        <div class="md:col-span-6 flex gap-2">
            <button class="rounded bg-slate-900 px-4 py-2 text-sm text-white">Filtrar</button>
            <a href="{{ route('admin.submissions.index') }}" class="rounded border border-slate-300 px-4 py-2 text-sm">Limpiar</a>
        </div>
    </form>

    <div class="overflow-hidden rounded-xl bg-white shadow-sm">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-4 py-3 text-left">Año</th>
                    <th class="px-4 py-3 text-left">Division</th>
                    <th class="px-4 py-3 text-left">Club</th>
                    <th class="px-4 py-3 text-left">Responsable</th>
                    <th class="px-4 py-3 text-center">Envios</th>
                    <th class="px-4 py-3 text-left">Pago</th>
                    <th class="px-4 py-3 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
            @forelse($submissions as $submission)
                <tr class="border-t align-top">
                    <td class="px-4 py-3">{{ $submission->season->year }}</td>
                    <td class="px-4 py-3">{{ $submission->division->name }}</td>
                    <td class="px-4 py-3">{{ $submission->club->name }}</td>
                    <td class="px-4 py-3">{{ $submission->responsible_name }}<br><span class="text-xs text-slate-500">{{ $submission->email }}</span></td>
                    <td class="px-4 py-3 text-center">{{ $submission->versions_count }}/{{ $submission->max_allowed_submissions }}</td>
                    <td class="px-4 py-3">
                        <form method="POST" action="{{ route('admin.submissions.payment-status', $submission) }}" class="flex gap-2">
                            @csrf
                            <select name="payment_status" class="rounded border-slate-300 text-sm">
                                <option value="pending" @selected($submission->payment_status === 'pending')>Pendiente</option>
                                <option value="in_review" @selected($submission->payment_status === 'in_review')>En revision</option>
                                <option value="paid" @selected($submission->payment_status === 'paid')>Pagado</option>
                            </select>
                            <button class="rounded bg-blue-600 px-2 py-1 text-white">OK</button>
                        </form>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <a href="{{ route('admin.submissions.show', $submission) }}" class="text-blue-600">Ver detalle</a>
                        <form class="mt-2" method="POST" action="{{ route('admin.submissions.extra-slot', $submission) }}">@csrf<button class="text-xs text-red-600">Habilitar envio extra</button></form>
                        <a class="mt-2 block text-xs text-slate-700 underline" href="{{ route('admin.downloads.submission-all', $submission) }}">Descargar todos</a>
                    </td>
                </tr>
            @empty
                <tr><td class="px-4 py-4 text-slate-500" colspan="7">Sin antecedentes.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $submissions->links() }}</div>
</x-admin-layout>
