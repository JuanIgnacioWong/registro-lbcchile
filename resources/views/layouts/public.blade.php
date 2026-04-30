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
    <div class="px-4 py-8 shadow-xl" style="background-color: #f54842;">
        <div class="mx-auto max-w-6xl">
            <h1 class="text-3xl font-bold tracking-tight">{{ $heading ?? 'Registro LBC Chile' }}</h1>
            <p class="mt-1 text-sm text-slate-100/90">{{ $subtitle ?? 'Plataforma independiente para carga y revision de antecedentes deportivos.' }}</p>
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

    <a
        href="https://github.com/JuanIgnacioWong"
        target="_blank"
        rel="noopener noreferrer"
        aria-label="GitHub JuanIgnacioWong"
        title="GitHub JuanIgnacioWong"
        style="position: fixed; right: 12px; bottom: 12px; z-index: 9999; background: rgba(15, 23, 42, 0.92); color: #ffffff; width: 34px; height: 34px; border-radius: 9999px; display: flex; align-items: center; justify-content: center; text-decoration: none; box-shadow: 0 8px 20px rgba(0,0,0,0.35);"
    >
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="18" height="18" fill="currentColor" aria-hidden="true">
            <path d="M8 0C3.58 0 0 3.67 0 8.2c0 3.62 2.29 6.69 5.47 7.77.4.08.55-.18.55-.39 0-.19-.01-.7-.01-1.37-2.23.5-2.7-.98-2.7-.98-.37-.96-.9-1.22-.9-1.22-.73-.52.06-.51.06-.51.81.06 1.23.86 1.23.86.72 1.27 1.88.9 2.34.69.07-.54.28-.9.51-1.1-1.78-.21-3.64-.92-3.64-4.08 0-.9.31-1.63.82-2.21-.08-.21-.36-1.05.08-2.18 0 0 .67-.22 2.2.84A7.37 7.37 0 0 1 8 4.8c.68 0 1.36.1 2 .3 1.52-1.06 2.19-.84 2.19-.84.44 1.13.16 1.97.08 2.18.51.58.82 1.31.82 2.21 0 3.17-1.87 3.87-3.65 4.08.29.26.54.76.54 1.54 0 1.11-.01 2-.01 2.27 0 .22.14.48.55.39C13.71 14.89 16 11.82 16 8.2 16 3.67 12.42 0 8 0Z"/>
        </svg>
    </a>
</body>
</html>
