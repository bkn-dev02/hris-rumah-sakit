@extends('shared::layouts.app')

@section('title', 'Permission Management')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8">

    @if(session('success'))
    <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">{{ session('error') }}</div>
    @endif

    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('security.index') }}" class="border w-10 h-10 flex items-center justify-center rounded-full border-[#dfeee1] bg-[#1f4d3d] hover:bg-[#2a684f] transition duration-200 translate-x-0 hover:-translate-x-1">
                <i class="fa fa-arrow-left text-md text-[#edf5ee]"></i>
            </a>
            <h1 class="text-xl font-semibold text-[#1f4d3d]">Permission Management</h1>
        </div>
        <a href="{{ route('security.permissions.create') }}" class="bg-[#1f4d3d] hover:bg-[#2a684f] text-white px-4 py-2 rounded-full text-sm font-medium transition duration-200">
            <i class="fa fa-plus text-sm"></i> Tambah Permission
        </a>
    </div>

    <form method="GET" class="mt-6 flex items-end gap-3">
        <div>
            <label class="mb-1 block text-xs font-medium text-slate-500">Filter Module</label>
            <select name="module" onchange="this.form.submit()" class="rounded-lg border border-slate-200 py-2 px-3 text-sm focus:border-[#2a684f] focus:outline-none focus:ring-1 focus:ring-[#dfeee1]">
                <option value="">Semua Module</option>
                @foreach($modules as $module)
                <option value="{{ $module }}" @selected(request('module')===$module)>{{ $module }}</option>
                @endforeach
            </select>
        </div>
        @if(request('module'))
        <a href="{{ route('security.permissions.index') }}" class="text-sm text-slate-500 hover:text-[#2a684f]">Reset</a>
        @endif
    </form>

    <div class="mt-4 bg-white shadow-md p-4">
        <div class="border border-gray-200 rounded-md grid grid-cols-6 gap-4 p-4 text-gray-700 font-semibold text-sm">
            <span>Module</span>
            <span>Nama</span>
            <span>Kode</span>
            <span>Status</span>
            <span></span>
            <span class="flex justify-center">Aksi</span>
        </div>

        @forelse($permissions as $permission)
        <div class="border border-gray-200 rounded-md grid grid-cols-6 gap-4 p-4 items-center text-gray-700 text-sm">
            <span><x-shared::badge variant="secondary" size="sm">{{ $permission->module }}</x-shared::badge></span>
            <span class="font-medium text-slate-800">{{ $permission->name }}</span>
            <code class="rounded bg-slate-100 px-1.5 py-0.5 text-xs text-slate-600 w-fit">{{ $permission->code }}</code>
            <span>
                @if($permission->is_active)
                <x-shared::badge variant="success" dot>Aktif</x-shared::badge>
                @else
                <x-shared::badge variant="secondary" dot>Nonaktif</x-shared::badge>
                @endif
            </span>
            <span></span>
            <div class="grid grid-cols-2 gap-2 w-full">
                <a href="{{ route('security.permissions.edit', $permission->id) }}" class="bg-[#1f4d3d] hover:bg-[#2a684f] text-white rounded-full px-3 py-1 transition duration-200 flex items-center justify-center gap-1 hover:-translate-y-1 hover:shadow-lg">
                    <i class="fa fa-pen text-sm"></i> Edit
                </a>
                <form method="POST" action="{{ route('security.permissions.destroy', $permission->id) }}" onsubmit="return confirm('Hapus permission ini? Pastikan tidak ada route yang masih memakainya.')">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full bg-red-900 hover:bg-red-800 text-white rounded-full px-3 py-1 transition duration-200 flex items-center justify-center gap-1 hover:-translate-y-1 hover:shadow-lg">
                        <i class="fa fa-trash text-sm"></i> Hapus
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="border border-gray-200 rounded-md p-10 text-center text-sm text-slate-400">Belum ada permission.</div>
        @endforelse
    </div>

    <div class="mt-4">{{ $permissions->links() }}</div>

</div>
@endsection
