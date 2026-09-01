@extends('shared::layouts.app')

@section('title', 'Riwayat Login')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8">

    <div class="flex items-center gap-4">
        <a href="{{ route('security.index') }}" class="border w-10 h-10 flex items-center justify-center rounded-full border-[#dfeee1] bg-[#1f4d3d] hover:bg-[#2a684f] transition duration-200 translate-x-0 hover:-translate-x-1">
            <i class="fa fa-arrow-left text-md text-[#edf5ee]"></i>
        </a>
        <h1 class="text-xl font-semibold text-[#1f4d3d]">Riwayat Login</h1>
    </div>

    <form method="GET" class="mt-6 flex flex-wrap items-end gap-3">
        <div>
            <label class="mb-1 block text-xs font-medium text-slate-500">Status</label>
            <select name="status" class="rounded-lg border border-slate-200 py-2 px-3 text-sm focus:border-[#2a684f] focus:outline-none focus:ring-1 focus:ring-[#dfeee1]">
                <option value="">Semua</option>
                <option value="success" @selected(request('status')==='success' )>Berhasil</option>
                <option value="failed" @selected(request('status')==='failed' )>Gagal</option>
            </select>
        </div>
        <div>
            <label class="mb-1 block text-xs font-medium text-slate-500">Dari Tanggal</label>
            <input type="date" name="start_date" value="{{ request('start_date') }}" class="rounded-lg border border-slate-200 py-2 px-3 text-sm focus:border-[#2a684f] focus:outline-none focus:ring-1 focus:ring-[#dfeee1]">
        </div>
        <div>
            <label class="mb-1 block text-xs font-medium text-slate-500">Sampai Tanggal</label>
            <input type="date" name="end_date" value="{{ request('end_date') }}" class="rounded-lg border border-slate-200 py-2 px-3 text-sm focus:border-[#2a684f] focus:outline-none focus:ring-1 focus:ring-[#dfeee1]">
        </div>
        <button type="submit" class="bg-[#1f4d3d] hover:bg-[#2a684f] text-white px-4 py-2 rounded-full text-sm font-medium transition duration-200">Filter</button>
        @if(request()->hasAny(['status', 'start_date', 'end_date']))
        <a href="{{ route('security.login-histories.index') }}" class="text-sm text-slate-500 hover:text-[#2a684f]">Reset</a>
        @endif
    </form>

    <div class="mt-4 bg-white shadow-md p-4">
        <div class="border border-gray-200 rounded-md grid grid-cols-5 gap-4 p-4 text-gray-700 font-semibold text-sm">
            <span>Username</span>
            <span>IP Address</span>
            <span>Status</span>
            <span>Keterangan</span>
            <span>Waktu</span>
        </div>

        @forelse($histories as $history)
        <div class="border border-gray-200 rounded-md grid grid-cols-5 gap-4 p-4 items-center text-gray-700 text-sm">
            <span class="font-medium text-slate-800">{{ $history->username_attempted }}</span>
            <span class="text-slate-500">{{ $history->ip_address }}</span>
            <span>
                @if($history->status === 'success')
                <x-shared::badge variant="success" dot>Berhasil</x-shared::badge>
                @else
                <x-shared::badge variant="danger" dot>Gagal</x-shared::badge>
                @endif
            </span>
            <span class="text-slate-500">
                @php
                $reasonLabels = [
                'invalid_password' => 'Password salah',
                'user_not_found' => 'Username tidak ditemukan',
                'account_inactive' => 'Akun nonaktif',
                ];
                @endphp
                {{ $reasonLabels[$history->failure_reason] ?? '-' }}
            </span>
            <span class="text-slate-500">{{ $history->created_at->format('d M Y H:i:s') }}</span>
        </div>
        @empty
        <div class="border border-gray-200 rounded-md p-10 text-center text-sm text-slate-400">Belum ada riwayat login.</div>
        @endforelse
    </div>

    <div class="mt-4">{{ $histories->links() }}</div>

</div>
@endsection
