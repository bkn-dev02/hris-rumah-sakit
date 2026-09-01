@extends('shared::layouts.app')

@section('title', 'Dashboard Cuti')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8">

    <div class="mb-6 flex flex-col gap-4 rounded-xl bg-[#edf5ee] px-5 py-4 shadow-sm sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-3">
            <i class="fa-solid fa-calendar-days text-[#173f34]"></i>
            <h1 class="text-xl font-bold text-[#173f34]">Manajemen Cuti Pegawai</h1>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('leave.requests.index') }}"
                class="inline-flex items-center gap-2 rounded-lg bg-white px-4 py-2 text-sm font-medium text-[#173f34] shadow-sm transition hover:bg-[#f8fbf8]">
                <i class="fa-solid fa-file-signature text-xs"></i>
                Pengajuan Cuti
            </a>
            <a href="{{ route('leave.leave-types.index') }}"
                class="inline-flex items-center gap-2 rounded-lg bg-white px-4 py-2 text-sm font-medium text-[#173f34] shadow-sm transition hover:bg-[#f8fbf8]">
                <i class="fa-solid fa-list-ul text-xs"></i>
                Jenis Cuti
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 mb-6">
        <div class="rounded-xl border border-[#dfeee1] bg-white p-5 shadow-sm">
            <p class="text-xs text-slate-400">Menunggu Persetujuan</p>
            <p class="mt-1 text-2xl font-bold text-amber-600">{{ $totalPending }}</p>
        </div>
        <div class="rounded-xl border border-[#dfeee1] bg-white p-5 shadow-sm">
            <p class="text-xs text-slate-400">Disetujui Bulan Ini</p>
            <p class="mt-1 text-2xl font-bold text-emerald-600">{{ $totalApprovedThisMonth }}</p>
        </div>
        <div class="rounded-xl border border-[#dfeee1] bg-white p-5 shadow-sm">
            <p class="text-xs text-slate-400">Ditolak Bulan Ini</p>
            <p class="mt-1 text-2xl font-bold text-rose-600">{{ $totalRejectedThisMonth }}</p>
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
    @endphp

    <div class="mb-6 overflow-x-auto rounded-xl border border-[#dfeee1] bg-white p-2 shadow-sm">
        <nav class="flex min-w-max gap-1" aria-label="Filter status pengajuan cuti">
            @foreach ($statusTabs as $status => $label)
            <a href="{{ $status === 'all' ? route('leave.index') : route('leave.index', ['status' => $status]) }}"
                class="inline-flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium transition
                    {{ $activeStatus === $status
                        ? 'bg-[#1f4d3d] text-white shadow-sm'
                        : 'text-slate-600 hover:bg-[#f8fbf8] hover:text-[#1f4d3d]' }}">
                {{ $label }}
                <span class="rounded-full px-2 py-0.5 text-xs
                    {{ $activeStatus === $status ? 'bg-white/15 text-white' : 'bg-slate-100 text-slate-500' }}">
                    {{ $statusCounts[$status] ?? 0 }}
                </span>
            </a>
            @endforeach
        </nav>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3 mb-6">
        <div class="lg:col-span-2 rounded-xl border border-[#dfeee1] bg-white p-5 shadow-sm">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-sm font-bold text-[#173f34]">
                    {{ $activeStatus === 'all' ? 'Pengajuan Terbaru' : 'Pengajuan ' . $statusTabs[$activeStatus] }}
                </h2>
                <a href="{{ route('leave.requests.index') }}" class="text-xs font-medium text-[#2a684f] hover:underline">
                    Lihat Semua
                </a>
            </div>
            <div class="space-y-3">
                @forelse ($recentRequests as $req)
                <a href="{{ route('leave.show', $req) }}" class="block rounded-lg border border-[#dfeee1] p-3 hover:bg-[#f8fbf8] transition">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm font-semibold text-[#173f34]">{{ $req->employee?->name ?? 'Pegawai (nonaktif)' }}</p>
                            <p class="text-xs text-slate-400">{{ $req->leaveType?->name }} — {{ $req->start_date->format('d M Y') }}</p>
                        </div>
                        <span class="text-xs font-semibold px-2 py-1 rounded-full
                            {{ $req->status === 'approved' ? 'bg-emerald-50 text-emerald-700' :
                               ($req->status === 'rejected' ? 'bg-rose-50 text-rose-700' :
                               ($req->status === 'cancelled' ? 'bg-slate-100 text-slate-500' : 'bg-amber-50 text-amber-700')) }}">
                            {{ ucfirst($req->status) }}
                        </span>
                    </div>
                </a>
                @empty
                <p class="text-sm text-slate-400 text-center py-6">Belum ada pengajuan.</p>
                @endforelse
            </div>
        </div>

        <div class="space-y-6">
            <div class="rounded-xl border border-[#dfeee1] bg-white p-5 shadow-sm">
                <h2 class="mb-4 text-sm font-bold text-[#173f34]">Per Jenis Cuti (Tahun Ini)</h2>
                <div class="space-y-2">
                    @forelse ($byType as $type)
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-700">{{ $type->name }}</span>
                        <span class="font-semibold text-[#173f34]">{{ $type->total }}</span>
                    </div>
                    @empty
                    <p class="text-sm text-slate-400">Belum ada data.</p>
                    @endforelse
                </div>
            </div>

            {{-- Top pegawai --}}
            <div class="rounded-xl border border-[#dfeee1] bg-white p-5 shadow-sm">
                <h2 class="mb-4 text-sm font-bold text-[#173f34]">Pegawai Paling Sering Cuti</h2>
                <div class="space-y-3">
                    @forelse ($topEmployees as $index => $emp)
                    <div class="flex items-center gap-3">
                        <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-[#edf5ee] text-xs font-bold text-[#173f34]">
                            {{ $index + 1 }}
                        </span>
                        <img src="{{ $emp->photo ? asset('storage/' . $emp->photo) : asset('images/user.png') }}"
                            class="h-8 w-8 rounded-full object-cover" alt="{{ $emp->name }}">
                        <div class="flex-1 min-w-0">
                            <p class="truncate text-sm font-medium text-[#173f34]">{{ $emp->name }}</p>
                        </div>
                        <span class="text-sm font-semibold text-[#2a684f]">{{ $emp->total }}x</span>
                    </div>
                    @empty
                    <p class="text-sm text-slate-400">Belum ada data.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- Grafik bulanan --}}
    <div class="rounded-xl border border-[#dfeee1] bg-white p-5 shadow-sm">
        <h2 class="mb-4 text-sm font-bold text-[#173f34]">Tren Pengajuan Cuti {{ now()->year }}</h2>
        <canvas id="monthlyLeaveChart" height="90"></canvas>
    </div>

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    const ctx = document.getElementById('monthlyLeaveChart');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($monthlyLabels),
            datasets: [{
                label: 'Pengajuan Cuti',
                data: @json($monthlyCounts),
                borderColor: '#0369a1',
                backgroundColor: 'rgba(3, 105, 161, 0.1)',
                tension: 0.3,
                fill: true,
                pointBackgroundColor: '#0369a1',
                pointRadius: 4,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
</script>
@endpush
@endsection