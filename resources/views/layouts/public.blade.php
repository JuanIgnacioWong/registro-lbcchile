@php
    $platformName = $sharedSettings['platform_name'] ?? 'Registro LBC Chile';
    $brandPrimary = $sharedSettings['brand_primary'] ?? '#0C2340';
    $brandSecondary = $sharedSettings['brand_secondary'] ?? '#1F4E8C';
    $brandAccent = $sharedSettings['brand_accent'] ?? '#35BDFE';
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? $platformName }}</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Archivo:wght@600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body
    class="min-h-screen bg-slate-100 font-sans text-slate-800"
    style="--brand-primary: {{ $brandPrimary }}; --brand-secondary: {{ $brandSecondary }}; --brand-accent: {{ $brandAccent }}; background-image: radial-gradient(circle at 0 0, rgba(53,189,254,0.18), transparent 40%), radial-gradient(circle at 100% 10%, rgba(31,78,140,0.20), transparent 35%);"
>
    <div class="min-h-screen">
        <header class="border-b border-slate-200/80 bg-white/90 backdrop-blur">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-sky-700">LBC Chile</p>
                    <h1 class="font-heading text-2xl font-extrabold text-primary sm:text-3xl">{{ $heading ?? 'Registro de Antecedentes' }}</h1>
                    <p class="mt-1 text-sm text-slate-600">{{ $subtitle ?? 'Plataforma independiente para recepción y revisión administrativa.' }}</p>
                </div>
                <div class="hidden rounded-2xl border border-sky-100 bg-sky-50 px-4 py-3 text-right text-xs text-sky-800 sm:block">
                    <p class="font-semibold">Proceso digital seguro</p>
                    <p>Historial por versiones y descargas protegidas</p>
                </div>
            </div>
        </header>

        <main class="mx-auto w-full max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            @if(session('success'))
                <x-alert type="success">{{ session('success') }}</x-alert>
            @endif

            @if($errors->any())
                <x-alert type="danger" title="Revisa los datos enviados">
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </x-alert>
            @endif

            {{ $slot }}
        </main>

        <footer class="border-t border-slate-200/80 bg-white/90 py-5 text-center text-xs text-slate-500">
            <p>{{ $platformName }} · Plataforma institucional de inscripción y correcciones deportivas</p>
        </footer>
    </div>
</body>
</html>
