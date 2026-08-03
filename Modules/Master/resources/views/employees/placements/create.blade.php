@extends('shared::layouts.app')

@section('title', 'Tempatkan / Mutasi Employee')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8">
    <div class="flex items-center gap-4">
        <a href="{{ route('master.employees.placements.index', $employee->slug) }}" class="border w-10 h-10 flex items-center justify-center rounded-full border-blue-100 bg-blue-900 hover:bg-blue-800 transition duration-200 translate-x-0 hover:-translate-x-1">
            <i class="fa fa-arrow-left text-md text-blue-50"></i>
        </a>
        <div>
            <h1 class="text-xl font-semibold text-blue-800">Tempatkan / Mutasi</h1>
            <p class="text-sm text-slate-500">{{ $employee->name }}</p>
        </div>
    </div>

    @if($currentPlacement)
    <div class="mt-4 rounded-lg border border-blue-100 bg-blue-50 px-4 py-3 text-sm text-blue-700">
        Saat ini di <strong>{{ $currentPlacement->department->name }}</strong> sebagai <strong>{{ $currentPlacement->position->name }}</strong>.
        Menyimpan penempatan baru akan otomatis mengakhiri penempatan ini.
    </div>
    @endif

    <form method="POST" action="{{ route('master.employees.placements.store', $employee->slug) }}" class="mt-6 bg-white shadow-md p-6">
        @csrf

        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Department</label>
                <select name="department_id" class="w-full rounded-lg border border-slate-200 py-2 px-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                    <option value="">-- Pilih Department --</option>
                    @foreach($departments as $department)
                    <option value="{{ $department->id }}" @selected(old('department_id')==$department->id)>{{ $department->name }}</option>
                    @endforeach
                </select>
                @error('department_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Posisi</label>
                <select name="position_id" class="w-full rounded-lg border border-slate-200 py-2 px-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                    <option value="">-- Pilih Posisi --</option>
                    @foreach($positions as $position)
                    <option value="{{ $position->id }}" @selected(old('position_id')==$position->id)>{{ $position->name }} (Level {{ $position->level }})</option>
                    @endforeach
                </select>
                @error('position_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Tanggal Mulai</label>
                <input type="date" name="start_date" value="{{ old('start_date', now()->toDateString()) }}"
                    class="w-full rounded-lg border border-slate-200 py-2 px-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                @error('start_date') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_temporary" value="1" id="is_temporary" @checked(old('is_temporary'))
                    class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                <label for="is_temporary" class="text-sm text-slate-700">Penempatan sementara</label>
            </div>

            <div class="sm:col-span-2">
                <label class="mb-1 block text-sm font-medium text-slate-700">Catatan</label>
                <textarea name="notes" rows="2" class="w-full rounded-lg border border-slate-200 py-2 px-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">{{ old('notes') }}</textarea>
                @error('notes') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <a href="{{ route('master.employees.placements.index', $employee->slug) }}" class="rounded-full border border-slate-200 px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50">Batal</a>
            <button type="submit" class="bg-blue-900 hover:bg-blue-800 text-white px-5 py-2 rounded-full text-sm font-medium transition duration-200">Simpan</button>
        </div>
    </form>
</div>
@endsection