<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;

use function Livewire\Volt\form;
use function Livewire\Volt\layout;

layout('layouts.guest');

form(LoginForm::class);

$login = function () {
    $this->validate();

    $this->form->authenticate();

    Session::regenerate();

    $this->redirectIntended(
        default: route('dashboard.index', absolute: false)
    );
};

?>

{{-- Login Card --}}
<div class="rounded-xl bg-white p-6 sm:p-8">

    {{-- Heading --}}
    <div class="flex flex-col items-center text-center">
        <div class="h-28 w-28 flex justify-center items-center rounded-full shadow-sky-300 shadow-lg p-2">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-full w-full object-fit">
        </div>

        <h1 class="font-['Fraunces'] mt-4 text-xl font-semibold leading-snug text-sky-800 sm:text-2xl">
            Absensi Rumah Sakit Umum<br>Kasih Insani
        </h1>
    </div>

    <div class="relative mx-auto mt-6 h-14 w-full max-w-[220px] overflow-hidden">
        <svg viewBox="0 0 220 60" class="h-full w-full" fill="none">

            <path
                d="M0 30 H70 L82 8 L94 50 L106 30 L116 38 L124 30 H220"
                stroke="#7dc9fc"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
                opacity="0.15" />
            <path
                d="M0 30 H70 L82 8 L94 50 L106 30 L116 38 L124 30 H220"
                stroke="#38bdf8"
                stroke-width="2.5"
                stroke-linecap="round"
                stroke-linejoin="round"
                class="pulse-line" />

        </svg>
    </div>

    {{-- Session Status --}}
    <x-auth-session-status
        class="mb-5"
        :status="session('status')" />

    {{-- Login Form --}}
    <form wire:submit="login" class="space-y-5">
        <div>
            <x-input-label
                for="login"
                value="Username"
                class="mb-1.5 block text-sm font-medium text-sky-400" />

            <x-text-input
                wire:model="form.login"
                id="login"
                class="block w-full rounded-lg border-sky-300 bg-white px-4 py-2.5 text-sm text-sky-600 shadow-sm transition"
                type="text"
                name="login"
                required
                autofocus
                autocomplete="username"
                placeholder="Masukkan username" />

            <x-input-error
                :messages="$errors->get('form.login')"
                class="mt-2" />
        </div>

        <div>
            <x-input-label
                for="password"
                value="Password"
                class="mb-1.5 block text-sm font-medium text-sky-400" />

            <x-text-input
                wire:model="form.password"
                id="password"
                class="block w-full rounded-lg border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-800 shadow-sm transition focus:border-slate-500 focus:ring-slate-500"
                type="password"
                name="password"
                required
                autocomplete="current-password"
                placeholder="Masukkan password" />

            <x-input-error
                :messages="$errors->get('form.password')"
                class="mt-2" />
        </div>

        {{-- Remember Me --}}
        <div class="flex items-center justify-between">

            <label
                for="remember"
                class="inline-flex cursor-pointer items-center gap-2">
                <input
                    wire:model="form.remember"
                    id="remember"
                    type="checkbox"
                    name="remember"
                    class="h-4 w-4 rounded border-slate-300 text-slate-700 shadow-sm focus:ring-slate-500">

                <span class="text-sm text-slate-600">
                    Ingat saya
                </span>
            </label>

        </div>

        {{-- Submit --}}
        <button
            type="submit"
            wire:loading.attr="disabled"
            wire:target="login"
            class="flex w-full items-center justify-center rounded-lg bg-slate-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-60">
            <span wire:loading.remove wire:target="login">
                Masuk
            </span>

            <span
                wire:loading
                wire:target="login"
                class="flex items-center gap-2">
                <svg
                    class="h-4 w-4 animate-spin"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24">
                    <circle
                        class="opacity-25"
                        cx="12"
                        cy="12"
                        r="10"
                        stroke="currentColor"
                        stroke-width="4"></circle>

                    <path
                        class="opacity-75"
                        fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>

                Memproses...
            </span>
        </button>

    </form>

    {{-- Register --}}
    <p class="mt-7 text-center text-sm text-slate-500">
        Belum punya akun?
        @if (Route::has('register'))
        <a href="{{ route('register') }}" class="font-semibold text-sky-700 transition hover:text-sky-950">
            Daftar di sini
        </a>
        @else
        <span class="cursor-not-allowed font-semibold text-slate-300" title="Fitur registrasi segera hadir">
            Daftar di sini
        </span>
        @endif
    </p>

</div>