@extends('shared::layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8">
    <div class="flex items-center gap-4">
        <a
            href="{{ route('security.users.index') }}"
            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#173f34] text-white shadow-sm shadow-[#173f34]/20 transition-all duration-200 hover:-translate-x-0.5 hover:bg-[#173f34] hover:shadow-md focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-sky-700"
            title="Kembali ke Security Module">
            <i class="fa-solid fa-arrow-left text-sm"></i>
        </a>
        <h1 class="text-xl font-semibold text-slate-800">Edit User</h1>
    </div>

    <form method="POST" action="{{ route('security.users.update', $user->slug) }}" class="mt-6 rounded-lg border border-slate-200 bg-white p-6">
        @csrf
        @method('PUT')
        @include('security::users._form')

        <div class="mt-6 flex justify-end gap-3">
            <a href="{{ route('security.users.index') }}" class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50">
                Batal
            </a>
            <x-shared::button type="submit" variant="primary">Simpan Perubahan</x-shared::button>
        </div>
    </form>
</div>
@endsection
