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
        <div class="flex h-28 w-28 items-center justify-center rounded-full bg-[#edf5ee] p-2 shadow-lg shadow-[#bfe2c7]">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-full w-full object-fit">
        </div>

        <h1 class="mt-4 font-['Fraunces'] text-xl font-semibold leading-snug text-[#1f4d3d] sm:text-2xl">
            Absensi Rumah Sakit Umum<br>Kasih Insani
        </h1>
    </div>

    <div class="relative mx-auto mt-6 h-14 w-full max-w-[220px] overflow-hidden">
        <svg viewBox="0 0 220 60" class="h-full w-full" fill="none">

            <path
                d="M0 30 H70 L82 8 L94 50 L106 30 L116 38 L124 30 H220"
                stroke="#bfe2c7"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
                opacity="0.2" />
            <path
                d="M0 30 H70 L82 8 L94 50 L106 30 L116 38 L124 30 H220"
                stroke="#2a684f"
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
                class="mb-1.5 block text-sm font-medium text-[#2d5d4d]" />

            <x-text-input
                wire:model="form.login"
                id="login"
                class="block w-full rounded-lg border-[#cfe6d7] bg-white px-4 py-2.5 text-sm text-[#1f4d3d] shadow-sm transition focus:border-[#2a684f] focus:ring-[#2a684f]"
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
                class="mb-1.5 block text-sm font-medium text-[#2d5d4d]" />

            <div x-data="{ showPassword: false }" class="relative">
                <input
                    id="password"
                    type="password"
                    x-ref="passwordInput"
                    x-model="$wire.form.password"
                    class="block w-full rounded-lg border-[#cfe6d7] bg-white px-4 py-2.5 pr-11 text-sm text-[#1f4d3d] shadow-sm transition focus:border-[#2a684f] focus:ring-[#2a684f]"
                    name="password"
                    required
                    autocomplete="current-password"
                    placeholder="Masukkan password" />

                <button
                    type="button"
                    x-on:click="showPassword = !showPassword; $refs.passwordInput.type = showPassword ? 'text' : 'password'"
                    class="absolute inset-y-0 right-3 flex items-center text-[#2a684f] transition hover:text-[#1f4d3d]"
                    aria-label="Lihat atau sembunyikan password">
                    <i class="fa-solid" x-bind:class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                </button>
            </div>

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
                    class="h-4 w-4 rounded border-[#cfe6d7] text-[#1f4d3d] shadow-sm focus:ring-[#2a684f]">

                <span class="text-sm text-[#425f55]">
                    Ingat saya
                </span>
            </label>

        </div>

        {{-- Submit --}}
        <button
            type="submit"
            wire:loading.attr="disabled"
            wire:target="login"
            class="flex w-full items-center justify-center rounded-lg bg-[#1f4d3d] px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#173f34] focus:outline-none focus:ring-2 focus:ring-[#2a684f] focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-60">
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
    <p class="mt-7 text-center text-sm text-[#5a7269]">
        Belum punya akun?
        @if (Route::has('register'))
        <a href="{{ route('register') }}" class="font-semibold text-[#2a684f] transition hover:text-[#173f34]">
            Daftar di sini
        </a>
        @else
        <span class="cursor-not-allowed font-semibold text-[#a0b5ae]" title="Fitur registrasi segera hadir">
            Daftar di sini
        </span>
        @endif
    </p>

</div>