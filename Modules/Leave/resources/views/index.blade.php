@extends('shared::layouts.app')

@section('title', 'Pengajuan Cuti')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8">

    {{-- Header --}}
    <div class="mb-6 flex items-center justify-between rounded-xl bg-sky-100 px-5 py-4 shadow-sm">
        <div class="flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white text-sky-950 shadow-sm">
                <i class="fa-solid fa-file-signature"></i>
            </div>
            <div>
                <h1 class="text-xl font-bold text-sky-950">Pengajuan Cuti</h1>
                <p class="mt-0.5 text-xs text-slate-500">Daftar seluruh pengajuan cuti karyawan</p>
            </div>
        </div>
    </div>

    @php
    $statusTabs = [
    'all' => 'Semua',
    'pending' => 'Menunggu',
    'approved' => 'Disetujui',
    'rejected' => 'Ditolak',
    'cancelled' => 'Dibatalkan',
    ];
    $activeStatus = request('status', 'all');
    @endphp

    <div class="mb-5 overflow-x-auto rounded-xl border border-sky-200 bg-white p-2 shadow-sm">
        <nav class="flex min-w-max gap-1" aria-label="Filter status pengajuan cuti">
            @foreach ($statusTabs as $status => $label)
            @php
            $tabQuery = request()->except(['status', 'page']);
            if ($status !== 'all') {
            $tabQuery['status'] = $status;
            }
            @endphp
            <a href="{{ route('leave.requests.index', $tabQuery) }}"
                class="inline-flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium transition
                    {{ $activeStatus === $status
                        ? 'bg-sky-950 text-white shadow-sm'
                        : 'text-slate-600 hover:bg-sky-50 hover:text-sky-950' }}">
                {{ $label }}
                <span class="rounded-full px-2 py-0.5 text-xs
                    {{ $activeStatus === $status ? 'bg-white/15 text-white' : 'bg-slate-100 text-slate-500' }}">
                    {{ $statusCounts[$status] ?? 0 }}
                </span>
            </a>
            @endforeach
        </nav>
    </div>

    {{-- Filter --}}
    <form method="GET"
        class="mb-5 rounded-xl border border-slate-200 bg-white p-4 shadow-sm">

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-12 lg:items-end">

            {{-- Cari Karyawan --}}
            <div class="lg:col-span-3">
                <label for="employee_search" class="mb-1.5 block text-xs font-semibold text-slate-600">
                    Cari Karyawan
                </label>

                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <i class="fa-solid fa-magnifying-glass text-xs text-slate-400"></i>
                    </div>

                    <input
                        id="employee_search"
                        type="text"
                        name="employee_search"
                        value="{{ request('employee_search') }}"
                        placeholder="Nama karyawan..."
                        class="w-full rounded-lg border border-slate-300 bg-white py-2.5 pl-9 pr-3 text-sm text-slate-700 placeholder:text-slate-400 focus:border-sky-500 focus:ring-2 focus:ring-sky-500/20">
                </div>
            </div>

            {{-- Status --}}
            <div class="lg:col-span-3">
                <label for="status" class="mb-1.5 block text-xs font-semibold text-slate-600">
                    Status
                </label>

                <select
                    id="status"
                    name="status"
                    class="w-full rounded-lg border border-slate-300 bg-white py-2.5 text-sm text-slate-700 focus:border-sky-500 focus:ring-2 focus:ring-sky-500/20">
                    <option value="">Semua Status</option>
                    <option value="pending" @selected(request('status')==='pending' )>
                        Menunggu Persetujuan
                    </option>
                    <option value="approved" @selected(request('status')==='approved' )>
                        Disetujui
                    </option>
                    <option value="rejected" @selected(request('status')==='rejected' )>
                        Ditolak
                    </option>
                    <option value="cancelled" @selected(request('status')==='cancelled' )>
                        Dibatalkan
                    </option>
                </select>
            </div>

            {{-- Jenis Cuti --}}
            <div class="lg:col-span-3">
                <label for="leave_type_id" class="mb-1.5 block text-xs font-semibold text-slate-600">
                    Jenis Cuti
                </label>

                <select
                    id="leave_type_id"
                    name="leave_type_id"
                    class="w-full rounded-lg border border-slate-300 bg-white py-2.5 text-sm text-slate-700 focus:border-sky-500 focus:ring-2 focus:ring-sky-500/20">
                    <option value="">Semua Jenis</option>

                    @foreach ($leaveTypes as $leaveType)
                    <option
                        value="{{ $leaveType->id }}"
                        @selected(request('leave_type_id')==$leaveType->id)
                        >
                        {{ $leaveType->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            {{-- Tahun --}}
            <div class="lg:col-span-2">
                <label for="year" class="mb-1.5 block text-xs font-semibold text-slate-600">
                    Tahun
                </label>

                <select
                    id="year"
                    name="year"
                    class="w-full rounded-lg border border-slate-300 bg-white py-2.5 text-sm text-slate-700 focus:border-sky-500 focus:ring-2 focus:ring-sky-500/20">
                    <option value="">Semua Tahun</option>

                    @for ($y = now()->year + 1; $y >= now()->year - 2; $y--)
                    <option value="{{ $y }}" @selected(request('year')==$y)>
                        {{ $y }}
                    </option>
                    @endfor
                </select>
            </div>

            {{-- Tombol Filter --}}
            <div class="lg:col-span-1">
                <button
                    type="submit"
                    class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-sky-950 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-900 focus:outline-none focus:ring-2 focus:ring-sky-500/30">
                    <i class="fa-solid fa-filter text-xs"></i>
                    <span class="lg:hidden">Filter</span>
                </button>
            </div>

            {{-- Reset Filter --}}
            @if (request()->anyFilled(['status', 'leave_type_id', 'year', 'employee_search']))
            <div class="lg:col-span-12 flex justify-end border-t border-slate-100 pt-3">
                <a
                    href="{{ route('leave.index') }}"
                    class="inline-flex items-center gap-1.5 text-xs font-medium text-slate-500 transition hover:text-sky-700">
                    <i class="fa-solid fa-rotate-left text-[10px]"></i>
                    Reset Filter
                </a>
            </div>
            @endif

        </div>
    </form>

    {{-- Table --}}
    <div class="overflow-hidden rounded-2xl border border-sky-200 bg-sky-100 p-4 shadow-md">

        <div class="hidden lg:grid lg:grid-cols-12 lg:items-center rounded-xl bg-gradient-to-r from-sky-950 to-sky-800 px-5 py-4 shadow-md">
            <div class="col-span-3 flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-white">
                <i class="fa-solid fa-user"></i>
                <span>Karyawan</span>
            </div>
            <div class="col-span-2 flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-white">
                <i class="fa-solid fa-tag"></i>
                <span>Jenis Cuti</span>
            </div>
            <div class="col-span-3 flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-white">
                <i class="fa-solid fa-calendar"></i>
                <span>Tanggal</span>
            </div>
            <div class="col-span-2 flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-white">
                <i class="fa-solid fa-circle-check"></i>
                <span>Status</span>
            </div>
            <div class="col-span-2 flex items-center justify-end gap-2 text-xs font-semibold uppercase tracking-wider text-white">
                <i class="fa-solid fa-gear"></i>
                <span>Aksi</span>
            </div>
        </div>

        <div class="mt-3 space-y-3">
            @forelse ($leaveRequests as $leaveRequest)
            <div class="rounded-xl border border-sky-200 bg-white p-4 shadow-sm transition duration-200 hover:border-sky-300 hover:shadow-md">
                <div class="grid grid-cols-1 gap-4 lg:grid-cols-12 lg:items-center">

                    <div class="lg:col-span-3">
                        <p class="mb-1 text-xs font-medium uppercase tracking-wide text-slate-400 lg:hidden">Karyawan</p>
                        <p class="font-semibold text-sky-950">{{ $leaveRequest->employee->name }}</p>
                        <p class="text-xs text-slate-400">{{ $leaveRequest->employee->profession }}</p>
                    </div>

                    <div class="lg:col-span-2">
                        <p class="mb-1 text-xs font-medium uppercase tracking-wide text-slate-400 lg:hidden">Jenis Cuti</p>
                        <span class="inline-flex items-center rounded-lg bg-sky-100 px-3 py-1.5 text-sm font-semibold text-sky-950">
                            {{ $leaveRequest->leaveType->name }}
                        </span>
                    </div>

                    <div class="lg:col-span-3">
                        <p class="mb-1 text-xs font-medium uppercase tracking-wide text-slate-400 lg:hidden">Tanggal</p>
                        <p class="text-sm text-slate-700">
                            {{ $leaveRequest->start_date->format('d M Y') }} - {{ $leaveRequest->end_date->format('d M Y') }}
                        </p>
                        <p class="text-xs text-sky-400">({{ $leaveRequest->total_days }} hari kerja)</p>
                    </div>

                    <div class="lg:col-span-2">
                        <p class="mb-1 text-xs font-medium uppercase tracking-wide text-slate-400 lg:hidden">Status</p>
                        @php
                        $statusColor = match ($leaveRequest->status) {
                        'approved' => 'emerald',
                        'rejected' => 'rose',
                        'cancelled' => 'slate',
                        default => 'amber',
                        };
                        @endphp
                        <span class="inline-flex items-center gap-1.5 rounded-lg bg-{{ $statusColor }}-50 px-3 py-1 text-xs font-semibold text-{{ $statusColor }}-700">
                            <span class="h-1.5 w-1.5 rounded-full bg-{{ $statusColor }}-500"></span>
                            {{ $leaveRequest->statusLabel() }}
                        </span>
                    </div>

                    <div class="flex items-center justify-start gap-2 lg:col-span-2 lg:justify-end">
                        <a href="{{ route('leave.show', $leaveRequest) }}"
                            class="inline-flex items-center gap-1.5 rounded-lg bg-sky-100 px-3 py-2 text-xs font-semibold text-sky-900 transition duration-200 hover:bg-sky-200 hover:-translate-y-0.5">
                            <i class="fa-solid fa-eye text-[11px]"></i>
                            Detail
                        </a>
                    </div>

                </div>
            </div>
            @empty
            <div class="rounded-xl border border-sky-200 bg-white p-10 text-center shadow-sm">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-sky-100 text-sky-800">
                    <i class="fa-solid fa-file-circle-xmark text-xl"></i>
                </div>
                <p class="mt-4 text-sm font-medium text-slate-600">Belum ada pengajuan cuti.</p>
            </div>
            @endforelse
        </div>
    </div>

    <div class="mt-4">
        {{ $leaveRequests->links() }}
    </div>

</div>
@endsection