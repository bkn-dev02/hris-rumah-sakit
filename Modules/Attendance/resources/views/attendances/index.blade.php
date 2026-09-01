@extends('shared::layouts.app')

@section('title', 'Rekap Absensi')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8">

    @if(session('success'))
    <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">{{ session('success') }}</div>
    @endif

    <div class="flex items-center gap-2">
        <a href="{{ route('attendance.index') }}" class="w-10 h-10 flex items-center justify-center rounded-full bg-[#173f34] hover:bg-[#173f34] transition duration-200 translate-x-0 hover:-translate-x-1">
            <i class="fa fa-arrow-left text-md text-[#edf5ee]"></i>
        </a>
        <h1 class="text-xl font-bold text-[#1f4d3d]">Rekap Absensi</h1>
    </div>

    {{-- Filter --}}
    <form method="GET" class="mt-4 rounded-md bg-[#edf5ee] p-5 shadow-md border border-[#dfeee1]">
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">

            <div>
                <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-[#1f4d3d]">Dari Tanggal</label>
                <input type="date" name="start_date"
                    value="{{ request('start_date', now()->toDateString()) }}"
                    class="w-full rounded-xl border border-[#dfeee1] bg-white px-4 py-2.5 text-sm shadow-sm transition focus:border-[#2a684f] focus:outline-none focus:ring-2 focus:ring-[#dfeee1]">
            </div>

            <div>
                <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-[#1f4d3d]">Sampai Tanggal</label>
                <input type="date" name="end_date"
                    value="{{ request('end_date', now()->toDateString()) }}"
                    class="w-full rounded-xl border border-[#dfeee1] bg-white px-4 py-2.5 text-sm shadow-sm transition focus:border-[#2a684f] focus:outline-none focus:ring-2 focus:ring-[#dfeee1]">
            </div>

            <div>
                <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-[#1f4d3d]">Status</label>
                <select name="status_id" class="w-full rounded-xl border border-[#dfeee1] bg-white px-4 py-2.5 text-sm shadow-sm transition focus:border-[#2a684f] focus:outline-none focus:ring-2 focus:ring-[#dfeee1]">
                    <option value="">Semua Status</option>
                    @foreach($statuses as $status)
                    <option value="{{ $status->id }}" @selected(request('status_id')==$status->id)>{{ $status->name }}</option>
                    @endforeach
                    <option value="unresolved" @selected(request('status_id')==='unresolved' )>Perlu Review</option>
                </select>
            </div>

            <div class="flex items-end gap-3">
                <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-[#173f34] px-5 py-2.5 text-sm font-medium text-white shadow transition duration-200 hover:bg-[#173f34]">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    Filter
                </button>

                @if(request()->hasAny(['start_date', 'end_date', 'status_id']))
                <a href="{{ route('attendance.attendances.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-600 transition hover:border-red-300 hover:text-red-600">
                    <i class="fa-solid fa-rotate-left"></i>
                    Reset
                </a>
                @endif
            </div>

        </div>
    </form>

    {{-- List --}}
    <div class="space-y-3 bg-[#edf5ee] mt-4 p-4 rounded-lg">
        <div class="hidden lg:grid lg:grid-cols-12 lg:items-center rounded-lg bg-gradient-to-r from-[#173f34] to-[#2a684f] px-6 py-4 shadow-md">
            <div class="col-span-2 flex items-center gap-2 text-sm font-semibold uppercase tracking-wider text-white">
                <i class="fa-solid fa-calendar"></i><span>Tanggal</span>
            </div>
            <div class="col-span-3 flex items-center gap-2 text-sm font-semibold uppercase tracking-wider text-white">
                <i class="fa-solid fa-user"></i><span>Info Pegawai</span>
            </div>
            <div class="col-span-2 flex items-center gap-2 text-sm font-semibold uppercase tracking-wider text-white">
                <i class="fa-solid fa-right-to-bracket"></i><span>Jam Masuk</span>
            </div>
            <div class="col-span-2 flex items-center gap-2 text-sm font-semibold uppercase tracking-wider text-white">
                <i class="fa-solid fa-right-from-bracket"></i><span>Jam Pulang</span>
            </div>
            <div class="col-span-2 flex items-center gap-2 text-sm font-semibold uppercase tracking-wider text-white">
                <i class="fa-solid fa-circle-check"></i><span>Status</span>
            </div>
            <div class="col-span-1 text-end text-sm font-semibold uppercase tracking-wider text-white">
                <i class="fa-solid fa-gear"></i>
            </div>
        </div>

        @forelse($attendances as $attendance)
        <div class="rounded-xl border border-[#dfeee1] bg-white p-4 shadow-sm transition hover:border-[#dfeee1] hover:shadow-md">
            <div class="flex flex-col gap-4 lg:grid lg:grid-cols-12 lg:items-center">

                <div class="lg:col-span-2">
                    <p class="text-xs font-medium uppercase text-gray-400 lg:hidden">Tanggal</p>
                    <p class="font-semibold text-slate-700">{{ $attendance['work_date'] }}</p>
                </div>

                <div class="lg:col-span-3">
                    <p class="font-semibold text-[#173f34]">{{ $attendance['employee_name'] }}</p>
                    <p class="text-xs text-gray-500">{{ $attendance['employee_position_name'] ?? '-' }}</p>
                </div>

                <div class="flex items-center gap-3 lg:col-span-2">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-[#edf5ee] text-[#173f34] ring-2 ring-sky-400 overflow-hidden">
                        @if($attendance['check_in_photo_url'])
                        <img src="{{ $attendance['check_in_photo_url'] }}" alt="Foto check-in" class="h-full w-full object-cover">
                        @else
                        <i class="fa-solid fa-right-to-bracket text-sm"></i>
                        @endif
                    </div>
                    <p class="font-semibold text-gray-800">{{ $attendance['check_in_time'] }}</p>
                </div>

                <div class="flex items-center gap-3 lg:col-span-2">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-[#edf5ee] text-[#173f34] ring-2 ring-sky-400 overflow-hidden">
                        @if($attendance['check_out_photo_url'])
                        <img src="{{ $attendance['check_out_photo_url'] }}" alt="Foto check-out" class="h-full w-full object-cover">
                        @else
                        <i class="fa-solid fa-right-from-bracket text-sm"></i>
                        @endif
                    </div>
                    <p class="font-semibold text-gray-800">{{ $attendance['check_out_time'] }}</p>
                </div>

                <div class="lg:col-span-2">
                    <span class="inline-flex items-center rounded-full bg-{{ $attendance['badge_color'] }}-100 px-3 py-1 text-xs font-semibold text-{{ $attendance['badge_color'] }}-700">
                        <span class="mr-1.5 h-1.5 w-1.5 rounded-full bg-{{ $attendance['badge_color'] }}-500"></span>
                        {{ $attendance['badge_label'] }}
                    </span>
                </div>

                <div class="lg:col-span-1 lg:text-end">
                    <a href="{{ route('attendance.attendances.show', $attendance['id']) }}"
                        class="inline-flex items-center gap-2 rounded-lg bg-gradient-to-br from-[#173f34] via-[#1f4d3d] to-[#2a684f] hover:bg-gradient-to-tl px-4 py-2 text-sm font-medium text-white transition duration-200">
                        <i class="fa-solid fa-eye text-sm"></i>
                    </a>
                </div>

            </div>
        </div>
        @empty
        <div class="rounded-xl border border-[#dfeee1] bg-white p-10 text-center text-sm text-slate-400">
            <i class="fa-solid fa-folder-open mb-3 block text-3xl text-slate-300"></i>
            Belum ada data absensi.
        </div>
        @endforelse

    </div>

    <div class="mt-4">{{ $attendances->links() }}</div>

</div>
@endsection
