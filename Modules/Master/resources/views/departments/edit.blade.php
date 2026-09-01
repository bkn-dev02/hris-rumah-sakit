@extends('shared::layouts.app')

@section('title', 'Edit Department')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8">
    <div class="flex items-center gap-4">
        <a href="{{ route('master.departments.index') }}" class="w-10 h-10 flex items-center justify-center rounded-full bg-[#173f34] hover:bg-[#1f4d3d] transition duration-200 translate-x-0 hover:-translate-x-1">
            <i class="fa fa-arrow-left text-md text-[#dfeee1]"></i>
        </a>
        <h1 class="text-lg font-bold text-[#1f4d3d]">Edit Department</h1>
    </div>

    <form method="POST" action="{{ route('master.departments.update', $department->id) }}" class="mt-6 rounded-lg border border-slate-200 bg-white p-6">
        @csrf
        @method('PUT')
        @include('master::departments._form')

        <div class="mt-6 flex justify-end gap-3">
            <a href="{{ route('master.departments.index') }}" class="rounded-full border border-slate-200 px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50">Batal</a>
            <x-shared::button type="submit" variant="primary">Simpan Perubahan</x-shared::button>
        </div>
    </form>
</div>
@endsection
