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
    <title>{{ $title ?? 'Panel '.$platformName }}</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Archivo:wght@600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body
    class="bg-muted min-h-screen font-sans text-slate-800"
    style="--brand-primary: {{ $brandPrimary }}; --brand-secondary: {{ $brandSecondary }}; --brand-accent: {{ $brandAccent }};"
    x-data="{ sidebarOpen: false }"
>
    <div class="min-h-screen lg:flex">
        <x-admin.sidebar />

        <div class="min-w-0 flex-1">
            <x-admin.topbar :heading="$heading ?? 'Panel Administrativo'" />

            <main class="space-y-6 p-4 sm:p-6 lg:p-8">
                @if(session('success'))
                    <x-alert type="success">{{ session('success') }}</x-alert>
                @endif

                @if($errors->any())
                    <x-alert type="danger" title="Se encontraron validaciones pendientes">
                        <ul class="list-disc pl-5">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </x-alert>
                @endif

                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>
