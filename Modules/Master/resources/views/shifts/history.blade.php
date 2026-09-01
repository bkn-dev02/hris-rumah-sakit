@extends('shared::layouts.app')

@section('title', 'Riwayat Shift')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8">
    <div class="flex items-center gap-4">
        <a href="{{ route('master.shifts.index') }}" class="border w-10 h-10 flex items-center justify-center rounded-full border-[#dfeee1] bg-[#173f34] hover:bg-[#173f34] transition duration-200 translate-x-0 hover:-translate-x-1">
            <i class="fa fa-arrow-left text-md text-[#edf5ee]"></i>
        </a>
        <h1 class="text-xl font-semibold text-[#1f4d3d]">Riwayat Perubahan Shift â€” {{ $code }}</h1>
    </div>

    <div class="mt-6 overflow-hidden rounded-lg border border-[#dfeee1] bg-white">
        <table class="w-full text-left text-sm">
            <thead class="border-b border-[#dfeee1] bg-[#f8fbf8] text-xs uppercase tracking-wide text-[#2a684f]">
                <tr>
                    <th class="px-4 py-3">Nama</th>
                    <th class="px-4 py-3">Jam Kerja</th>
                    <th class="px-4 py-3">Berlaku Dari</th>
                    <th class="px-4 py-3">Berlaku Sampai</th>
                    <th class="px-4 py-3">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-sky-100">
                @foreach($history as $version)
                <tr>
                    <td class="px-4 py-3 text-[#2a684f]">{{ $version->name }}</td>
                    <td class="px-4 py-3 text-[#2a684f]">{{ $version->start_time }} - {{ $version->end_time }}</td>
                    <td class="px-4 py-3 text-[#2a684f]">{{ $version->effective_date->format('d M Y') }}</td>
                    <td class="px-4 py-3 text-[#2a684f]">{{ $version->end_date?->format('d M Y') ?? '-' }}</td>
                    <td class="px-4 py-3">
                        @if($version->isCurrent())
                        <x-shared::badge variant="success" dot>Berlaku</x-shared::badge>
                        @else
                        <x-shared::badge variant="secondary" dot>Riwayat</x-shared::badge>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
