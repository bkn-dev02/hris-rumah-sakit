@extends('shared::layouts.app')

@section('title', 'Tambah Employee')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8">
    <div class="flex items-center gap-4">
        <a href="{{ route('master.employees.index') }}" class="w-10 h-10 flex items-center justify-center rounded-full bg-[#1f4d3d] hover:bg-[#173f34] transition duration-200 translate-x-0 hover:-translate-x-1">
            <i class="fa fa-arrow-left text-md text-[#edf5ee]"></i>
        </a>
        <h1 class="text-lg font-bold text-[#1f4d3d]">Tambah Karyawan</h1>
    </div>

    <form method="POST" action="{{ route('master.employees.store') }}" enctype="multipart/form-data" class="my-6 bg-white shadow-md p-6 rounded-lg">
        @csrf
        @include('master::employees._form')

        <div class="mt-6 flex justify-end gap-3">
            <a href="{{ route('master.employees.index') }}" class="w-32 rounded-full border border-[#dfeee1] px-4 py-2 text-sm font-medium text-[#1f4d3d] hover:bg-[#edf5ee] text-center translate-y-0 hover:-translate-y-1 shadow-slate-200 hover:shadow-lg transition duration-200 cursor-pointer">Batal</a>
            <button type="submit" class="w-32 bg-[#1f4d3d] hover:bg-[#173f34] text-[#edf5ee] px-5 py-2 rounded-full text-sm font-medium transition duration-200 translate-y-0 hover:-translate-y-1 shadow-[#1f4d3d]/20 hover:shadow-lg cursor-pointer">
                Submit
            </button>
        </div>
    </form>
</div>
@endsection