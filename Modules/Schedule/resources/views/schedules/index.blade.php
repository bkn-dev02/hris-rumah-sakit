@extends('shared::layouts.app')

@section('title', 'Jadwal Mingguan')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8">

    {{-- Header --}}
    <div class="bg-gradient-to-r from-sky-950 to-sky-800 rounded-t-2xl px-6 py-4 flex items-center justify-between flex-wrap gap-3 shadow-md">
        <div class="flex items-center gap-3">
            <i class="fas fa-calendar-week text-sky-300"></i>
            <h1 class="text-sky-300 font-semibold text-lg">Jadwal Mingguan</h1>
        </div>

        <form method="GET" action="{{ route('schedule.index') }}" class="flex items-center gap-3 flex-wrap">
            <select name="department_id" onchange="this.form.submit()"
                class="rounded-lg border-0 text-sm text-sky-200 py-2 px-3 shadow-sm focus:ring-2 focus:ring-sky-400">
                <option value="">Pilih Departemen</option>
                @foreach ($departments as $department)
                <option value="{{ $department->id }}" {{ $departmentId == $department->id ? 'selected' : '' }}>
                    {{ $department->name }}
                </option>
                @endforeach
            </select>

            <div class="flex items-center gap-2">
                <a href="{{ route('schedule.index', array_merge(request()->query(), ['start_date' => $startDate->copy()->subWeek()->toDateString()])) }}"
                    class="text-white/80 hover:text-white transition">
                    <i class="fas fa-chevron-left"></i>
                </a>
                <span class="text-white text-sm px-2">
                    {{ $startDate->translatedFormat('d M') }} – {{ $endDate->translatedFormat('d M Y') }}
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
                <tr class="border-b border-slate-50 hover:bg-sky-50/40 transition">
                    <td class="px-4 py-3 font-medium text-slate-700 whitespace-nowrap sticky left-0 bg-white">
                        {{ $employee->name }}
                    </td>
                    @foreach ($dates as $date)
                    <td class="px-2 py-2 text-center">
                        <div x-data="scheduleCell({
                                            employeeId: {{ $employee->id }},
                                            date: '{{ $date->toDateString() }}',
                                            shifts: @js($shifts->map(fn ($s) => ['id' => $s->id, 'label' => strtoupper(substr($s->name, 0, 1)), 'name' => $s->name])),
                                        })" class="relative inline-block">

                            <button @click="open = !open"
                                class="w-8 h-7 rounded-lg text-xs font-semibold shadow-sm hover:-translate-y-0.5 transition"
                                :class="badgeClass()">
                                <span x-text="label()"></span>
                            </button>

                            <div x-show="open" @click.outside="open = false" x-cloak
                                class="absolute z-20 mt-1 left-1/2 -translate-x-1/2 bg-white rounded-xl shadow-lg border border-slate-100 p-2 w-36">
                                <template x-for="shift in shifts" :key="shift.id">
                                    <button @click="assign('kerja', shift.id)"
                                        class="w-full text-left px-3 py-1.5 rounded-lg hover:bg-sky-50 text-xs flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full bg-sky-500"></span>
                                        <span x-text="shift.name"></span>
                                    </button>
                                </template>
                                <button @click="assign('libur', null)"
                                    class="w-full text-left px-3 py-1.5 rounded-lg hover:bg-slate-50 text-xs flex items-center gap-2 mt-1 border-t border-slate-100 pt-2">
                                    <span class="w-2 h-2 rounded-full bg-slate-300"></span>
                                    Libur
                                </button>
                            </div>
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
    <div class="flex items-center gap-4 mt-3 text-xs text-slate-500">
        <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-sky-500"></span> Shift kerja</span>
        <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-slate-300"></span> Libur</span>
    </div>
</div>

@push('scripts')
<script>
    function scheduleCell({
        employeeId,
        date,
        shifts
    }) {
        return {
            open: false,
            employeeId,
            date,
            shifts,
            current: null,

            label() {
                if (!this.current) return '-';
                return this.current.type === 'libur' ? 'L' : this.current.shiftLabel;
            },

            badgeClass() {
                if (!this.current) return 'bg-slate-100 text-slate-400';
                return this.current.type === 'libur' ?
                    'bg-slate-200 text-slate-600' :
                    'bg-sky-100 text-sky-700';
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