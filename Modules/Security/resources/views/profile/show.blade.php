@extends('shared::layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8">

    @if(session('success'))
    <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">{{ session('success') }}</div>
    @endif

    <div class="flex items-center gap-4">
        <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('dashboard.index') }}" class="border w-10 h-10 flex items-center justify-center rounded-full border-[#dfeee1] bg-[#173f34] hover:bg-[#1f4d3d] transition duration-200 translate-x-0 hover:-translate-x-1">
            <i class="fa fa-arrow-left text-md text-[#edf5ee]"></i>
        </a>
        <h1 class="text-xl font-semibold text-[#173f34]">Profil Saya</h1>
    </div>

    {{-- Info Akun --}}
    <div class="mt-6 bg-white shadow-md p-6">
        <div class="flex items-center gap-4">
            <x-shared::avatar
                :src="$user->employee?->photo ? asset('storage/' . $user->employee->photo) : null"
                :name="$user->employee?->name ?? $user->username"
                size="xl" />
            <div>
                <p class="text-lg font-semibold text-slate-800">{{ $user->employee?->name ?? $user->username }}</p>
                <p class="text-sm text-slate-500">{{ $user->username }} &middot; {{ $user->email }}</p>
                <div class="mt-1 flex flex-wrap gap-1">
                    @forelse($user->roles as $role)
                    <x-shared::badge variant="secondary" size="sm">{{ $role->name }}</x-shared::badge>
                    @empty
                    <span class="text-xs text-slate-400">Belum ada role</span>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="mt-6 grid grid-cols-1 gap-4 border-t border-slate-100 pt-6 sm:grid-cols-2">
            <div>
                <p class="text-xs text-slate-400">Login Terakhir</p>
                <p class="text-sm text-slate-700">{{ $user->last_login_at?->format('d M Y H:i') ?? '-' }}</p>
            </div>
            @if($user->employee)
            <div>
                <p class="text-xs text-slate-400">Nomor Pegawai</p>
                <p class="text-sm text-slate-700">{{ $user->employee->employee_number }}</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Ganti Password --}}
    <div class="mt-6 bg-white shadow-md p-6">
        <h3 class="font-semibold text-slate-800">Ganti Password</h3>

        <form method="POST" action="{{ route('profile.updatePassword') }}" class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
            @csrf @method('PUT')

            @unless($user->roles->contains('code', 'super-admin'))
            <div class="sm:col-span-2">
                <label class="mb-1 block text-sm font-medium text-slate-700">Password Saat Ini</label>
                <input type="password" name="current_password"
                    class="w-full rounded-lg border border-slate-200 py-2 px-3 text-sm focus:border-[#2a684f] focus:outline-none focus:ring-1 focus:ring-[#dfeee1]">
                @error('current_password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            @endunless

            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Password Baru</label>
                <input type="password" name="password"
                    class="w-full rounded-lg border border-slate-200 py-2 px-3 text-sm focus:border-[#2a684f] focus:outline-none focus:ring-1 focus:ring-[#dfeee1]">
                @error('password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation"
                    class="w-full rounded-lg border border-slate-200 py-2 px-3 text-sm focus:border-[#2a684f] focus:outline-none focus:ring-1 focus:ring-[#dfeee1]">
            </div>

            <div class="sm:col-span-2">
                <button type="submit" class="bg-[#1f4d3d] hover:bg-[#173f34] text-white px-5 py-2 rounded-full text-sm font-medium transition duration-200">
                    Simpan Password Baru
                </button>
            </div>
        </form>
    </div>

    {{-- Riwayat Login Terakhir --}}
    <div class="mt-6 bg-white shadow-md p-6">
        <h3 class="font-semibold text-slate-800">Aktivitas Login Terakhir</h3>

        <div class="mt-4 space-y-2">
            @forelse($recentLogins as $login)
            <div class="flex items-center justify-between rounded-lg bg-slate-50 px-3 py-2 text-sm">
                <span class="text-slate-600">{{ $login->ip_address }}</span>
                <span class="text-slate-400">{{ $login->created_at->format('d M Y H:i') }}</span>
            </div>
            @empty
            <p class="text-sm text-slate-400">Belum ada riwayat login.</p>
            @endforelse
        </div>
    </div>

</div>
@endsection