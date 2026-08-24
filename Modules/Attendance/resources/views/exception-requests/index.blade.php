@extends('shared::layouts.app')

@section('title', 'Pengajuan Absensi')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8">

    @if(session('success'))
    <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">{{ session('error') }}</div>
    @endif

    <div class="flex items-center gap-4">
        <a href="{{ route('attendance.index') }}" class="border w-10 h-10 flex items-center justify-center rounded-full border-sky-100 bg-sky-900 hover:bg-sky-800 transition duration-200 translate-x-0 hover:-translate-x-1">
            <i class="fa fa-arrow-left text-md text-sky-50"></i>
        </a>
        <h1 class="text-xl font-semibold text-sky-800">Pengajuan Absensi</h1>
    </div>

    <div class="mt-6 flex gap-2 border-b border-sky-200">
        @foreach(['pending' => 'Menunggu', 'approved' => 'Disetujui', 'rejected' => 'Ditolak'] as $value => $label)
        <a href="{{ route('attendance.exception-requests.index', ['status' => $value]) }}"
            class="border-b-2 px-4 py-2 text-sm font-medium {{ $approvalStatus === $value ? 'border-sky-600 text-sky-600' : 'border-transparent text-sky-500 hover:text-sky-700' }}">
            {{ $label }}
        </a>
        @endforeach
    </div>

    <div class="mt-4 bg-white shadow-md p-4">
        <div class="border border-gray-200 rounded-md grid grid-cols-5 gap-4 p-4 text-gray-700 font-semibold text-sm">
            <span>Pegawai</span>
            <span>Tanggal</span>
            <span>Jenis</span>
            <span>Alasan</span>
            <span class="flex justify-center">Aksi</span>
        </div>

        @forelse($requests as $request)
        <div class="border border-gray-200 rounded-md grid grid-cols-5 gap-4 p-4 items-center text-gray-700 text-sm">
            <span class="font-medium text-sky-800">{{ $request->employee->name }}</span>
            <span>{{ $request->work_date->format('d M Y') }}</span>
            <span><x-shared::badge variant="info" size="sm">{{ $request->status->name }}</x-shared::badge></span>
            <span class="text-sky-500 truncate">{{ $request->reason }}</span>
            <div class="flex justify-center">
                <a href="{{ route('attendance.exception-requests.show', $request->id) }}" class="bg-sky-900 hover:bg-sky-800 text-white rounded-full px-3 py-1 transition duration-200 flex items-center justify-center gap-1 hover:-transky-y-1 hover:shadow-lg">
                    <i class="fa fa-eye text-sm"></i> Detail
                </a>
            </div>
        </div>
        @empty
        <div class="border border-gray-200 rounded-md p-10 text-center text-sm text-sky-400">Tidak ada pengajuan.</div>
        @endforelse
    </div>

    <div class="mt-4">{{ $requests->links() }}</div>

</div>
@endsection