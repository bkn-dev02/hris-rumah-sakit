@extends('shared::layouts.app')

@section('title', 'Edit Permission')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8">
    <div class="flex items-center gap-4">
        <a href="{{ route('security.permissions.index') }}" class="border w-10 h-10 flex items-center justify-center rounded-full border-[#dfeee1] bg-[#1f4d3d] hover:bg-[#2a684f] transition duration-200 translate-x-0 hover:-translate-x-1">
            <i class="fa fa-arrow-left text-md text-[#edf5ee]"></i>
        </a>
        <h1 class="text-xl font-semibold text-[#1f4d3d]">Edit Permission</h1>
    </div>

    <form method="POST" action="{{ route('security.permissions.update', $permission->id) }}" class="mt-6 bg-white shadow-md p-6">
        @csrf @method('PUT')
        @include('security::permissions._form')

        <div class="mt-6 flex justify-end gap-3">
            <a href="{{ route('security.permissions.index') }}" class="rounded-full border border-slate-200 px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50">Batal</a>
            <button type="submit" class="bg-[#1f4d3d] hover:bg-[#2a684f] text-white px-5 py-2 rounded-full text-sm font-medium transition duration-200">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection
