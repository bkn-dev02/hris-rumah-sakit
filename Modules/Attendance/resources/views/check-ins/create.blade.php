@extends('shared::layouts.app')

@section('title', 'Tambah Check-In')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8">
    <div class="flex items-center gap-4">
        <a href="{{ route('attendance.check-ins.index') }}" class="border w-10 h-10 flex items-center justify-center rounded-full border-blue-100 bg-blue-900 hover:bg-blue-800 transition duration-200 translate-x-0 hover:-translate-x-1">
            <i class="fa fa-arrow-left text-md text-blue-50"></i>
        </a>
        <h1 class="text-xl font-semibold text-blue-800">Tambah Check-In</h1>
    </div>

    <form method="POST" action="{{ route('attendance.check-ins.store') }}" class="mt-6 bg-white shadow-md p-6" enctype="multipart/form-data">
        @csrf
        @include('attendance::check-ins._form')

        <div class="mt-6 flex justify-end gap-3">
            <a href="{{ route('attendance.check-ins.index') }}" class="rounded-full border border-slate-200 px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50">Batal</a>
            <button type="submit" class="bg-blue-900 hover:bg-blue-800 text-white px-5 py-2 rounded-full text-sm font-medium transition duration-200">Simpan</button>
        </div>
    </form>
</div>
@endsection