@extends('shared::layouts.app')

@section('title', 'Detail Pengajuan Cuti')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:py-8">

    {{-- Header --}}
    <div class="mb-6 flex items-center gap-3">
        <a href="{{ route('leave.index') }}"
            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-sky-950 text-white shadow-sm transition hover:bg-sky-900 focus:outline-none focus:ring-2 focus:ring-sky-500/30">
            <i class="fa-solid fa-arrow-left text-sm"></i>
        </a>

        <div>
            <h1 class="text-xl font-bold text-sky-950">
                Detail Pengajuan Cuti
            </h1>
            <p class="mt-0.5 text-xs text-sky-500">
                Informasi lengkap pengajuan dan riwayat persetujuan
            </p>
        </div>
    </div>

    {{-- Success Message --}}
    @if (session('success'))
    <div class="mb-5 flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
        <i class="fa-solid fa-circle-check mt-0.5 text-emerald-600"></i>

        <span>{{ session('success') }}</span>
    </div>
    @endif

    <div class="grid gap-6 lg:grid-cols-2">

        {{-- Detail Pengajuan --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

            {{-- Card Header --}}
            <div class="border-b border-slate-100 bg-slate-50/70 px-6 py-4">
                <div class="flex items-center gap-2">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-sky-100 text-sky-700">
                        <i class="fa-solid fa-file-lines text-sm"></i>
                    </div>

                    <div>
                        <h2 class="text-sm font-semibold text-sky-950">
                            Informasi Pengajuan
                        </h2>
                        <p class="text-xs text-slate-500">
                            Detail permohonan cuti karyawan
                        </p>
                    </div>
                </div>
            </div>

            {{-- Detail --}}
            <div class="divide-y divide-slate-100">

                {{-- Karyawan --}}
                <div class="px-6 py-4 sm:flex sm:items-center">
                    <div class="sm:w-1/3">
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                            Karyawan
                        </p>
                    </div>

                    <div class="mt-1 sm:mt-0 sm:w-2/3">
                        <p class="text-sm font-semibold text-sky-950">
                            {{ $leaveRequest->employee->name }}
                        </p>
                    </div>
                </div>

                {{-- Jenis Cuti --}}
                <div class="px-6 py-4 sm:flex sm:items-center">
                    <div class="sm:w-1/3">
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                            Jenis Cuti
                        </p>
                    </div>

                    <div class="mt-1 sm:mt-0 sm:w-2/3">
                        <p class="text-sm text-slate-700">
                            {{ $leaveRequest->leaveType->name }}
                        </p>
                    </div>
                </div>

                {{-- Tanggal --}}
                <div class="px-6 py-4 sm:flex sm:items-center">
                    <div class="sm:w-1/3">
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                            Tanggal Cuti
                        </p>
                    </div>

                    <div class="mt-1 sm:mt-0 sm:w-2/3">
                        <p class="text-sm text-slate-700">
                            {{ $leaveRequest->start_date->format('d M Y') }}
                            <span class="mx-1 text-slate-400">—</span>
                            {{ $leaveRequest->end_date->format('d M Y') }}
                        </p>

                        <p class="mt-1 text-xs text-slate-400">
                            {{ $leaveRequest->total_days }} hari kerja
                        </p>
                    </div>
                </div>

                {{-- Alasan --}}
                <div class="px-6 py-4 sm:flex">
                    <div class="sm:w-1/3">
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                            Alasan
                        </p>
                    </div>

                    <div class="mt-1 sm:mt-0 sm:w-2/3">
                        <p class="whitespace-pre-line text-sm leading-6 text-slate-700">
                            {{ $leaveRequest->reason }}
                        </p>
                    </div>
                </div>

                {{-- Status --}}
                <div class="px-6 py-4 sm:flex sm:items-center">
                    <div class="sm:w-1/3">
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                            Status
                        </p>
                    </div>

                    <div class="mt-1 sm:mt-0 sm:w-2/3">
                        <span class="inline-flex items-center rounded-full bg-sky-100 px-3 py-1 text-xs font-semibold text-sky-700">
                            {{ $leaveRequest->statusLabel() }}
                        </span>
                    </div>
                </div>

            </div>
        </div>

        {{-- Riwayat Persetujuan --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

            {{-- Card Header --}}
            <div class="border-b border-slate-100 bg-slate-50/70 px-6 py-4">
                <div class="flex items-center gap-2">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-sky-100 text-sky-700">
                        <i class="fa-solid fa-list-check text-sm"></i>
                    </div>

                    <div>
                        <h2 class="text-sm font-semibold text-sky-950">
                            Riwayat Persetujuan
                        </h2>
                        <p class="text-xs text-slate-500">
                            Tahapan proses persetujuan pengajuan
                        </p>
                    </div>
                </div>
            </div>

            <div class="p-6">

                <ol class="relative space-y-6">

                    @foreach ($leaveRequest->approvals as $approval)

                    @php
                    $stepColor = match ($approval->status) {
                    'approved' => 'emerald',
                    'rejected' => 'rose',
                    default => 'amber',
                    };
                    @endphp

                    <li class="relative flex gap-4">

                        {{-- Connector --}}
                        @if (!$loop->last)
                        <div class="absolute left-3 top-7 h-[calc(100%+1.5rem)] w-px bg-slate-200"></div>
                        @endif

                        {{-- Sequence --}}
                        <span class="relative z-10 flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-{{ $stepColor }}-100 text-xs font-bold text-{{ $stepColor }}-700 ring-4 ring-white">
                            {{ $approval->sequence }}
                        </span>

                        {{-- Approval Content --}}
                        <div class="min-w-0 flex-1">

                            <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                                <p class="text-sm font-semibold text-slate-700">
                                    {{ $approval->approver->name }}
                                </p>

                                <span class="text-xs text-slate-400">
                                    {{ $approval->typeLabel() }}
                                </span>
                            </div>

                            <p class="mt-1 text-xs font-medium text-{{ $stepColor }}-700">
                                @if ($approval->status === 'pending')
                                <i class="fa-solid fa-clock mr-1"></i>
                                Menunggu persetujuan
                                @else
                                @if ($approval->status === 'approved')
                                <i class="fa-solid fa-circle-check mr-1"></i>
                                Disetujui
                                @else
                                <i class="fa-solid fa-circle-xmark mr-1"></i>
                                Ditolak
                                @endif

                                @if ($approval->decided_at)
                                <span class="ml-1 font-normal text-slate-400">
                                    — {{ $approval->decided_at->format('d M Y H:i') }}
                                </span>
                                @endif
                                @endif
                            </p>

                            @if ($approval->note)
                            <div class="mt-2 rounded-lg bg-slate-50 px-3 py-2">
                                <p class="text-xs italic leading-5 text-slate-500">
                                    "{{ $approval->note }}"
                                </p>
                            </div>
                            @endif

                        </div>
                    </li>

                    @endforeach

                </ol>

            </div>
        </div>

    </div>

    @if ($leaveRequest->attachment)
    @php
    $attachmentUrl = Storage::disk('public')->url($leaveRequest->attachment);
    $attachmentExtension = strtolower(pathinfo($leaveRequest->attachment, PATHINFO_EXTENSION));
    $isImageAttachment = in_array($attachmentExtension, ['jpg', 'jpeg', 'png'], true);
    $isPdfAttachment = $attachmentExtension === 'pdf';
    @endphp

    <div class="mt-6 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="flex flex-col gap-3 border-b border-slate-100 bg-slate-50/70 px-6 py-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-2">
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-sky-100 text-sky-700">
                    <i class="fa-solid fa-paperclip text-sm"></i>
                </div>

                <div>
                    <h2 class="text-sm font-semibold text-sky-950">Lampiran</h2>
                    <p class="text-xs text-slate-500">Dokumen pendukung pengajuan cuti</p>
                </div>
            </div>

            <a href="{{ route('leave.attachment.download', $leaveRequest) }}"
                class="inline-flex items-center justify-center gap-2 rounded-lg bg-sky-950 px-3 py-2 text-xs font-semibold text-white transition hover:bg-sky-900 focus:outline-none focus:ring-2 focus:ring-sky-500/30">
                <i class="fa-solid fa-download text-xs"></i>
                Download
            </a>
        </div>

        <div class="p-4 sm:p-6">
            @if ($isImageAttachment)
            <div class="flex min-h-64 items-center justify-center overflow-hidden rounded-xl bg-slate-100">
                <img src="{{ $attachmentUrl }}" alt="Preview lampiran pengajuan cuti" loading="lazy"
                    class="max-h-[32rem] w-full object-contain">
            </div>
            @elseif ($isPdfAttachment)
            <iframe src="{{ $attachmentUrl }}#toolbar=1" title="Preview lampiran pengajuan cuti" loading="lazy"
                class="h-[32rem] w-full rounded-xl border border-slate-200 bg-slate-100"></iframe>
            @else
            <div class="flex min-h-48 flex-col items-center justify-center rounded-xl bg-slate-50 px-6 text-center">
                <i class="fa-solid fa-file-word mb-3 text-4xl text-sky-600"></i>
                <p class="text-sm font-semibold text-slate-700">Preview belum tersedia untuk format ini</p>
                <p class="mt-1 text-xs text-slate-500">Gunakan tombol Download untuk membuka dokumen.</p>
            </div>
            @endif
        </div>
    </div>
    @endif

    {{-- Decision --}}
    @if ($canDecide)
    <div class="mt-6 overflow-hidden rounded-2xl border border-amber-200 bg-white shadow-sm">

        <div class="border-b border-amber-200 bg-amber-50 px-6 py-4">
            <div class="flex items-center gap-3">
                <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-amber-100 text-amber-700">
                    <i class="fa-solid fa-gavel text-sm"></i>
                </div>

                <div>
                    <h2 class="text-sm font-semibold text-amber-800">
                        Giliran Anda untuk Memutuskan
                    </h2>
                    <p class="mt-0.5 text-xs text-amber-700/70">
                        Berikan keputusan terhadap pengajuan cuti ini
                    </p>
                </div>
            </div>
        </div>

        <div class="p-6">
            <form method="POST"
                action="{{ route('leave.decide', $leaveRequest) }}"
                class="space-y-4">

                @csrf

                <div>
                    <label for="note" class="mb-1.5 block text-xs font-semibold text-slate-600">
                        Catatan
                        <span class="font-normal text-slate-400">(opsional)</span>
                    </label>

                    <textarea
                        id="note"
                        name="note"
                        rows="3"
                        placeholder="Tambahkan catatan jika diperlukan..."
                        class="w-full resize-none rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-700 placeholder:text-slate-400 focus:border-sky-500 focus:ring-2 focus:ring-sky-500/20"></textarea>
                </div>

                <div class="flex flex-col gap-2 sm:flex-row sm:justify-end">

                    <button
                        type="submit"
                        name="decision"
                        value="reject"
                        class="inline-flex items-center justify-center gap-2 rounded-lg bg-rose-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-rose-500/30">
                        <i class="fa-solid fa-xmark text-xs"></i>
                        Tolak
                    </button>

                    <button
                        type="submit"
                        name="decision"
                        value="approve"
                        class="inline-flex items-center justify-center gap-2 rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500/30">
                        <i class="fa-solid fa-check text-xs"></i>
                        Setujui
                    </button>

                </div>

            </form>
        </div>
    </div>
    @endif

</div>
@endsection