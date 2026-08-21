<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    <title>Absensi Rumah Sakit Umum Kasih Insani</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased">
    <div class="relative flex min-h-screen items-center justify-center overflow-hidden bg-[#eef5f2] px-4 py-8 sm:px-6 lg:px-8">
        <div class="pointer-events-none absolute -left-24 -top-24 h-72 w-72 rounded-full bg-sky-200/40 blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-32 -right-24 h-96 w-96 rounded-full bg-sky-200/35 blur-3xl"></div>
        <div class="relative w-full max-w-lg overflow-hidden rounded-[2rem] border border-white/80 bg-white/95 shadow-[0_24px_80px_rgba(22,101,73,0.14)] backdrop-blur sm:rounded-[2.25rem]">
            {{ $slot }}
        </div>
    </div>
</body>

</html>