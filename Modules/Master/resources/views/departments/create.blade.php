@extends('shared::layouts.app')

@section('title', 'Tambah Department')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8">
    <div class="flex items-center gap-4">
        <a href="{{ route('master.departments.index') }}" class="w-10 h-10 flex items-center justify-center rounded-full bg-sky-900 hover:bg-sky-800 transition duration-200 translate-x-0 hover:-translate-x-1">
            <i class="fa fa-arrow-left text-md text-sky-100"></i>
        </a>
        <h1 class="text-lg font-bold text-sky-800">Tambah Department</h1>
    </div>
    <form method="POST" action="{{ route('master.departments.store') }}" class="mt-6 rounded-lg border border-slate-200 bg-white p-6">
        @csrf
        @include('master::departments._form')

        <div class="mt-6 flex justify-end gap-3">
            <a href="{{ route('master.departments.index') }}" class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50">Batal</a>
            <x-shared::button type="submit" variant="primary">Simpan</x-shared::button>
        </div>
    </form>
</div>
@endsection