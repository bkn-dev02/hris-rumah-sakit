<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name') }}</title>

    @vite([
    'resources/css/app.css',
    'resources/js/app.js',
    ])

</head>

<body class="bg-slate-50">

    <div class="min-h-screen">

        {{-- Header --}}
        @include('shared::partials.header')

        <div class="flex justify-center m-auto min-h-screen mt-16 w-full">
            {{-- Sidebar --}}
            <div class="w-64">
                @include('shared::partials.sidebar')
            </div>

            {{-- Konten halaman --}}
            <main class="flex-1 px-2 py-2">
                @yield('content')
            </main>

        </div>

    </div>

</body>

</html>