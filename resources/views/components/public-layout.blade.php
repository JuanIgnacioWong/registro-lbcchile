<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Registro LBC Chile' }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=barlow:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 font-[Barlow] text-slate-100">
    <div class="bg-gradient-to-r from-red-700 to-blue-700 px-4 py-8 shadow-xl">
        <div class="mx-auto max-w-6xl">
            <h1 class="text-3xl font-bold tracking-tight">{{ $heading ?? 'Registro LBC Chile' }}</h1>
            <p class="mt-1 text-sm text-slate-100/90">Plataforma independiente para carga y revision de antecedentes deportivos.</p>
        </div>
    </div>

    <main class="mx-auto max-w-6xl p-4 sm:p-6 lg:p-8">
        @if(session('success'))
            <div class="mb-4 rounded-lg border border-emerald-300 bg-emerald-100 px-4 py-3 text-emerald-800">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="mb-4 rounded-lg border border-red-300 bg-red-100 px-4 py-3 text-red-800">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{ $slot }}
    </main>
</body>
</html>
