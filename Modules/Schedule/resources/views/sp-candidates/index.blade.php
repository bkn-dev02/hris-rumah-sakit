@extends('shared::layouts.app')

@section('title', 'SP Candidate')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8">

    <div class="bg-gradient-to-r from-sky-950 to-sky-800 rounded-t-2xl px-6 py-4 flex items-center gap-3 shadow-md">
        <i class="fas fa-triangle-exclamation text-amber-300"></i>
        <h1 class="text-white font-semibold text-lg">SP Candidate</h1>
    </div>

    <div class="bg-white rounded-b-2xl shadow-md">
        {{-- Tabs --}}
        <div class="flex gap-1 border-b border-slate-100 px-4">
            <a href="{{ route('schedule.sp-candidates.index', ['tab' => 'action']) }}"
                class="px-4 py-3 text-sm font-medium flex items-center gap-2 border-b-2 transition
                    {{ $tab === 'action' ? 'border-amber-500 text-amber-700' : 'border-transparent text-slate-400 hover:text-slate-600' }}">
                Perlu Tindakan
                @if ($counts['action'] > 0)
                <span class="text-xs px-2 py-0.5 rounded-full font-semibold {{ $tab === 'action' ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-500' }}">
                    {{ $counts['action'] }}
                </span>
                @endif
            </a>
            <a href="{{ route('schedule.sp-candidates.index', ['tab' => 'issued']) }}"
                class="px-4 py-3 text-sm font-medium flex items-center gap-2 border-b-2 transition
                    {{ $tab === 'issued' ? 'border-sky-800 text-sky-800' : 'border-transparent text-slate-400 hover:text-slate-600' }}">
                <i class="fas fa-file-signature text-xs"></i> SP Terbit
                <span class="text-xs text-slate-400">{{ $counts['issued'] }}</span>
            </a>
            <a href="{{ route('schedule.sp-candidates.index', ['tab' => 'cancelled']) }}"
                class="px-4 py-3 text-sm font-medium flex items-center gap-2 border-b-2 transition
                    {{ $tab === 'cancelled' ? 'border-slate-500 text-slate-600' : 'border-transparent text-slate-400 hover:text-slate-600' }}">
                <i class="fas fa-circle-xmark text-xs"></i> SP Dibatalkan
                <span class="text-xs text-slate-400">{{ $counts['cancelled'] }}</span>
            </a>
        </div>

        <div class="p-4">
            @if ($candidates->isEmpty())
            <div class="p-10 text-center text-slate-400">
                <i class="fas fa-circle-check text-3xl mb-3"></i>
                <p>
                    @if ($tab === 'action') Tidak ada yang perlu ditindak saat ini.
                    @elseif ($tab === 'issued') Belum ada SP yang diterbitkan.
                    @else Belum ada SP yang dibatalkan.
                    @endif
                </p>
            </div>
            @elseif ($tab === 'action')
            @php
            $needsInfo = $candidates->where('status', 'candidate');
            $needsDecision = $candidates->where('status', 'pending_decision');
            @endphp

            @if ($needsDecision->isNotEmpty())
            <div class="text-xs font-semibold text-violet-700 mb-2 flex items-center gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-violet-600"></span>
                MENUNGGU KEPUTUSAN ANDA — {{ $needsDecision->count() }}
            </div>
            <div class="space-y-2 mb-5">
                @foreach ($needsDecision as $candidate)
                <div class="border border-violet-100 bg-violet-50/50 rounded-xl p-3">
                    <a href="{{ route('schedule.sp-candidates.show', $candidate->id) }}" class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-white flex items-center justify-center text-xs font-semibold text-slate-600 shrink-0">
                                {{ collect(explode(' ', $candidate->employee->name))->map(fn($w) => $w[0] ?? '')->take(2)->implode('') }}
                            </div>
                            <div>
                                <div class="font-medium text-slate-700 text-sm">{{ $candidate->employee->name }}</div>
                                <div class="text-xs text-slate-400">
                                    {{ $candidate->department->name }} · {{ $candidate->date->translatedFormat('d M') }} · Terlambat check-in {{ $candidate->late_checkin_at?->format('H:i') }}
                                </div>
                            </div>
                        </div>
                    </a>
                    @if (auth()->user()->hasPermission('sp-letters.issue'))
                    <div class="flex gap-2">
                        <form method="POST" action="{{ route('schedule.sp-candidates.decide', $candidate->id) }}" class="flex-1">
                            @csrf
                            <input type="hidden" name="issue_sp" value="1">
                            <button class="w-full py-1.5 rounded-lg bg-rose-600 text-white text-xs font-medium hover:bg-rose-700 transition">
                                Tetap Terbitkan SP
                            </button>
                        </form>
                        <form method="POST" action="{{ route('schedule.sp-candidates.decide', $candidate->id) }}" class="flex-1">
                            @csrf
                            <input type="hidden" name="issue_sp" value="0">
                            <button class="w-full py-1.5 rounded-lg bg-white border border-slate-200 text-slate-600 text-xs font-medium hover:bg-slate-50 transition">
                                Batalkan
                            </button>
                        </form>
                    </div>
                    @else
                    <a href="{{ route('schedule.sp-candidates.show', $candidate->id) }}" class="text-xs text-violet-700 hover:underline">
                        Lihat detail →
                    </a>
                    @endif
                </div>
                @endforeach
            </div>
            @endif

            @if ($needsInfo->isNotEmpty())
            <div class="text-xs font-semibold text-amber-700 mb-2 flex items-center gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                BELUM ADA KABAR — {{ $needsInfo->count() }}
            </div>
            <div class="space-y-2">
                @foreach ($needsInfo as $candidate)
                <a href="{{ route('schedule.sp-candidates.show', $candidate->id) }}"
                    class="flex items-center justify-between border border-amber-100 bg-amber-50/50 rounded-xl p-3 hover:shadow-sm transition">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-white flex items-center justify-center text-xs font-semibold text-slate-600">
                            {{ collect(explode(' ', $candidate->employee->name))->map(fn($w) => $w[0] ?? '')->take(2)->implode('') }}
                        </div>
                        <div>
                            <div class="font-medium text-slate-700 text-sm">{{ $candidate->employee->name }}</div>
                            <div class="text-xs text-slate-400">
                                {{ $candidate->department->name }} · {{ $candidate->date->translatedFormat('d M') }} · {{ $candidate->shift->name }}
                            </div>
                        </div>
                    </div>
                    <i class="fas fa-chevron-right text-slate-300 text-xs"></i>
                </a>
                @endforeach
            </div>
            @endif
            @else
            {{-- SP Terbit & SP Dibatalkan: tampilan ringkas, tidak butuh grup/aksi --}}
            <div class="space-y-2">
                @foreach ($candidates as $candidate)
                <a href="{{ route('schedule.sp-candidates.show', $candidate->id) }}"
                    class="flex items-center justify-between p-3 rounded-xl border border-slate-100 hover:bg-slate-50 transition">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-xs font-semibold text-slate-500">
                            {{ collect(explode(' ', $candidate->employee->name))->map(fn($w) => $w[0] ?? '')->take(2)->implode('') }}
                        </div>
                        <div>
                            <div class="font-medium text-slate-600 text-sm">{{ $candidate->employee->name }}</div>
                            <div class="text-xs text-slate-400">
                                {{ $candidate->department->name }} · {{ $candidate->date->translatedFormat('d M Y') }}
                                @if ($tab === 'issued' && $candidate->spLetter)
                                · SP ke-{{ $candidate->spLetter->sp_number }}
                                @endif
                            </div>
                        </div>
                    </div>
                    <i class="fas fa-chevron-right text-slate-300 text-xs"></i>
                </a>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</div>
@endsection