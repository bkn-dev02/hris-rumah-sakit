@extends('shared::layouts.app')

@section('title', 'Manajemen Karyawan')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8">
    @if(session('success'))
    <div class="mb-6 flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
        <i class="fa-solid fa-circle-check mt-0.5"></i>
        <span>
            {{ session('success') }}
        </span>
    </div>
    @endif

    <div class="mb-8">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('master.index') }}" class="w-10 h-10 flex items-center justify-center rounded-full bg-sky-900 hover:bg-sky-950 transition duration-200 translate-x-0 hover:-translate-x-1">
                    <i class="fa fa-arrow-left text-md text-sky-200"></i>
                </a>
                <h1 class="text-lg font-bold text-sky-800">Manajemen Pegawai</h1>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('master.employees.create') }}">
                    <x-shared::button variant="primary" icon="fa-solid fa-plus">
                        <span class="text-sky-50">Tambah Pegawai</span>
                    </x-shared::button>
                </a>
            </div>
        </div>
    </div>

    <div class="mb-6">

        <div class="inline-flex rounded-xl border border-slate-200 bg-white p-1 shadow-sm">
            <a
                href="{{ route('master.employees.index') }}"
                class="inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium transition-all duration-200
                    {{ !request('trashed')
                        ? 'bg-sky-900 text-white shadow-sm'
                        : 'text-slate-500 hover:bg-sky-50 hover:text-sky-800'
                    }}">
                <span class="h-2 w-2 rounded-full
                        {{ !request('trashed') ? 'bg-emerald-300' : 'bg-emerald-500' }}">
                </span>
                Aktif
            </a>
            <a
                href="{{ route('master.employees.index', ['trashed' => 1]) }}"
                class="inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium transition-all duration-200
                    {{ request('trashed')
                        ? 'bg-sky-900 text-white shadow-sm'
                        : 'text-slate-500 hover:bg-sky-50 hover:text-sky-800'
                    }}">
                <span class="h-2 w-2 rounded-full
                        {{ request('trashed') ? 'bg-red-300' : 'bg-red-500' }}">
                </span>
                Nonaktif
            </a>
        </div>
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="hidden border-b border-slate-200 bg-sky-900 px-6 py-4 lg:grid lg:grid-cols-12 lg:gap-4 text-sky-50">
            <div class="col-span-3 text-xs font-semibold uppercase tracking-wide">
                <i class="fa-solid fa-user"></i> Pegawai
            </div>
            <div class="col-span-2 text-xs font-semibold uppercase tracking-wide">
                <i class="fa-solid fa-hospital"></i> Posisi
            </div>
            <div class="col-span-2 text-xs font-semibold uppercase tracking-wide">
                <i class="fa-solid fa-id-card"></i> Status
            </div>
            <div class="col-span-2 text-xs font-semibold uppercase tracking-wide">
                <i class="fa-solid fa-phone"></i> Telp/WA
            </div>
            <div class="col-span-2 text-xs font-semibold uppercase tracking-wide">
                <i class="fa-solid fa-calendar-days"></i> Masa Kerja
            </div>
            <div class="col-span-1 text-center text-xs font-semibold uppercase tracking-wide">
                <i class="fa-solid fa-cogs"></i> Aksi
            </div>
        </div>

        <div class="divide-y divide-slate-100">
            @forelse($employees as $employee)
            <div class="group px-4 py-5 transition-colors duration-200 hover:bg-sky-50/40 sm:px-6 sm:py-6">
                <div class="grid grid-cols-1 gap-5 sm:gap-6 lg:grid-cols-12 lg:items-center lg:gap-4">
                    <div class="lg:col-span-3">
                        <div class="flex items-center gap-3">
                            <x-shared::avatar
                                :src="$employee->photo ? asset('storage/' . $employee->photo) : null"
                                :name="$employee->name"
                                size="sm" />
                            <div class="min-w-0">
                                <p class="truncate text-sm font-semibold text-sky-900 capitalize">
                                    {{ $employee->name }}
                                </p>
                                @if($employee->profession)
                                <p class="mt-0.5 text-xs font-medium text-sky-600">
                                    {{ $employee->profession }}
                                </p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="lg:col-span-2">
                        <p class="text-sm text-sky-600 break-all">
                            {{ $employee->position ?? '-' }} {{ $employee->currentDepartment()?->name ?? '-' }}
                        </p>
                    </div>
                    <div class="lg:col-span-2">
                        <span class="text-sm text-sky-600">
                            {{ $employee->employmentStatus->name ?? '-' }}
                        </span>
                        @if($employee->trashed())
                        <x-shared::badge
                            variant="danger"
                            dot>
                            Nonaktif
                        </x-shared::badge>
                        @else

                        <x-shared::badge
                            variant="success"
                            dot>
                            Aktif
                        </x-shared::badge>

                        @endif
                    </div>
                    <div class="lg:col-span-2">
                        <p class="text-sm text-sky-600">
                            {{ $employee->phone ?? '-' }}
                        </p>
                    </div>
                    <div class="lg:col-span-2">
                        <p class="text-sm text-sky-600">
                            {{ $employee->work_duration ?? '-' }}
                        </p>
                    </div>
                    <div class="lg:col-span-1">
                        <div class="grid w-full max-w-[220px] grid-cols-4 gap-2 sm:gap-3 md:grid-cols-4 lg:max-w-[180px] lg:grid-cols-2 lg:grid-rows-2 lg:gap-2">
                            @if($employee->trashed())
                            <form
                                method="POST"
                                action="{{ route('master.employees.restore', $employee->slug) }}"
                                class="w-full">
                                @csrf
                                @method('PATCH')
                                <button
                                    type="submit"
                                    class="inline-flex w-full items-center justify-center rounded-lg bg-emerald-600 px-3 py-2 text-xs font-medium text-white shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:bg-emerald-700 hover:shadow-md focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-600 cursor-pointer"
                                    title="Pulihkan karyawan"
                                    aria-label="Pulihkan karyawan">
                                    <i class="fa-solid fa-rotate-left"></i>
                                </button>
                            </form>
                            <form
                                method="POST"
                                action="{{ route('master.employees.forceDelete', $employee->slug) }}"
                                onsubmit="return confirm('Hapus permanen? Tindakan ini tidak bisa dibatalkan.')"
                                class="w-full">
                                @csrf
                                @method('DELETE')
                                <button
                                    type="submit"
                                    class="inline-flex w-full items-center justify-center rounded-lg bg-red-600 px-3 py-2 text-xs font-medium text-white shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:bg-red-700 hover:shadow-md focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600 cursor-pointer"
                                    title="Hapus permanen"
                                    aria-label="Hapus permanen">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </form>
                            @else
                            <a
                                href="{{ route('master.employees.show', $employee->slug) }}"
                                class="inline-flex w-full items-center justify-center rounded-lg bg-sky-900 px-3 py-2 text-xs font-medium text-white shadow-sm shadow-sky-900/20 transition-all duration-200 hover:-translate-y-0.5 hover:bg-sky-800 hover:shadow-md focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-sky-700"
                                title="Lihat detail"
                                aria-label="Lihat detail">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            <a
                                href="{{ route('master.employees.edit', $employee->slug) }}"
                                class="inline-flex w-full items-center justify-center rounded-lg bg-amber-500 px-3 py-2 text-xs font-medium text-white shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:bg-amber-600 hover:shadow-md focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-amber-500"
                                title="Edit karyawan"
                                aria-label="Edit karyawan">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <button
                                type="button"
                                class="inline-flex w-full items-center justify-center rounded-lg bg-violet-600 px-3 py-2 text-xs font-medium text-white shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:bg-violet-700 hover:shadow-md focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-violet-600 cursor-pointer open-attendance-location-modal"
                                data-slug="{{ $employee->slug }}"
                                data-name="{{ $employee->name }}"
                                data-current-location="{{ $employee->attendance_location_id ?? '' }}"
                                title="Atur lokasi absensi"
                                aria-label="Atur lokasi absensi">
                                <i class="fa-solid fa-location-dot"></i>
                            </button>
                            <form
                                method="POST"
                                action="{{ route('master.employees.destroy', $employee->slug) }}"
                                onsubmit="return confirm('Nonaktifkan karyawan ini?')"
                                class="w-full">
                                @csrf
                                @method('DELETE')
                                <button
                                    type="submit"
                                    class="inline-flex w-full items-center justify-center rounded-lg bg-red-600 px-3 py-2 text-xs font-medium text-white shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:bg-red-700 hover:shadow-md focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600 cursor-pointer"
                                    title="Nonaktifkan karyawan"
                                    aria-label="Nonaktifkan karyawan">
                                    <i class="fa-solid fa-user-slash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div id="attendance-location-modal-{{ $employee->slug }}" class="hidden fixed inset-0 z-50 items-center justify-center bg-slate-900/60 p-4">
                <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-sky-600">Lokasi absensi</p>
                            <h3 class="mt-1 text-lg font-bold text-slate-800">{{ $employee->name }}</h3>
                        </div>
                        <button type="button" class="close-attendance-location-modal rounded-lg border border-slate-200 p-2 text-slate-500 hover:bg-slate-100" aria-label="Tutup modal">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>

                    <form method="POST" action="{{ route('master.employees.setAttendanceLocation', $employee->slug) }}" class="mt-5">
                        @csrf
                        @method('PATCH')
                        <div>
                            <label for="attendance_location_id_{{ $employee->slug }}" class="mb-2 block text-sm font-medium text-slate-700">
                                Pilih lokasi absensi
                            </label>
                            <select
                                id="attendance_location_id_{{ $employee->slug }}"
                                name="attendance_location_id"
                                class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-700 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-100">
                                <option value="">-- Tidak ada lokasi --</option>
                                @foreach($attendanceLocations as $location)
                                <option value="{{ $location->id }}" {{ $employee->attendance_location_id == $location->id ? 'selected' : '' }}>
                                    {{ $location->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mt-5 flex justify-end gap-3">
                            <button type="button" class="close-attendance-location-modal rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50">
                                Batal
                            </button>
                            <button type="submit" class="rounded-xl bg-sky-900 px-4 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-sky-800">
                                Simpan Lokasi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @empty
            <div class="px-6 py-16 text-center">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-sky-50 text-sky-600">
                    <i class="fa-solid fa-users text-xl"></i>
                </div>
                <h3 class="mt-4 text-sm font-semibold text-slate-800">
                    Belum ada data karyawan
                </h3>

                <p class="mx-auto mt-1 max-w-sm text-sm text-slate-500">
                    Belum terdapat data karyawan pada kategori yang sedang dipilih.
                </p>

                @if(!request('trashed'))
                <a
                    href="{{ route('master.employees.create') }}"
                    class="mt-5 inline-flex items-center gap-2 rounded-xl bg-sky-900 px-4 py-2 text-sm font-medium text-white shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:bg-sky-800 hover:shadow-md">
                    <i class="fa-solid fa-plus text-xs"></i>
                    Tambah Karyawan
                </a>
                @endif
            </div>
            @endforelse
        </div>
    </div>
    @if($employees->hasPages())
    <div class="mt-6">
        {{ $employees->links() }}
    </div>

    @endif

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const openButtons = document.querySelectorAll('.open-attendance-location-modal');

        openButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                const slug = this.dataset.slug;
                const modal = document.getElementById('attendance-location-modal-' + slug);

                if (modal) {
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                }
            });
        });

        document.querySelectorAll('.close-attendance-location-modal').forEach(function(button) {
            button.addEventListener('click', function() {
                const modal = this.closest('[id^="attendance-location-modal-"]');

                if (modal) {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }
            });
        });

        document.querySelectorAll('[id^="attendance-location-modal-"]').forEach(function(modal) {
            modal.addEventListener('click', function(event) {
                if (event.target === modal) {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }
            });
        });
    });
</script>
@endsection