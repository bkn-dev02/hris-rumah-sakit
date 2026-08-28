@extends('shared::layouts.app')

@section('title', 'SP Candidate')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8">

    <div class="bg-gradient-to-r from-sky-950 to-sky-800 rounded-t-2xl px-6 py-4 flex items-center justify-between flex-wrap gap-3 shadow-md">
        <div class="flex items-center gap-3">
            <i class="fas fa-triangle-exclamation text-amber-300"></i>
            <h1 class="text-white font-semibold text-lg">SP Candidate</h1>
        </div>

        <form method="GET" class="flex items-center gap-2">
            <select name="status" onchange="this.form.submit()"
                class="rounded-lg border-0 text-sm py-2 px-3 shadow-sm focus:ring-2 focus:ring-sky-400">
                <option value="">Semua Status</option>
                <option value="candidate" {{ $status === 'candidate' ? 'selected' : '' }}>Candidate</option>
                <option value="pending_decision" {{ $status === 'pending_decision' ? 'selected' : '' }}>Pending Decision</option>
                <option value="resolved_issued" {{ $status === 'resolved_issued' ? 'selected' : '' }}>SP Diterbitkan</option>
                <option value="cancelled_manual" {{ $status === 'cancelled_manual' ? 'selected' : '' }}>Dibatalkan</option>
            </select>
        </form>
    </div>

    <div class="bg-white rounded-b-2xl shadow-md p-4 space-y-2">
        @forelse ($candidates as $candidate)
        @php
        $statusMeta = [
        'candidate' => ['label' => 'Candidate', 'dot' => 'bg-amber-500', 'text' => 'text-amber-700', 'bg' => 'bg-amber-50'],
        'pending_decision' => ['label' => 'Pending Decision', 'dot' => 'bg-violet-500', 'text' => 'text-violet-700', 'bg' => 'bg-violet-50'],
        'resolved_issued' => ['label' => 'SP Diterbitkan', 'dot' => 'bg-rose-500', 'text' => 'text-rose-700', 'bg' => 'bg-rose-50'],
        'cancelled_manual' => ['label' => 'Dibatalkan (Manual)', 'dot' => 'bg-slate-400', 'text' => 'text-slate-600', 'bg' => 'bg-slate-50'],
        'cancelled_late_checkin_decision' => ['label' => 'Dibatalkan (Terlambat)', 'dot' => 'bg-slate-400', 'text' => 'text-slate-600', 'bg' => 'bg-slate-50'],
        ][$candidate->status] ?? ['label' => $candidate->status, 'dot' => 'bg-slate-400', 'text' => 'text-slate-600', 'bg' => 'bg-slate-50'];
        @endphp
        <a href="{{ route('schedule.sp-candidates.show', $candidate->id) }}"
            class="flex items-center justify-between p-3 rounded-xl border border-slate-100 hover:shadow-sm hover:-translate-y-0.5 transition">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-slate-100 flex items-center justify-center text-xs font-semibold text-slate-600">
                    {{ collect(explode(' ', $candidate->employee->name))->map(fn($w) => $w[0] ?? '')->take(2)->implode('') }}
                </div>
                <div>
                    <div class="font-medium text-slate-700 text-sm">{{ $candidate->employee->name }}</div>
                    <div class="text-xs text-slate-400">
                        {{ $candidate->department->name }} · {{ $candidate->date->translatedFormat('d M Y') }} · {{ $candidate->shift->name }}
                    </div>
                </div>
            </div>
            <span class="text-xs px-3 py-1 rounded-full font-medium flex items-center gap-1.5 {{ $statusMeta['bg'] }} {{ $statusMeta['text'] }}">
                <span class="w-1.5 h-1.5 rounded-full {{ $statusMeta['dot'] }}"></span>
                {{ $statusMeta['label'] }}
            </span>
        </a>
        @empty
        <div class="p-10 text-center text-slate-400">
            <i class="fas fa-circle-check text-3xl mb-3"></i>
            <p>Tidak ada SP Candidate.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection