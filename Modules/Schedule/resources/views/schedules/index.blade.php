@extends('shared::layouts.app')

@section('title', 'Jadwal Mingguan')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8">

    {{-- Header --}}
    <div class="bg-gradient-to-r from-[#173f34] to-[#2a684f] rounded-t-2xl px-6 py-4 flex items-center justify-between flex-wrap gap-3 shadow-md">
        <div class="flex items-center gap-3">
            <i class="fas fa-calendar-week text-[#dfeee1]"></i>
            <h1 class="text-[#dfeee1] font-semibold text-lg">Jadwal Mingguan</h1>
        </div>

        <form method="GET" action="{{ route('schedule.index') }}" class="flex items-center gap-3 flex-wrap">
            @if ($showFilter)
            <select name="department_id"
                onchange="this.form.submit()"
                class="rounded-lg border-0 bg-[#173f34] text-[#edf5ee] py-2 px-3 shadow-sm
                        focus:ring-2 focus:ring-[#dfeee1]">

                @if ($departmentsForFilter->count() > 1 || !$departmentId)
                <option value="" class="bg-white text-slate-800">
                    Pilih Departemen
                </option>
                @endif

                @foreach ($departmentsForFilter as $department)
                <option
                    value="{{ $department->id }}"
                    class="bg-white text-slate-800"
                    {{ $departmentId == $department->id ? 'selected' : '' }}>
                    {{ $department->name }}
                </option>
                @endforeach
            </select>
            @else
            <span class="text-[#dfeee1] text-sm font-medium px-1">
                {{ $departmentsForFilter->first()->name ?? '' }}
            </span>
            @endif

            <div class="flex items-center gap-2">
                <a href="{{ route('schedule.index', array_merge(request()->query(), ['start_date' => $startDate->copy()->subWeek()->toDateString()])) }}"
                    class="text-white/80 hover:text-white transition">
                    <i class="fas fa-chevron-left"></i>
                </a>
                <span class="text-white text-sm px-2">
                    {{ $startDate->translatedFormat('d M') }} sd. {{ $endDate->translatedFormat('d M Y') }}
                </span>
                <a href="{{ route('schedule.index', array_merge(request()->query(), ['start_date' => $startDate->copy()->addWeek()->toDateString()])) }}"
                    class="text-white/80 hover:text-white transition">
                    <i class="fas fa-chevron-right"></i>
                </a>
            </div>
        </form>
    </div>

    {{-- Grid --}}
    <div class="bg-white rounded-b-2xl shadow-md overflow-x-auto">
        @if (!$departmentId)
        <div class="p-10 text-center text-slate-400">
            <i class="fas fa-building text-3xl mb-3"></i>
            <p>Pilih departemen untuk melihat jadwal.</p>
        </div>
        @else
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-slate-100">
                    <th class="text-left px-4 py-3 font-medium text-slate-500 sticky left-0 bg-white">Pegawai</th>
                    @foreach ($dates as $date)
                    <th class="px-2 py-3 font-medium text-slate-500 text-center min-w-[64px]">
                        {{ $date->translatedFormat('D') }}<br>
                        <span class="text-slate-400 font-normal">{{ $date->format('d') }}</span>
                    </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($employees as $employee)
                <tr class="border-b border-slate-50 hover:bg-[#f8fbf8]/40 transition">
                    <td class="px-4 py-3 font-medium text-slate-700 whitespace-nowrap sticky left-0 bg-white">
                        {{ $employee->name }}
                    </td>
                    @foreach ($dates as $date)
                    <td class="px-2 py-2 text-center">
                        <div x-data="scheduleCell({
                                    employeeId: {{ $employee->id }},
                                    date: '{{ $date->toDateString() }}',
                                    shifts: @js($shifts->map(fn ($s) => ['id' => $s->id, 'label' => $s->initials, 'name' => $s->name])),
                                    initial: @js($scheduleMap[$employee->id][$date->toDateString()] ?? null),
                                })" class="relative inline-block">

                            <button @click="toggle($event)"
                                class="w-8 h-7 rounded-lg text-xs font-semibold shadow-sm hover:-translate-y-0.5 transition"
                                :class="badgeClass()">
                                <span x-text="label()"></span>
                            </button>

                            <template x-teleport="body">
                                <div x-show="open" @click.outside="open = false" x-cloak
                                    :style="`position:fixed; top:${popoverTop}px; left:${popoverLeft}px;`"
                                    class="z-50 bg-white rounded-xl shadow-lg border border-slate-100 p-2 w-36">
                                    <template x-for="shift in shifts" :key="shift.id">
                                        <button @click="assign('kerja', shift.id)"
                                            class="w-full text-left px-3 py-1.5 rounded-lg hover:bg-[#f8fbf8] text-xs flex items-center gap-2">
                                            <span class="w-2 h-2 rounded-full" :class="shiftDotClass(shift.id)"></span>
                                            <span x-text="shift.name"></span>
                                        </button>
                                    </template>
                                    <button @click="assign('libur', null)"
                                        class="w-full text-left px-3 py-1.5 rounded-lg hover:bg-slate-50 text-xs flex items-center gap-2 mt-1 border-t border-slate-100 pt-2">
                                        <span class="w-2 h-2 rounded-full bg-slate-300"></span>
                                        Libur
                                    </button>
                                </div>
                            </template>
                        </div>
                    </td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>

    {{-- Legend --}}
    <div class="flex items-center gap-4 mt-3 text-xs text-slate-500 flex-wrap">
        @foreach ($shifts as $shift)
        <span class="flex items-center gap-1">
            <span class="w-2 h-2 rounded-full {{ ['bg-[#f8fbf8]0','bg-amber-500','bg-violet-500','bg-emerald-500','bg-rose-500','bg-[#edf5ee]0'][$shift->id % 6] }}"></span>
            {{ $shift->name }} ({{ $shift->initials }}) <i class="fa-regular fa-clock text-xs text-slate-400"></i> ({{ \Carbon\Carbon::parse($shift->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($shift->end_time)->format('H:i') }})
        </span>
        @endforeach
        <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-slate-300"></span> Libur</span>
    </div>
</div>

@push('scripts')
<script>
    const SHIFT_BADGE_COLORS = [
        'bg-[#edf5ee] text-[#2a684f]',
        'bg-amber-100 text-amber-700',
        'bg-violet-100 text-violet-700',
        'bg-emerald-100 text-emerald-700',
        'bg-rose-100 text-rose-700',
        'bg-[#edf5ee] text-[#1f4d3d]',
    ];
    const SHIFT_DOT_COLORS = [
        'bg-[#f8fbf8]0', 'bg-amber-500', 'bg-violet-500',
        'bg-emerald-500', 'bg-rose-500', 'bg-[#edf5ee]0',
    ];

    function shiftBadgeClass(shiftId) {
        return SHIFT_BADGE_COLORS[shiftId % SHIFT_BADGE_COLORS.length];
    }

    function shiftDotClass(shiftId) {
        return SHIFT_DOT_COLORS[shiftId % SHIFT_DOT_COLORS.length];
    }

    function scheduleCell({
        employeeId,
        date,
        shifts,
        initial
    }) {
        return {
            open: false,
            popoverTop: 0,
            popoverLeft: 0,
            employeeId,
            date,
            shifts,
            current: initial ? {
                type: initial.type,
                shiftId: initial.shift_id,
                shiftLabel: initial.shift_label,
            } : null,

            toggle(event) {
                if (this.open) {
                    this.open = false;
                    return;
                }
                const rect = event.currentTarget.getBoundingClientRect();
                this.popoverTop = rect.bottom + 4;
                this.popoverLeft = rect.left + rect.width / 2 - 72;
                this.open = true;
            },

            label() {
                if (!this.current) return '-';
                return this.current.type === 'libur' ? 'L' : this.current.shiftLabel;
            },

            badgeClass() {
                if (!this.current) return 'bg-slate-100 text-slate-400';
                if (this.current.type === 'libur') return 'bg-slate-200 text-slate-600';
                return shiftBadgeClass(this.current.shiftId);
            },

            assign(type, shiftId) {
                this.open = false;

                const shift = shiftId ? this.shifts.find(s => s.id === shiftId) : null;

                fetch('{{ route('schedule.store') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            },
                            body: JSON.stringify({
                                employee_id: this.employeeId,
                                date: this.date,
                                type,
                                shift_id: shiftId,
                            }),
                        })
                    .then(res => res.json())
                    .then(() => {
                        this.current = {
                            type,
                            shiftId,
                            shiftLabel: shift ? shift.label : null
                        };
                    })
                    .catch(() => alert('Gagal menyimpan jadwal.'));
            },
        };
    }
</script>
@endpush
@endsection