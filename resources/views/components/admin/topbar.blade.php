@props(['heading' => 'Panel Administrativo'])

@php
    $platformName = $sharedSettings['platform_name'] ?? 'Registro LBC Chile';
@endphp

<header class="sticky top-0 z-20 border-b border-slate-200 bg-white/95 backdrop-blur">
    <div class="flex items-center justify-between px-4 py-3 sm:px-6 lg:px-8">
        <div class="flex items-center gap-3">
            <button type="button" class="btn-secondary px-3 py-1.5 lg:hidden" @click="sidebarOpen = !sidebarOpen">Menú</button>
            <div>
                <h2 class="font-heading text-xl font-bold text-primary">{{ $heading }}</h2>
                <p class="text-xs text-slate-500">{{ $platformName }}</p>
            </div>
        </div>
        <div class="flex items-center gap-3 text-sm">
            <span class="hidden text-slate-600 sm:inline">{{ auth()->user()->name }}</span>
            <a class="text-slate-500 underline" href="{{ route('profile.edit') }}">Perfil</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-button type="submit" variant="secondary">Salir</x-button>
            </form>
        </div>
    </div>
</header>
