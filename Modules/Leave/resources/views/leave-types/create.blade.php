@extends('shared::layouts.app')

@section('title', 'Tambah Jenis Cuti')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8">

    {{-- Header --}}
    <div class="mb-6 flex items-center gap-3 rounded-xl bg-[#edf5ee] px-5 py-4 shadow-sm">

        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white text-[#173f34] shadow-sm">
            <i class="fa-solid fa-calendar-plus"></i>
        </div>

        <div>
            <h1 class="text-xl font-bold text-[#173f34]">
                Tambah Jenis Cuti
            </h1>

            <p class="mt-0.5 text-xs text-slate-500">
                Tambahkan jenis cuti baru ke dalam sistem
            </p>
        </div>

    </div>


    {{-- Form --}}
    <form
        action="{{ route('leave.leave-types.store') }}"
        method="POST"
        class="rounded-2xl border border-[#dfeee1] bg-[#edf5ee] p-5 shadow-md sm:p-6">

        @csrf

        <div class="rounded-xl bg-white p-5 shadow-sm">

            @include('leave::leave-types._form')

        </div>


        {{-- Actions --}}
        <div class="mt-5 flex items-center justify-end gap-3">

            <a
                href="{{ route('leave.leave-types.index') }}"
                class="inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2.5 text-sm font-medium text-slate-600 shadow-sm ring-1 ring-slate-200 transition duration-200 hover:-translate-y-0.5 hover:bg-slate-50 hover:text-slate-800">

                <i class="fa-solid fa-arrow-left text-xs"></i>
                Batal

            </a>

            <button
                type="submit"
                class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-br from-[#173f34] via-[#1f4d3d] to-[#2a684f] px-5 py-2.5 text-sm font-medium text-white shadow-md transition duration-200 hover:-translate-y-0.5 hover:shadow-lg">

                <i class="fa-solid fa-floppy-disk text-xs"></i>
                Simpan

            </button>

        </div>

    </form>

</div>
@endsection
