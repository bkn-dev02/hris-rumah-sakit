@extends('shared::layouts.app')

@section('title', 'Tambah Lokasi Absensi')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8">
    <div class="flex items-center gap-4">
        <a href="{{ route('attendance.locations.index') }}" class="border w-10 h-10 flex items-center justify-center rounded-full border-sky-100 bg-sky-900 hover:bg-sky-800 transition duration-200 translate-x-0 hover:-translate-x-1">
            <i class="fa fa-arrow-left text-md text-sky-50"></i>
        </a>
        <h1 class="text-xl font-semibold text-sky-800">Tambah Lokasi Absensi</h1>
    </div>

    <form method="POST" action="{{ route('attendance.locations.store') }}" class="mt-6 bg-white shadow-md p-6">
        @csrf
        @include('attendance::locations._form')

        <div class="mt-6 flex justify-end gap-3">
            <a href="{{ route('attendance.locations.index') }}" class="rounded-full border border-sky-200 px-4 py-2 text-sm font-medium text-sky-600 hover:bg-sky-50">Batal</a>
            <button type="submit" class="bg-sky-900 hover:bg-sky-800 text-white px-5 py-2 rounded-full text-sm font-medium transition duration-200">Simpan</button>
        </div>
    </form>
</div>
@endsection