@extends('shared::layouts.app')

@section('title', 'Presensi Darurat')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8">

    <div class="bg-gradient-to-r from-sky-950 to-sky-800 rounded-t-2xl px-6 py-4 flex items-center gap-3 shadow-md">
        <i class="fas fa-triangle-exclamation text-amber-300"></i>
        <h1 class="text-white font-semibold text-lg">Presensi Darurat</h1>
    </div>

    <div class="bg-white rounded-b-2xl shadow-md p-4">
        {{-- Tabs --}}
        <div class="flex items-center justify-between flex-wrap gap-3 mb-4">
            <div class="flex gap-1 border-b border-slate-100">
                <a href="{{ route('attendance.emergency.index', ['tab' => 'pending', 'department_id' => $departmentId]) }}"
                    class="px-4 py-2.5 text-sm font-medium border-b-2 transition
                        {{ $tab === 'pending' ? 'border-amber-500 text-amber-700' : 'border-transparent text-slate-400 hover:text-slate-600' }}">
                    Menunggu Persetujuan
                </a>
                <a href="{{ route('attendance.emergency.index', ['tab' => 'rejected', 'department_id' => $departmentId]) }}"
                    class="px-4 py-2.5 text-sm font-medium border-b-2 transition
                        {{ $tab === 'rejected' ? 'border-rose-500 text-rose-700' : 'border-transparent text-slate-400 hover:text-slate-600' }}">
                    Ditolak
                </a>
                <a href="{{ route('attendance.emergency.index', ['tab' => 'approved', 'department_id' => $departmentId]) }}"
                    class="px-4 py-2.5 text-sm font-medium border-b-2 transition
                        {{ $tab === 'approved' ? 'border-emerald-500 text-emerald-700' : 'border-transparent text-slate-400 hover:text-slate-600' }}">
                    Disetujui
                </a>
            </div>

            @if ($showFilter)
            <form method="GET">
                <input type="hidden" name="tab" value="{{ $tab }}">
                <select name="department_id" onchange="this.form.submit()"
                    class="rounded-lg border-slate-200 text-sm py-1.5 px-3">
                    @if ($departmentsForFilter->count() > 1 || !$departmentId)
                    <option value="">Semua Departemen</option>
                    @endif
                    @foreach ($departmentsForFilter as $department)
                    <option value="{{ $department->id }}" {{ $departmentId == $department->id ? 'selected' : '' }}>
                        {{ $department->name }}
                    </option>
                    @endforeach
                </select>
            </form>
            @elseif ($departmentsForFilter->isNotEmpty())
            <span class="text-sm text-slate-500">{{ $departmentsForFilter->first()->name }}</span>
            @endif
        </div>

        {{-- List --}}
        <div class="space-y-2">
            @forelse ($checkIns as $checkIn)
            <div class="rounded-xl border border-slate-100 p-3">
                <div class="flex items-center justify-between {{ $tab === 'pending' ? 'mb-3' : '' }}">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-slate-100 flex items-center justify-center text-xs font-semibold text-slate-600">
                            {{ collect(explode(' ', $checkIn->employee->name))->map(fn($w) => $w[0] ?? '')->take(2)->implode('') }}
                        </div>
                        <div>
                            <div class="font-medium text-slate-700 text-sm">{{ $checkIn->employee->name }}</div>
                            <div class="text-xs text-slate-400">{{ $checkIn->checked_at->translatedFormat('d M Y, H:i') }}</div>
                        </div>
                    </div>

                    @if ($tab !== 'pending')
                    @php
                    $meta = $tab === 'approved'
                    ? ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-700', 'dot' => 'bg-emerald-500', 'label' => 'Disetujui']
                    : ['bg' => 'bg-rose-100', 'text' => 'text-rose-700', 'dot' => 'bg-rose-500', 'label' => 'Ditolak'];
                    @endphp
                    <span class="text-xs px-3 py-1 rounded-full font-medium flex items-center gap-1.5 {{ $meta['bg'] }} {{ $meta['text'] }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $meta['dot'] }}"></span>
                        {{ $meta['label'] }}
                    </span>
                    <a href="{{ route('attendance.emergency.show', $checkIn->id) }}" class="text-xs text-sky-700 hover:underline">
                        Lihat Detail
                    </a>
                    @endif
                </div>

                @if ($tab === 'pending' && auth()->user()->hasPermission('emergency-attendance.approve'))
                <div class="flex gap-2">
                    <form method="POST" action="{{ route('attendance.emergency.decide', $checkIn->id) }}" class="flex-1">
                        @csrf
                        <input type="hidden" name="decision" value="approve">
                        <button class="w-full py-1.5 rounded-lg bg-emerald-600 text-white text-xs font-medium hover:bg-emerald-700 transition">
                            Setujui
                        </button>
                    </form>
                    <form method="POST" action="{{ route('attendance.emergency.decide', $checkIn->id) }}" class="flex-1">
                        @csrf
                        <input type="hidden" name="decision" value="reject">
                        <button class="w-full py-1.5 rounded-lg bg-white border border-slate-200 text-slate-600 text-xs font-medium hover:bg-slate-50 transition">
                            Tolak
                        </button>
                    </form>
                </div>
                @endif
            </div>
            @empty
            <div class="p-10 text-center text-slate-400">
                <i class="fas fa-inbox text-2xl mb-2 block"></i>
                @if ($tab === 'pending') Tidak ada pengajuan yang menunggu persetujuan.
                @elseif ($tab === 'rejected') Belum ada pengajuan yang ditolak.
                @else Belum ada pengajuan yang disetujui.
                @endif
            </div>
            @endforelse
        </div>

        <div class="mt-4">
            {{ $checkIns->links() }}
        </div>
    </div>
</div>
@endsection