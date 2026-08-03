{{-- create.blade.php --}}
@extends('shared::layouts.app')

@section('title', 'Tambah Role')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8">

    <h1 class="text-xl font-semibold text-slate-800">Tambah Role</h1>

    <form method="POST" action="{{ route('security.roles.store') }}" class="mt-6 rounded-lg border border-slate-200 bg-white p-6">
        @csrf
        @include('security::roles._form')

        <div class="mt-6 flex justify-end gap-3">
            <a href="{{ route('security.roles.index') }}" class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50">Batal</a>
            <x-shared::button type="submit" variant="primary">Simpan</x-shared::button>
        </div>
    </form>
</div>
@endsection