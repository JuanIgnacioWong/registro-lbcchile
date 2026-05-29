<x-public-layout :heading="$settings['inscription_heading'] ?? 'Inscipción de Clubes'" :subtitle="$settings['inscription_subheading'] ?? 'Plataforma independiente para carga y revisión de antecedentes deportivos.'">
    <div class="grid gap-6 lg:grid-cols-3">
        <x-card class="lg:col-span-2" title="{{ $settings['platform_name'] ?? 'Registro LBC Chile' }}" subtitle="Proceso oficial de recepción de antecedentes">
            <p class="text-sm text-slate-600">{{ $settings['inscription_intro_text'] ?? 'Completa la información requerida y adjunta los documentos oficiales del club.' }}</p>
            <div class="mt-4 flex flex-wrap gap-2">
                @if(!empty($settings['payment_button_url']))
                    <a href="{{ $settings['payment_button_url'] }}" target="_blank" rel="noopener" class="btn-primary">{{ $settings['payment_button_text'] ?? 'Pagar cuota' }}</a>
                @endif
                <a href="{{ route('public.inscription.template') }}" class="btn-secondary">Descargar plantilla de nómina</a>
            </div>
        </x-card>

        <x-card title="Proceso" subtitle="Pasos de carga">
            <ol class="space-y-2 text-sm text-slate-600">
                <li>1. Selecciona temporada, división y club.</li>
                <li>2. Ingresa responsable y contacto.</li>
                <li>3. Adjunta logo, comprobante y nómina.</li>
                <li>4. Confirma envío y espera revisión.</li>
            </ol>
        </x-card>
    </div>

    <x-card class="mt-6" title="Formulario de antecedentes" subtitle="Todos los campos son obligatorios salvo observaciones." x-data="{
        seasonId: '{{ old('season_id') }}',
        divisionId: '{{ old('division_id') }}',
        clubId: '{{ old('club_id') }}',
        divisions: [],
        clubs: [],
        loadingDivisions: false,
        loadingClubs: false,
        submitting: false,
        logoName: '',
        receiptName: '',
        rosterName: '',
        async loadDivisions() {
            this.divisionId = '';
            this.clubId = '';
            this.clubs = [];
            if (!this.seasonId) { this.divisions = []; return; }
            this.loadingDivisions = true;
            const response = await fetch(`/api/seasons/${this.seasonId}/divisions`);
            const json = await response.json();
            this.divisions = json.data || [];
            this.loadingDivisions = false;
        },
        async loadClubs() {
            this.clubId = '';
            if (!this.divisionId) { this.clubs = []; return; }
            this.loadingClubs = true;
            const response = await fetch(`/api/divisions/${this.divisionId}/clubs`);
            const json = await response.json();
            this.clubs = json.data || [];
            this.loadingClubs = false;
        },
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
    }" x-init="if (seasonId) { loadDivisions().then(() => { divisionId='{{ old('division_id') }}'; if (divisionId) loadClubs().then(() => clubId='{{ old('club_id') }}'); }) }">
        <form method="POST" enctype="multipart/form-data" action="{{ route('public.inscription.store') }}" class="space-y-8" @submit="submitOnce()">
            @csrf

            <div class="grid gap-4 md:grid-cols-3">
                <x-select label="Año / Temporada" name="season_id" x-model="seasonId" @change="loadDivisions()" required>
                    <option value="">Selecciona temporada</option>
                    @foreach($seasons as $season)
                        <option value="{{ $season->id }}">{{ $season->year }} - {{ $season->name }}</option>
                    @endforeach
                </x-select>

                <x-select label="División o categoría" name="division_id" x-model="divisionId" @change="loadClubs()" x-bind:disabled="!seasonId || loadingDivisions" required>
                    <option value="" x-text="loadingDivisions ? 'Cargando divisiones...' : 'Selecciona división'"></option>
                    <template x-for="division in divisions" :key="division.id">
                        <option :value="division.id" x-text="division.name"></option>
                    </template>
                </x-select>

                <x-select label="Club" name="club_id" x-model="clubId" x-bind:disabled="!divisionId || loadingClubs" required>
                    <option value="" x-text="loadingClubs ? 'Cargando clubes...' : 'Selecciona club'"></option>
                    <template x-for="club in clubs" :key="club.id">
                        <option :value="club.id" x-text="club.name"></option>
                    </template>
                </x-select>
            </div>

            <div class="grid gap-4 md:grid-cols-3">
                <x-input label="Nombre responsable" name="responsible_name" :value="old('responsible_name')" required />
                <x-input label="Teléfono" name="phone" :value="old('phone')" required />
                <x-input type="email" label="Correo electrónico" name="email" :value="old('email')" required />
            </div>

            <div class="grid gap-4 md:grid-cols-3">
                <div>
                    <x-file-input label="Logo del club" name="club_logo" accept=".png,.jpg,.jpeg,.webp,.svg" @change="setFileName('logo', $event)" required />
                    <p class="mt-1 text-xs text-slate-500">Formatos: PNG, JPG, JPEG, WEBP, SVG. Max 2 MB.</p>
                    <p class="text-xs font-medium text-slate-700" x-show="logoName" x-text="logoName"></p>
                </div>
                <div>
                    <x-file-input label="Comprobante de pago" name="payment_receipt" accept=".pdf,.xls,.xlsx,.docx" @change="setFileName('receipt', $event)" required />
                    <p class="mt-1 text-xs text-slate-500">PDF, XLS, XLSX, DOCX. Max {{ $settings['max_file_size_kb'] ?? '10240' }} KB.</p>
                    <p class="text-xs font-medium text-slate-700" x-show="receiptName" x-text="receiptName"></p>
                </div>
                <div>
                    <x-file-input label="Nómina de jugadores" name="players_roster" accept=".pdf,.xls,.xlsx,.docx" @change="setFileName('roster', $event)" required />
                    <p class="mt-1 text-xs text-slate-500">PDF, XLS, XLSX, DOCX. Max {{ $settings['max_file_size_kb'] ?? '10240' }} KB.</p>
                    <p class="text-xs font-medium text-slate-700" x-show="rosterName" x-text="rosterName"></p>
                </div>
            </div>

            <x-textarea label="Observaciones" name="observations" rows="4">{{ old('observations') }}</x-textarea>

            <div class="flex flex-wrap items-center gap-3">
                <x-button type="submit" x-bind:disabled="submitting" x-bind:class="submitting ? 'opacity-60 cursor-not-allowed' : ''">
                    <span x-show="!submitting">Enviar antecedentes</span>
                    <span x-show="submitting" style="display:none;">Enviando...</span>
                </x-button>
                <p class="text-xs text-slate-500">No cierres la página hasta recibir confirmación.</p>
            </div>
        </form>
    </x-card>
</x-public-layout>
