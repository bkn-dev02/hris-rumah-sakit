@extends('shared::layouts.app')

@section('title', 'Tambah Lokasi Absensi')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8">
    <div class="flex items-center gap-4">
        <a href="{{ route('attendance.locations.index') }}" class="border w-10 h-10 flex items-center justify-center rounded-full border-[#dfeee1] bg-[#173f34] hover:bg-[#173f34] transition duration-200 translate-x-0 hover:-translate-x-1">
            <i class="fa fa-arrow-left text-md text-[#edf5ee]"></i>
        </a>
        <h1 class="text-xl font-semibold text-[#1f4d3d]">Tambah Lokasi Absensi</h1>
    </div>

    <form method="POST" action="{{ route('attendance.locations.store') }}" class="mt-6 bg-white shadow-md p-6">
        @csrf
        @include('attendance::locations._form')

        <div class="mt-6 flex justify-end gap-3">
            <a href="{{ route('attendance.locations.index') }}" class="rounded-full border border-[#dfeee1] px-4 py-2 text-sm font-medium text-[#2a684f] hover:bg-[#f8fbf8]">Batal</a>
            <button type="submit" class="bg-[#173f34] hover:bg-[#173f34] text-white px-5 py-2 rounded-full text-sm font-medium transition duration-200">Simpan</button>
        </div>
    </form>
</div>
@endsection
