<x-public-layout>
    <x-slot name="heading">{{ $settings['inscription_heading'] ?? 'Inscripcion de Clubes' }}</x-slot>
    <x-slot name="subtitle">{{ $settings['inscription_subheading'] ?? 'Plataforma independiente para carga y revision de antecedentes deportivos.' }}</x-slot>

    <div class="mb-6 rounded-xl bg-slate-900/70 p-4 shadow-lg">
        <div class="flex flex-wrap items-center gap-3">
            @if(!empty($settings['payment_button_url']))
                <a href="{{ $settings['payment_button_url'] }}" target="_blank" class="rounded bg-emerald-500 px-4 py-2 font-semibold text-slate-950 hover:bg-emerald-400">
                    {{ $settings['payment_button_text'] ?? 'Pagar cuota' }}
                </a>
            @endif
            <a href="{{ route('public.inscription.template') }}" class="rounded border border-slate-300 px-4 py-2 text-sm text-slate-100 hover:bg-slate-800">Descargar plantilla oficial de nomina</a>
        </div>
    </div>

    <form method="POST" enctype="multipart/form-data" action="{{ route('public.inscription.store') }}" class="rounded-2xl bg-white p-6 text-slate-800 shadow-xl"
          x-data="{
              seasonId: '{{ old('season_id') }}',
              divisionId: '{{ old('division_id') }}',
              clubId: '{{ old('club_id') }}',
              divisions: [],
              clubs: [],
              async loadDivisions() {
                this.divisionId = '';
                this.clubId = '';
                this.clubs = [];
                if (!this.seasonId) { this.divisions = []; return; }
                const response = await fetch(`/inscripcion/options/${this.seasonId}/divisiones`);
                const json = await response.json();
                this.divisions = json.data;
              },
              async loadClubs() {
                this.clubId = '';
                if (!this.seasonId || !this.divisionId) { this.clubs = []; return; }
                const response = await fetch(`/inscripcion/options/${this.seasonId}/${this.divisionId}/clubes`);
                const json = await response.json();
                this.clubs = json.data;
              }
          }"
          x-init="if (seasonId) { loadDivisions().then(() => { if (divisionId) loadClubs(); }) }">
        @csrf

        <h3 class="mb-4 text-xl font-bold">Formulario de antecedentes</h3>

        <div class="grid gap-4 md:grid-cols-3">
            <div>
                <label class="text-sm font-medium">Año / Temporada</label>
                <select name="season_id" x-model="seasonId" @change="loadDivisions()" class="mt-1 w-full rounded border-slate-300" required>
                    <option value="">Selecciona temporada</option>
                    @foreach($seasons as $season)
                        <option value="{{ $season->id }}">{{ $season->year }} - {{ $season->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-sm font-medium">Division o categoria</label>
                <select name="division_id" x-model="divisionId" @change="loadClubs()" class="mt-1 w-full rounded border-slate-300" required :disabled="!seasonId">
                    <option value="">Selecciona division</option>
                    <template x-for="division in divisions" :key="division.id">
                        <option :value="division.id" x-text="division.name"></option>
                    </template>
                </select>
            </div>

            <div>
                <label class="text-sm font-medium">Club</label>
                <select name="club_id" x-model="clubId" class="mt-1 w-full rounded border-slate-300" required :disabled="!divisionId || !clubs.length">
                    <option value="" x-text="divisionId && !clubs.length ? 'No hay clubes activos para esta division' : 'Selecciona club'"></option>
                    <template x-for="club in clubs" :key="club.id">
                        <option :value="club.id" x-text="club.name"></option>
                    </template>
                </select>
            </div>

            <div>
                <label class="text-sm font-medium">Nombre responsable</label>
                <input name="responsible_name" value="{{ old('responsible_name') }}" class="mt-1 w-full rounded border-slate-300" required>
            </div>

            <div>
                <label class="text-sm font-medium">Telefono</label>
                <input name="phone" value="{{ old('phone') }}" class="mt-1 w-full rounded border-slate-300" required>
            </div>

            <div>
                <label class="text-sm font-medium">Correo electronico</label>
                <input type="email" name="email" value="{{ old('email') }}" class="mt-1 w-full rounded border-slate-300" required>
            </div>
        </div>

        <div class="mt-6 grid gap-4 md:grid-cols-3">
            <div>
                <label class="text-sm font-medium">Logo del club (PNG/JPG/WEBP/SVG, max 2MB)</label>
                <input type="file" name="club_logo" accept=".png,.jpg,.jpeg,.webp,.svg" class="mt-1 w-full rounded border-slate-300" required>
            </div>
            <div>
                <label class="text-sm font-medium">Comprobante de pago (PDF/XLS/XLSX/DOCX, max 10MB)</label>
                <input type="file" name="payment_receipt" accept=".pdf,.xls,.xlsx,.docx" class="mt-1 w-full rounded border-slate-300" required>
            </div>
            <div>
                <label class="text-sm font-medium">Nomina de jugadores (PDF/XLS/XLSX/DOCX, max 10MB)</label>
                <input type="file" name="players_roster" accept=".pdf,.xls,.xlsx,.docx" class="mt-1 w-full rounded border-slate-300" required>
            </div>
        </div>

        <div class="mt-4">
            <label class="text-sm font-medium">Observaciones</label>
            <textarea name="observations" rows="4" class="mt-1 w-full rounded border-slate-300">{{ old('observations') }}</textarea>
        </div>

        <button class="mt-6 rounded bg-red-600 px-5 py-3 font-semibold text-white hover:bg-red-500">Enviar antecedentes</button>
    </form>
</x-public-layout>
