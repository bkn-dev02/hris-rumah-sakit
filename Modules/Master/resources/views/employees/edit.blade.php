@extends('shared::layouts.app')

@section('title', 'Edit Employee')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8">
    <div class="flex items-center gap-4">
        <a href="{{ route('master.employees.show', $employee->slug) }}" class="border w-10 h-10 flex items-center justify-center rounded-full border-[#dfeee1] bg-[#1f4d3d] hover:bg-[#173f34] transition duration-200 translate-x-0 hover:-translate-x-1">
            <i class="fa fa-arrow-left text-md text-[#edf5ee]"></i>
        </a>
        <h1 class="text-xl font-semibold text-[#1f4d3d]">Edit Employee</h1>
    </div>

    <form method="POST" action="{{ route('master.employees.update', $employee->slug) }}" enctype="multipart/form-data" class="mt-6 bg-white shadow-md p-6">
        @csrf
        @method('PUT')
        @include('master::employees._form')

        <div class="mt-6 flex justify-end gap-3">
            <a href="{{ route('master.employees.show', $employee->slug) }}" class="rounded-full border border-slate-200 px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50">Batal</a>
            <button type="submit" class="bg-[#1f4d3d] hover:bg-[#173f34] text-white px-5 py-2 rounded-full text-sm font-medium transition duration-200">
                Simpan Perubahan
            </button>
        </div>
    </form>
    {{-- Kuota Cuti --}}
    <div class="mt-8 rounded-2xl border border-[#dfeee1] bg-white shadow-sm overflow-hidden">
        <div class="flex flex-col gap-3 border-b border-[#dfeee1] bg-[#edf5ee] px-6 py-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-3">
                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-[#1f4d3d] text-white">
                    <i class="fa-solid fa-chart-pie text-sm"></i>
                </div>
                <h2 class="text-sm font-bold text-[#1f4d3d]">Kuota Cuti</h2>
            </div>

            <form method="GET" action="{{ route('master.employees.edit', $employee->slug) }}" class="flex items-center gap-2">
                <label class="text-xs font-medium text-slate-500">Tahun:</label>
                <select name="quota_year" onchange="this.form.submit()"
                    class="rounded-lg border-slate-300 text-sm focus:border-[#2a684f] focus:ring-[#dfeee1]">
                    @for ($y = now()->year + 1; $y >= now()->year - 2; $y--)
                    <option value="{{ $y }}" @selected($quotaYear==$y)>{{ $y }}</option>
                    @endfor
                </select>
            </form>
        </div>

        @if ($leaveTypes->isEmpty())
        <div class="px-6 py-8 text-center text-sm text-slate-500">
            Belum ada jenis cuti yang membutuhkan kuota.
        </div>
        @else
        <form method="POST" action="{{ route('leave.employees.quotas.update', $employee->slug) }}" class="px-6 py-5">
            @csrf
            @method('PUT')
            <input type="hidden" name="year" value="{{ $quotaYear }}">

            <div class="space-y-3">
                @foreach ($leaveTypes as $index => $leaveType)
                @php
                $quota = $existingQuotas->get($leaveType->id)?->quota_days ?? 0;
                $used = $usedDays->get($leaveType->id, 0);
                $remaining = max(0, $quota - $used);
                @endphp
                <div class="flex items-center justify-between gap-4 rounded-xl border border-[#dfeee1] bg-[#edf5ee]/70 px-4 py-3">
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center rounded-lg bg-[#dfeee1] px-2.5 py-1 text-xs font-semibold text-[#1f4d3d]">
                            {{ $leaveType->code }}
                        </span>
                        <span class="text-sm font-medium text-slate-700">{{ $leaveType->name }}</span>
                    </div>

                    <div class="flex items-center gap-4">
                        <span class="text-xs text-slate-500">
                            Terpakai: <span class="font-semibold text-slate-700">{{ $used }}</span> hari
                            Â· Sisa: <span class="font-semibold text-emerald-600">{{ $remaining }}</span> hari
                        </span>

                        <input type="hidden" name="quotas[{{ $index }}][leave_type_id]" value="{{ $leaveType->id }}">
                        <input type="number" name="quotas[{{ $index }}][quota_days]" min="0" max="365"
                            value="{{ $quota }}"
                            class="w-24 rounded-lg border-slate-300 text-sm text-right focus:border-[#2a684f] focus:ring-[#dfeee1]">
                        <span class="text-xs text-slate-400">hari</span>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-5 flex justify-end">
                <button type="submit"
                    class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-br from-[#173f34] via-[#1f4d3d] to-[#2a684f] px-4 py-2.5 text-sm font-medium text-white shadow-md transition duration-200 hover:-translate-y-0.5 hover:shadow-lg">
                    <i class="fa-solid fa-floppy-disk text-xs"></i>
                    Simpan Kuota {{ $quotaYear }}
                </button>
            </div>
        </form>
        @endif
    </div>
</div>
@endsection
