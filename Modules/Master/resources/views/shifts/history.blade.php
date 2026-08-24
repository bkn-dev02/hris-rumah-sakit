@extends('shared::layouts.app')

@section('title', 'Riwayat Shift')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8">
    <div class="flex items-center gap-4">
        <a href="{{ route('master.shifts.index') }}" class="border w-10 h-10 flex items-center justify-center rounded-full border-sky-100 bg-sky-900 hover:bg-sky-800 transition duration-200 translate-x-0 hover:-translate-x-1">
            <i class="fa fa-arrow-left text-md text-blue-50"></i>
        </a>
        <h1 class="text-xl font-semibold text-sky-800">Riwayat Perubahan Shift — {{ $code }}</h1>
    </div>

    <div class="mt-6 overflow-hidden rounded-lg border border-sky-200 bg-white">
        <table class="w-full text-left text-sm">
            <thead class="border-b border-sky-200 bg-sky-50 text-xs uppercase tracking-wide text-sky-500">
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
                    <td class="px-4 py-3 text-sky-700">{{ $version->name }}</td>
                    <td class="px-4 py-3 text-sky-600">{{ $version->start_time }} - {{ $version->end_time }}</td>
                    <td class="px-4 py-3 text-sky-500">{{ $version->effective_date->format('d M Y') }}</td>
                    <td class="px-4 py-3 text-sky-500">{{ $version->end_date?->format('d M Y') ?? '-' }}</td>
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