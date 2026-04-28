<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Panel LBC' }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=barlow:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-slate-100 font-[Barlow] text-slate-800" x-data="{sidebarOpen:false}">
    <div class="min-h-screen lg:flex">
        <aside class="fixed inset-y-0 left-0 z-40 w-72 transform bg-slate-900 text-slate-100 transition lg:static lg:translate-x-0"
               :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">
            <div class="border-b border-slate-700 px-6 py-5">
                <p class="text-xs uppercase tracking-widest text-slate-400">LBC Chile</p>
                <h1 class="text-xl font-bold">Panel de Registro</h1>
            </div>
            <nav class="space-y-1 p-4 text-sm">
                @php
                    $links = [
                        ['Dashboard', route('admin.dashboard'), 'admin.dashboard'],
                        ['Temporadas', route('admin.seasons.index'), 'admin.seasons.*'],
                        ['Divisiones / Categorias', route('admin.divisions.index'), 'admin.divisions.*'],
                        ['Clubes', route('admin.clubs.index'), 'admin.clubs.*'],
                        ['Antecedentes', route('admin.submissions.index'), 'admin.submissions.*'],
                        ['Pagos', route('admin.payments.index'), 'admin.payments.*'],
                        ['Correcciones', route('admin.corrections.index'), 'admin.corrections.*'],
                        ['Historial', route('admin.history.index'), 'admin.history.*'],
                        ['Configuracion', route('admin.settings.edit'), 'admin.settings.*'],
                        ['Usuarios', route('admin.users.index'), 'admin.users.*'],
                    ];
                @endphp

                @foreach($links as [$label, $href, $pattern])
                    <a href="{{ $href }}"
                       class="block rounded-lg px-3 py-2 {{ request()->routeIs($pattern) ? 'bg-red-600 text-white' : 'text-slate-200 hover:bg-slate-800' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </nav>
        </aside>

        <div class="flex-1">
            <header class="sticky top-0 z-30 border-b border-slate-200 bg-white/95 backdrop-blur">
                <div class="flex items-center justify-between px-4 py-3 lg:px-8">
                    <div class="flex items-center gap-3">
                        <button class="rounded-md border border-slate-300 px-2 py-1 lg:hidden" @click="sidebarOpen = !sidebarOpen">Menu</button>
                        <h2 class="text-lg font-semibold">{{ $heading ?? 'Panel Administrativo' }}</h2>
                    </div>
                    <div class="flex items-center gap-3 text-sm">
                        <span>{{ auth()->user()->name }}</span>
                        <a class="text-slate-500 underline" href="{{ route('profile.edit') }}">Perfil</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="rounded bg-slate-900 px-3 py-1 text-white" type="submit">Salir</button>
                        </form>
                    </div>
                </div>
            </header>

            <main class="p-4 lg:p-8">
                @if(session('success'))
                    <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-700">{{ session('success') }}</div>
                @endif

                @if($errors->any())
                    <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-700">
                        <ul class="list-disc pl-5">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{ $slot }}
            </main>
        </div>
    </div>

    @livewireScripts
</body>
</html>
