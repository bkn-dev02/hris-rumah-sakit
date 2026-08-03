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
<div class="rounded-2xl bg-white p-6 sm:p-8">

    {{-- Heading --}}
    <div class="text-center mb-8">
        <h1 class="text-2xl font-bold tracking-tight text-sky-900">
            HRIS
        </h1>

        <p class="mt-1 text-sm text-sky-600">
            Human Resource Information System
        </p>
    </div>

    {{-- Session Status --}}
    <x-auth-session-status
        class="mb-5"
        :status="session('status')" />

    {{-- Login Form --}}
    <form wire:submit="login" class="space-y-5">

        {{-- Username / Email --}}
        <div>
            <x-input-label
                for="login"
                value="Username atau Email"
                class="mb-1.5 block text-sm font-medium text-slate-700" />

            <x-text-input
                wire:model="form.login"
                id="login"
                class="block w-full rounded-lg border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-800 shadow-sm transition focus:border-slate-500 focus:ring-slate-500"
                type="text"
                name="login"
                required
                autofocus
                autocomplete="username"
                placeholder="Masukkan username atau email" />

            <x-input-error
                :messages="$errors->get('form.login')"
                class="mt-2" />
        </div>

        {{-- Password --}}
        <div>
            <x-input-label
                for="password"
                value="Password"
                class="mb-1.5 block text-sm font-medium text-slate-700" />

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

</div>