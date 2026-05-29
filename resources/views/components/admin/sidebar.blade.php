@php
    $platformName = $sharedSettings['platform_name'] ?? 'Registro LBC Chile';
@endphp

<aside class="fixed inset-y-0 left-0 z-40 w-72 -translate-x-full bg-primary text-slate-100 shadow-soft transition lg:static lg:translate-x-0"
       :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">
    <div class="border-b border-slate-700 px-6 py-6">
        <p class="text-xs uppercase tracking-[0.24em] text-slate-300">LBC Chile</p>
        <h1 class="mt-2 font-heading text-2xl font-extrabold">{{ $platformName }}</h1>
    </div>

    @php
        $links = [
            ['Dashboard', route('admin.dashboard'), 'admin.dashboard'],
            ['Temporadas', route('admin.seasons.index'), 'admin.seasons.*'],
            ['Divisiones', route('admin.divisions.index'), 'admin.divisions.*'],
            ['Clubes', route('admin.clubs.index'), 'admin.clubs.*'],
            ['Antecedentes', route('admin.submissions.index'), 'admin.submissions.*'],
            ['Pagos', route('admin.payments.index'), 'admin.payments.*'],
            ['Correcciones', route('admin.corrections.index'), 'admin.corrections.*'],
            ['Historial', route('admin.history.index'), 'admin.history.*'],
            ['Configuración', route('admin.settings.edit'), 'admin.settings.*'],
            ['Usuarios', route('admin.users.index'), 'admin.users.*'],
        ];
    @endphp

    <nav class="space-y-1 p-4 text-sm">
        @foreach($links as [$label, $href, $pattern])
            <a href="{{ $href }}"
               class="block rounded-xl px-3 py-2.5 font-medium transition {{ request()->routeIs($pattern) ? 'bg-sky-500 text-white' : 'text-slate-200 hover:bg-slate-800/70' }}">
                {{ $label }}
            </a>
        @endforeach
    </nav>
</aside>

<div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 z-30 bg-slate-900/40 lg:hidden" @click="sidebarOpen=false" style="display:none;"></div>
