```blade
@extends('shared::layouts.app')

@section('title', 'Security Module')

@section('content')

<div class="min-h-full bg-slate-50">

    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">

        {{-- ========================================================= HEADER ========================================================== --}}
        <div class="mb-8">

            <div class="flex items-start gap-4">

                {{-- Module Icon --}}
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-sky-100 text-sky-700 ring-1 ring-sky-200">

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.8"
                        stroke="currentColor"
                        class="h-6 w-6">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959
                            11.959 0 013.598 6 11.99 11.99 0 003
                            9.749c0 5.592 3.824 10.29 9
                            11.623 5.176-1.333 9-6.03
                            9-11.623a11.99 11.99 0
                            00-.598-3.749A11.959
                            11.959 0 0112 2.714Z" />
                    </svg>

                </div>

                <div>
                    {{-- Title --}}
                    <h1 class="text-2xl font-bold tracking-tight text-slate-800 sm:text-3xl">
                        Security & Account Management
                    </h1>

                    {{-- Label --}}
                    <div class="mb-1 flex items-center gap-2">

                        <span class="text-sm font-medium text-sky-700">
                            Kelola pengguna, peran, hak akses, serta keamanan aplikasi
                        </span>

                        <span class="h-1 w-1 rounded-full bg-sky-400"></span>

                    </div>

                </div>

            </div>

        </div>


        {{-- =========================================================
            MENU
        ========================================================== --}}
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-3">


            {{-- =====================================================
                USER MANAGEMENT
            ====================================================== --}}
            <a
                href="{{ route('security.users.index') }}"
                class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-sky-200 hover:shadow-xl hover:shadow-sky-100/50">

                {{-- Decorative Background --}}
                <div class="absolute -right-8 -top-8 h-24 w-24 rounded-full bg-sky-50 transition-transform duration-500 group-hover:scale-150">
                </div>

                <div class="relative">

                    {{-- Icon & Arrow --}}
                    <div class="flex items-start justify-between">

                        {{-- Icon --}}
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-sky-50 text-sky-700 ring-1 ring-sky-100 transition-all duration-300 group-hover:bg-sky-700 group-hover:text-white group-hover:shadow-lg group-hover:shadow-sky-700/20">

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.8"
                                stroke="currentColor"
                                class="h-6 w-6">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M17.982 18.725A7.488 7.488
                                    0 0012 15.75a7.488 7.488
                                    0 00-5.982 2.975m11.963
                                    0a9 9 0 10-11.963 0m11.963
                                    0A8.966 8.966 0 0112
                                    21a8.966 8.966 0 01-5.982-2.275M15
                                    9.75a3 3 0 11-6 0 3 3 0
                                    016 0Z" />
                            </svg>

                        </div>

                        {{-- Arrow --}}
                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-50 text-slate-400 transition-all duration-300 group-hover:bg-sky-50 group-hover:text-sky-700">

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.8"
                                stroke="currentColor"
                                class="h-4 w-4 transition-transform duration-300 group-hover:translate-x-0.5 group-hover:-translate-y-0.5">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M7.5 16.5L16.5 7.5m0 0H9m7.5 0V15" />
                            </svg>

                        </div>

                    </div>


                    {{-- Content --}}
                    <div class="mt-6">

                        <h2 class="text-lg font-semibold text-slate-800 transition-colors group-hover:text-sky-800">
                            User Management
                        </h2>

                        <p class="mt-2 text-sm leading-6 text-slate-500">
                            Kelola akun pengguna sistem, aktivasi akun,
                            reset password, serta hubungan akun dengan
                            data pegawai.
                        </p>

                    </div>


                    {{-- Footer --}}
                    <div class="mt-6 flex items-center gap-2 text-xs font-medium text-slate-400 transition-colors group-hover:text-sky-700">

                        <span>
                            Kelola pengguna
                        </span>

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="2"
                            stroke="currentColor"
                            class="h-3.5 w-3.5 transition-transform duration-300 group-hover:translate-x-1">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                        </svg>

                    </div>

                </div>

            </a>


            {{-- =====================================================
                ROLE MANAGEMENT
            ====================================================== --}}
            <a
                href="{{ route('security.roles.index') }}"
                class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-sky-200 hover:shadow-xl hover:shadow-sky-100/50">

                {{-- Decorative Background --}}
                <div class="absolute -right-8 -top-8 h-24 w-24 rounded-full bg-sky-50 transition-transform duration-500 group-hover:scale-150">
                </div>

                <div class="relative">

                    {{-- Icon & Arrow --}}
                    <div class="flex items-start justify-between">

                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-sky-50 text-sky-700 ring-1 ring-sky-100 transition-all duration-300 group-hover:bg-sky-700 group-hover:text-white group-hover:shadow-lg group-hover:shadow-sky-700/20">

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.8"
                                stroke="currentColor"
                                class="h-6 w-6">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M16.5 10.5V6.75a4.5
                                    4.5 0 10-9 0v3.75m-1.5
                                    0h12a1.5 1.5 0 011.5
                                    1.5v7.5a1.5 1.5 0
                                    01-1.5 1.5h-12A1.5
                                    1.5 0 014.5 19.5v-7.5A1.5
                                    1.5 0 016 10.5Z" />
                            </svg>

                        </div>

                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-50 text-slate-400 transition-all duration-300 group-hover:bg-sky-50 group-hover:text-sky-700">

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.8"
                                stroke="currentColor"
                                class="h-4 w-4 transition-transform duration-300 group-hover:translate-x-0.5 group-hover:-translate-y-0.5">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M7.5 16.5L16.5 7.5m0 0H9m7.5 0V15" />
                            </svg>

                        </div>

                    </div>


                    {{-- Content --}}
                    <div class="mt-6">

                        <h2 class="text-lg font-semibold text-slate-800 transition-colors group-hover:text-sky-800">
                            Role Management
                        </h2>

                        <p class="mt-2 text-sm leading-6 text-slate-500">
                            Mengatur peran pengguna sesuai struktur
                            organisasi dan kebutuhan akses sistem
                            rumah sakit.
                        </p>

                    </div>


                    {{-- Footer --}}
                    <div class="mt-6 flex items-center gap-2 text-xs font-medium text-slate-400 transition-colors group-hover:text-sky-700">

                        <span>
                            Kelola role
                        </span>

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="2"
                            stroke="currentColor"
                            class="h-3.5 w-3.5 transition-transform duration-300 group-hover:translate-x-1">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                        </svg>

                    </div>

                </div>

            </a>


            {{-- =====================================================
                PERMISSION MANAGEMENT
            ====================================================== --}}
            <a
                href="{{ route('security.permissions.index') }}"
                class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-sky-200 hover:shadow-xl hover:shadow-sky-100/50">

                {{-- Decorative Background --}}
                <div class="absolute -right-8 -top-8 h-24 w-24 rounded-full bg-sky-50 transition-transform duration-500 group-hover:scale-150">
                </div>

                <div class="relative">

                    {{-- Icon & Arrow --}}
                    <div class="flex items-start justify-between">

                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-sky-50 text-sky-700 ring-1 ring-sky-100 transition-all duration-300 group-hover:bg-sky-700 group-hover:text-white group-hover:shadow-lg group-hover:shadow-sky-700/20">

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.8"
                                stroke="currentColor"
                                class="h-6 w-6">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M15.75 5.25a3 3
                                    0 113 3m-3-3L8.25
                                    12.75m0 0a3 3 0
                                    104.243 4.243m-4.243
                                    -4.243L5.25 15.75" />
                            </svg>

                        </div>

                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-50 text-slate-400 transition-all duration-300 group-hover:bg-sky-50 group-hover:text-sky-700">

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.8"
                                stroke="currentColor"
                                class="h-4 w-4 transition-transform duration-300 group-hover:translate-x-0.5 group-hover:-translate-y-0.5">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M7.5 16.5L16.5 7.5m0 0H9m7.5 0V15" />
                            </svg>

                        </div>

                    </div>


                    {{-- Content --}}
                    <div class="mt-6">

                        <h2 class="text-lg font-semibold text-slate-800 transition-colors group-hover:text-sky-800">
                            Permission Management
                        </h2>

                        <p class="mt-2 text-sm leading-6 text-slate-500">
                            Menentukan hak akses pengguna terhadap
                            setiap menu dan fitur dalam sistem HRIS.
                        </p>

                    </div>


                    {{-- Footer --}}
                    <div class="mt-6 flex items-center gap-2 text-xs font-medium text-slate-400 transition-colors group-hover:text-sky-700">

                        <span>
                            Kelola permission
                        </span>

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="2"
                            stroke="currentColor"
                            class="h-3.5 w-3.5 transition-transform duration-300 group-hover:translate-x-1">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                        </svg>

                    </div>

                </div>

            </a>

            {{-- ===================================================== PROFILE SETTINGS ====================================================== --}}
            <a
                href="{{ route('profile.show') }}"
                class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-sky-200 hover:shadow-xl hover:shadow-sky-100/50">

                {{-- Decorative Background --}}
                <div class="absolute -right-8 -top-8 h-24 w-24 rounded-full bg-sky-50 transition-transform duration-500 group-hover:scale-150">
                </div>

                <div class="relative">

                    {{-- Icon & Arrow --}}
                    <div class="flex items-start justify-between">

                        {{-- Icon --}}
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-sky-50 text-sky-700 ring-1 ring-sky-100 transition-all duration-300 group-hover:bg-sky-700 group-hover:text-white group-hover:shadow-lg group-hover:shadow-sky-700/20">

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.8"
                                stroke="currentColor"
                                class="h-6 w-6">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M17.982 18.725A7.488 7.488
                        0 0012 15.75a7.488 7.488
                        0 00-5.982 2.975m11.963
                        0a9 9 0 10-11.963 0m11.963
                        0A8.966 8.966 0 0112
                        21a8.966 8.966 0 01-5.982-2.275M15
                        9.75a3 3 0 11-6 0
                        3 3 0 016 0Z" />
                            </svg>

                        </div>

                        {{-- Arrow --}}
                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-50 text-slate-400 transition-all duration-300 group-hover:bg-sky-50 group-hover:text-sky-700">

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.8"
                                stroke="currentColor"
                                class="h-4 w-4 transition-transform duration-300 group-hover:translate-x-0.5 group-hover:-translate-y-0.5">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M7.5 16.5L16.5 7.5m0 0H9m7.5 0V15" />
                            </svg>

                        </div>

                    </div>


                    {{-- Content --}}
                    <div class="mt-6">

                        <h2 class="text-lg font-semibold text-slate-800 transition-colors group-hover:text-sky-800">
                            Profile Settings
                        </h2>

                        <p class="mt-2 text-sm leading-6 text-slate-500">
                            Kelola informasi profil pribadi, foto, email,
                            password, dan pengaturan akun pengguna.
                        </p>

                    </div>


                    {{-- Footer --}}
                    <div class="mt-6 flex items-center gap-2 text-xs font-medium text-slate-400 transition-colors group-hover:text-sky-700">

                        <span>
                            Kelola profil
                        </span>

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="2"
                            stroke="currentColor"
                            class="h-3.5 w-3.5 transition-transform duration-300 group-hover:translate-x-1">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                        </svg>

                    </div>

                </div>

            </a>


            {{-- ===================================================== LOGIN HISTORY ====================================================== --}}
            <a
                href="{{ route('security.login-histories.index') }}"
                class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-sky-200 hover:shadow-xl hover:shadow-sky-100/50">

                {{-- Decorative Background --}}
                <div class="absolute -right-8 -top-8 h-24 w-24 rounded-full bg-sky-50 transition-transform duration-500 group-hover:scale-150">
                </div>

                <div class="relative">

                    {{-- Icon & Arrow --}}
                    <div class="flex items-start justify-between">

                        {{-- Icon --}}
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-sky-50 text-sky-700 ring-1 ring-sky-100 transition-all duration-300 group-hover:bg-sky-700 group-hover:text-white group-hover:shadow-lg group-hover:shadow-sky-700/20">

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.8"
                                stroke="currentColor"
                                class="h-6 w-6">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M12 6v6l4 2m6-2a10 10
                        0 11-20 0 10 10 0
                        0120 0Z" />
                            </svg>

                        </div>

                        {{-- Arrow --}}
                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-50 text-slate-400 transition-all duration-300 group-hover:bg-sky-50 group-hover:text-sky-700">

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.8"
                                stroke="currentColor"
                                class="h-4 w-4 transition-transform duration-300 group-hover:translate-x-0.5 group-hover:-translate-y-0.5">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M7.5 16.5L16.5 7.5m0 0H9m7.5 0V15" />
                            </svg>

                        </div>

                    </div>


                    {{-- Content --}}
                    <div class="mt-6">

                        <h2 class="text-lg font-semibold text-slate-800 transition-colors group-hover:text-sky-800">
                            Login History
                        </h2>

                        <p class="mt-2 text-sm leading-6 text-slate-500">
                            Lihat riwayat aktivitas login akun, perangkat,
                            alamat IP, waktu login, serta informasi keamanan lainnya.
                        </p>

                    </div>


                    {{-- Footer --}}
                    <div class="mt-6 flex items-center gap-2 text-xs font-medium text-slate-400 transition-colors group-hover:text-sky-700">

                        <span>
                            Lihat riwayat login
                        </span>

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="2"
                            stroke="currentColor"
                            class="h-3.5 w-3.5 transition-transform duration-300 group-hover:translate-x-1">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                        </svg>

                    </div>

                </div>

            </a>

        </div>

    </div>

</div>

@endsection