<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link
        rel="icon"
        type="image/png"
        href="{{ asset('images/logo.jpeg') }}">
    <title>@yield('title', config('app.name')) - RS Bina Insani</title>

    @vite([
    'resources/css/app.css',
    'resources/js/app.js',
    ])

</head>

<body class="bg-slate-50">
    <div class="min-h-screen">
        @include('shared::partials.header')
        <div class="flex flex-col lg:flex-row min-h-screen mt-16 w-full">
            <aside class="w-full lg:w-64 shrink-0">
                @include('shared::partials.sidebar')
            </aside>
            <main class="flex-1 px-4 py-4">
                @yield('content')
            </main>
        </div>
    </div>
</body>

</html>