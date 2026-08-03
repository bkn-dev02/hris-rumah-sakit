@extends('shared::layouts.app')

@section('title', 'Rekap Absensi')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8">

    @if(session('success'))
    <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">{{ session('success') }}</div>
    @endif

    <h1 class="text-xl font-semibold text-blue-800">Rekap Absensi</h1>
    <p class="mt-1 text-sm text-slate-500">Data check-in/check-out dari aplikasi mobile.</p>

    {{-- Filter --}}
    <form method="GET" class="mt-6 flex flex-wrap items-end gap-3 bg-white shadow-md p-4">
        <div>
            <label class="mb-1 block text-xs font-medium text-slate-500">Dari Tanggal</label>
            <input type="date" name="start_date" value="{{ request('start_date') }}" class="rounded-lg border border-slate-200 py-2 px-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
        </div>
        <div>
            <label class="mb-1 block text-xs font-medium text-slate-500">Sampai Tanggal</label>
            <input type="date" name="end_date" value="{{ request('end_date') }}" class="rounded-lg border border-slate-200 py-2 px-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
        </div>
        <div>
            <label class="mb-1 block text-xs font-medium text-slate-500">Status</label>
            <select name="status_id" class="rounded-lg border border-slate-200 py-2 px-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                <option value="">Semua Status</option>
                @foreach($statuses as $status)
                <option value="{{ $status->id }}" @selected(request('status_id')==$status->id)>{{ $status->name }}</option>
                @endforeach
                <option value="unresolved" @selected(request('status_id')==='unresolved' )>Perlu Review</option>
            </select>
        </div>
        <button type="submit" class="bg-blue-900 hover:bg-blue-800 text-white px-4 py-2 rounded-full text-sm font-medium transition duration-200">Filter</button>
        @if(request()->hasAny(['start_date', 'end_date', 'status_id']))
        <a href="{{ route('attendance.attendances.index') }}" class="text-sm text-slate-500 hover:text-blue-600">Reset</a>
        @endif
    </form>

    <div class="mt-4 bg-white shadow-md p-4">
        <div class="border border-gray-200 rounded-md grid grid-cols-6 gap-4 p-4 text-gray-700 font-semibold text-sm">
            <span>Pegawai</span>
            <span>Tanggal</span>
            <span>Check-in</span>
            <span>Check-out</span>
            <span>Status</span>
            <span class="flex justify-center">Aksi</span>
        </div>

        @forelse($attendances as $attendance)
        <div class="border border-gray-200 rounded-md grid grid-cols-6 gap-4 p-4 items-center text-gray-700 text-sm">
            <span class="font-medium text-slate-800">{{ $attendance->employee->name }}</span>
            <span>{{ $attendance->work_date->format('d M Y') }}</span>
            <span>{{ $attendance->check_in_at?->format('H:i') ?? '—' }}</span>
            <span>{{ $attendance->check_out_at?->format('H:i') ?? '—' }}</span>
            <span>
                @if($attendance->status)
                <x-shared::badge variant="success" size="sm">{{ $attendance->status->name }}</x-shared::badge>
                @else
                <x-shared::badge variant="warning" size="sm">Perlu Review</x-shared::badge>
                @endif
            </span>
            <div class="flex justify-center">
                <a href="{{ route('attendance.attendances.show', $attendance->id) }}" class="bg-blue-900 hover:bg-blue-800 text-white rounded-full px-3 py-1 transition duration-200 flex items-center justify-center gap-1 hover:-translate-y-1 hover:shadow-lg">
                    <i class="fa fa-eye text-sm"></i> Detail
                </a>
            </div>
        </div>
        @empty
        <div class="border border-gray-200 rounded-md p-10 text-center text-sm text-slate-400">Belum ada data absensi.</div>
        @endforelse
    </div>

    <div class="mt-4">{{ $attendances->links() }}</div>

</div>
@endsection