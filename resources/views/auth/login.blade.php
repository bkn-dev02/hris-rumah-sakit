@extends('layouts.guest')

@section('content')
<div class="rounded-xl bg-white p-6 sm:p-8">
    <div class="flex flex-col items-center text-center">
        <div class="flex h-28 w-28 items-center justify-center rounded-full bg-[#edf5ee] p-2 shadow-lg shadow-[#bfe2c7]">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-full w-full object-fit">
        </div>

        <h1 class="mt-4 font-['Fraunces'] text-xl font-semibold leading-snug text-[#1f4d3d] sm:text-2xl">
            Absensi Rumah Sakit Umum<br>Kasih Insani
        </h1>
    </div>

    <div class="relative mx-auto mt-6 h-14 w-full max-w-[220px] overflow-hidden">
        <svg viewBox="0 0 220 60" class="h-full w-full" fill="none">
            <path d="M0 30 H70 L82 8 L94 50 L106 30 L116 38 L124 30 H220" stroke="#bfe2c7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" opacity="0.2" />
            <path d="M0 30 H70 L82 8 L94 50 L106 30 L116 38 L124 30 H220" stroke="#2a684f" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="pulse-line" />
        </svg>
    </div>

    @if (session('status'))
        <div class="mb-5 text-sm text-[#1f4d3d]">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-5 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-700">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <label for="login" class="mb-1.5 block text-sm font-medium text-[#2d5d4d]">Username</label>
            <input
                id="login"
                name="login"
                type="text"
                value="{{ old('login') }}"
                required
                autofocus
                autocomplete="username"
                placeholder="Masukkan username"
                class="block w-full rounded-lg border-[#cfe6d7] bg-white px-4 py-2.5 text-sm text-[#1f4d3d] shadow-sm transition focus:border-[#2a684f] focus:ring-[#2a684f]"
            >
        </div>

        <div>
            <label for="password" class="mb-1.5 block text-sm font-medium text-[#2d5d4d]">Password</label>
            <div x-data="{ showPassword: false }" class="relative">
                <input
                    id="password"
                    name="password"
                    type="password"
                    required
                    autocomplete="current-password"
                    placeholder="Masukkan password"
                    x-ref="passwordInput"
                    class="block w-full rounded-lg border-[#cfe6d7] bg-white px-4 py-2.5 pr-11 text-sm text-[#1f4d3d] shadow-sm transition focus:border-[#2a684f] focus:ring-[#2a684f]"
                >

                <button
                    type="button"
                    x-on:click="showPassword = !showPassword; $refs.passwordInput.type = showPassword ? 'text' : 'password'"
                    class="absolute inset-y-0 right-3 flex items-center text-[#2a684f] transition hover:text-[#1f4d3d]"
                    aria-label="Lihat atau sembunyikan password"
                >
                    <i class="fa-solid" x-bind:class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                </button>
            </div>
        </div>

        <div class="flex items-center justify-between">
            <label for="remember" class="inline-flex cursor-pointer items-center gap-2">
                <input id="remember" type="checkbox" name="remember" class="h-4 w-4 rounded border-[#cfe6d7] text-[#1f4d3d] shadow-sm focus:ring-[#2a684f]">
                <span class="text-sm text-[#425f55]">Ingat saya</span>
            </label>
        </div>

        <button type="submit" class="flex w-full items-center justify-center rounded-lg bg-[#1f4d3d] px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#173f34] focus:outline-none focus:ring-2 focus:ring-[#2a684f] focus:ring-offset-2">
            Masuk
        </button>
    </form>

    <p class="mt-7 text-center text-sm text-[#5a7269]">
        Belum punya akun?
        @if (Route::has('register'))
            <a href="{{ route('register') }}" class="font-semibold text-[#2a684f] transition hover:text-[#173f34]">Daftar di sini</a>
        @else
            <span class="cursor-not-allowed font-semibold text-[#a0b5ae]">Daftar di sini</span>
        @endif
    </p>
</div>
@endsection
