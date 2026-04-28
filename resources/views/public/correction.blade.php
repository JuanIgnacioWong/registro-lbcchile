<x-public-layout>
    <x-slot name="heading">Correccion de Antecedentes</x-slot>

    <div class="mb-4 rounded-xl bg-slate-900/70 p-4">
        <p><strong>Temporada:</strong> {{ $link->season->year }}</p>
        <p><strong>Division:</strong> {{ $link->division->name }}</p>
        <p><strong>Club:</strong> {{ $link->club->name }}</p>
        <p><strong>Envios usados:</strong> {{ $submission->versions_count }} / {{ $submission->max_allowed_submissions }}</p>
    </div>

    <form method="POST" enctype="multipart/form-data" action="{{ route('public.corrections.store', [$link->season->year, $link->division->slug, $link->club->slug, $link->token]) }}" class="rounded-2xl bg-white p-6 text-slate-800 shadow-xl">
        @csrf
        <h3 class="mb-4 text-xl font-bold">Subir archivos corregidos</h3>

        <div class="grid gap-4 md:grid-cols-3">
            <div>
                <label class="text-sm font-medium">Logo del club (opcional)</label>
                <input type="file" name="club_logo" accept=".png,.jpg,.jpeg,.webp,.svg" class="mt-1 w-full rounded border-slate-300">
            </div>
            <div>
                <label class="text-sm font-medium">Comprobante de pago (opcional)</label>
                <input type="file" name="payment_receipt" accept=".pdf,.xls,.xlsx,.docx" class="mt-1 w-full rounded border-slate-300">
            </div>
            <div>
                <label class="text-sm font-medium">Nomina de jugadores (opcional)</label>
                <input type="file" name="players_roster" accept=".pdf,.xls,.xlsx,.docx" class="mt-1 w-full rounded border-slate-300">
            </div>
        </div>

        <div class="mt-4">
            <label class="text-sm font-medium">Observaciones</label>
            <textarea name="observations" rows="4" class="mt-1 w-full rounded border-slate-300">{{ old('observations') }}</textarea>
        </div>

        <button class="mt-6 rounded bg-red-600 px-5 py-3 font-semibold text-white hover:bg-red-500">Enviar correccion</button>
    </form>
</x-public-layout>
