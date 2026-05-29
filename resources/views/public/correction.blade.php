<x-public-layout heading="Corrección de antecedentes" subtitle="Carga controlada de archivos corregidos por enlace seguro.">
    <x-card title="Contexto de corrección" subtitle="Datos bloqueados del enlace actual">
        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4 text-sm">
            <div><p class="text-slate-500">Temporada</p><p class="font-semibold">{{ $link->season->year }}</p></div>
            <div><p class="text-slate-500">División</p><p class="font-semibold">{{ $link->division->name }}</p></div>
            <div><p class="text-slate-500">Club</p><p class="font-semibold">{{ $link->club->name }}</p></div>
            <div><p class="text-slate-500">Cupos usados</p><p class="font-semibold">{{ $submission->versions_count }} / {{ $submission->max_allowed_submissions }}</p></div>
        </div>
        <p class="mt-4 text-sm text-slate-600">{{ $settings['correction_instructions'] ?? 'Sube solo los archivos que deseas corregir. El sistema mantiene historial completo por versión.' }}</p>
    </x-card>

    <x-card class="mt-6" title="Subida de archivos corregidos" x-data="{
        submitting: false,
        logoName: '',
        receiptName: '',
        rosterName: '',
        setFileName(type, event) {
            const fileName = event.target.files[0]?.name || '';
            if (type === 'logo') this.logoName = fileName;
            if (type === 'receipt') this.receiptName = fileName;
            if (type === 'roster') this.rosterName = fileName;
        },
        submitOnce() {
            if (this.submitting) return false;
            this.submitting = true;
            return true;
        }
    }">
        <x-alert type="warning" title="Importante">
            Este formulario corresponde a una corrección oficial. Si no adjuntas al menos un archivo, el envío será rechazado.
        </x-alert>

        <form method="POST" enctype="multipart/form-data" action="{{ route('public.corrections.store', [$link->season->year, $link->division->slug, $link->club->slug, $link->token]) }}" class="space-y-6" @submit="submitOnce()">
            @csrf

            <div class="grid gap-4 md:grid-cols-3">
                <div>
                    <x-file-input label="Logo del club (opcional)" name="club_logo" accept=".png,.jpg,.jpeg,.webp,.svg" @change="setFileName('logo', $event)" />
                    <p class="text-xs font-medium text-slate-700" x-show="logoName" x-text="logoName"></p>
                </div>
                <div>
                    <x-file-input label="Comprobante de pago (opcional)" name="payment_receipt" accept=".pdf,.xls,.xlsx,.docx" @change="setFileName('receipt', $event)" />
                    <p class="text-xs font-medium text-slate-700" x-show="receiptName" x-text="receiptName"></p>
                </div>
                <div>
                    <x-file-input label="Nómina de jugadores (opcional)" name="players_roster" accept=".pdf,.xls,.xlsx,.docx" @change="setFileName('roster', $event)" />
                    <p class="text-xs font-medium text-slate-700" x-show="rosterName" x-text="rosterName"></p>
                </div>
            </div>

            <x-textarea label="Observaciones" name="observations" rows="4">{{ old('observations') }}</x-textarea>

            <div class="flex items-center gap-3">
                <x-button type="submit" x-bind:disabled="submitting" x-bind:class="submitting ? 'opacity-60 cursor-not-allowed' : ''">
                    <span x-show="!submitting">Enviar corrección</span>
                    <span x-show="submitting" style="display:none;">Enviando...</span>
                </x-button>
                <p class="text-xs text-slate-500">El sistema conservará esta carga como nueva versión histórica.</p>
            </div>
        </form>
    </x-card>
</x-public-layout>
