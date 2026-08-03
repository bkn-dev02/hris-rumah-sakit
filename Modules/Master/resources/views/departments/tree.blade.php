@extends('shared::layouts.app')

@section('title', 'Struktur Department')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('master.departments.index') }}" class="border w-10 h-10 flex items-center justify-center rounded-full border-blue-100 bg-blue-900 hover:bg-blue-800 transition duration-200 translate-x-0 hover:-translate-x-1">
                <i class="fa fa-arrow-left text-md text-blue-50"></i>
            </a>
            <h1 class="text-xl font-semibold text-blue-800">Struktur Department</h1>
        </div>
        <a href="{{ route('master.departments.create') }}" class="bg-blue-900 hover:bg-blue-800 text-white px-4 py-2 rounded-full text-sm font-medium transition duration-200">
            <i class="fa fa-plus text-sm"></i> Tambah Department
        </a>
    </div>

    <div class="mt-6 bg-white shadow-md p-6">
        @if($departments->isEmpty())
        <p class="py-10 text-center text-sm text-slate-400">Belum ada department.</p>
        @else
        <ul class="space-y-1">
            @foreach($departments as $department)
            @include('master::departments._tree-node', ['department' => $department, 'depth' => 0])
            @endforeach
        </ul>
        @endif
    </div>
</div>
@endsection