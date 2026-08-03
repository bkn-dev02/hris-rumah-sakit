@extends('shared::layouts.app')

@section('title', 'Status Kepegawaian')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8">

    @if(session('success'))
    <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
        {{ session('error') }}
    </div>
    @endif

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="w-full flex justify-between items-center">
            <div class="flex items-center gap-4">
                <a href="{{ route('master.index') }}" class="border w-10 h-10 flex items-center justify-center rounded-full border-blue-100 bg-blue-900 hover:bg-blue-800 transition duration-200 translate-x-0 hover:-translate-x-1">
                    <i class="fa fa-arrow-left text-md text-blue-50"></i>
                </a>
                <h1 class="text-xl font-semibold text-blue-800">Status Kepegawaian</h1>
            </div>
            <div class="transition duration-200 translate-y-0 hover:-translate-y-1 hover:shadow-lg">
                <a href="{{ route('master.employment-statuses.create') }}" class="bg-blue-900 hover:bg-blue-800 text-white px-4 py-2 rounded-full transition duration-200">
                    <i class="fa fa-plus text-sm"></i>
                    <span class="text-gray-100">Tambah Status</span>
                </a>
            </div>
        </div>
    </div>

    <div class="mt-4 bg-white shadow-md p-4">
        <div class="border border-gray-200 rounded-md grid grid-cols-4 gap-4 p-4 text-gray-700 font-semibold">
            <span>Nama</span>
            <span>Kode</span>
            <span>Status</span>
            <span class="flex justify-center">Action</span>
        </div>

        @forelse($employmentStatuses as $employmentStatus)
        <div class="border border-gray-200 rounded-md grid grid-cols-4 gap-4 p-4 items-center text-gray-700">
            <span>{{ $employmentStatus->name }}</span>
            <span>
                <code class="rounded bg-slate-100 px-1.5 py-0.5 text-xs text-slate-600">{{ $employmentStatus->code }}</code>
            </span>
            <span>
                @if($employmentStatus->is_active)
                <x-shared::badge variant="success" dot>Aktif</x-shared::badge>
                @else
                <x-shared::badge variant="secondary" dot>Nonaktif</x-shared::badge>
                @endif
            </span>
            <div class="grid grid-cols-2 gap-2 w-full">
                <a href="{{ route('master.employment-statuses.edit', $employmentStatus->id) }}" class="bg-blue-900 hover:bg-blue-800 text-white rounded-full px-3 py-1 transition duration-200 flex items-center justify-center gap-1 text-center hover:-translate-y-1 hover:shadow-lg">
                    <i class="fa fa-pen text-sm"></i>
                    <span class="text-sm whitespace-nowrap">Edit</span>
                </a>
                <form method="POST" action="{{ route('master.employment-statuses.destroy', $employmentStatus->id) }}" onsubmit="return confirm('Hapus status ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full bg-red-900 hover:bg-red-800 text-white rounded-full px-3 py-1 transition duration-200 flex items-center justify-center gap-1 text-center hover:-translate-y-1 hover:shadow-lg">
                        <i class="fa fa-trash text-sm"></i>
                        <span class="text-sm whitespace-nowrap">Hapus</span>
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="border border-gray-200 rounded-md p-10 text-center text-sm text-slate-400">
            Belum ada status kepegawaian.
        </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $employmentStatuses->links() }}
    </div>

</div>
@endsection