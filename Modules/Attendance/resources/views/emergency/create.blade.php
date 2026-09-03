@extends('shared::layouts.app')

@section('title', 'Presensi Darurat')

@section('content')
<div class="mx-auto max-w-2xl px-3 py-4 sm:px-6 sm:py-8">

    <div class="mb-5 rounded-2xl bg-gradient-to-br from-[#042A22] via-[#0F5C48] to-[#1B7A5C] p-5 text-white shadow-sm sm:p-6">
        <h1 class="text-xl font-bold">Presensi Darurat</h1>
        <p class="mt-1 text-sm text-white/70">
            Gunakan fitur ini jika Anda terkendala hadir/check-in normal (misal kendaraan mogok, lokasi tidak terjangkau, dsb).
        </p>
    </div>

    @if (session('success'))
    <div class="mb-4 rounded-xl bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700 ring-1 ring-emerald-200">
        {{ session('success') }}
    </div>
    @endif

    @if (session('error'))
    <div class="mb-4 rounded-xl bg-rose-50 px-4 py-3 text-sm font-medium text-rose-700 ring-1 ring-rose-200">
        {{ session('error') }}
    </div>
    @endif

    @if ($existing)
    {{-- Sudah pernah mengajukan hari ini — tampilkan status, jangan tampilkan form --}}
    @php
    $statusMap = [
    'pending' => ['Menunggu Persetujuan', 'bg-amber-100 text-amber-700'],
    'approved' => ['Disetujui', 'bg-emerald-100 text-emerald-700'],
    'rejected' => ['Ditolak', 'bg-rose-100 text-rose-700'],
    ];
    [$statusLabel, $statusClass] = $statusMap[$existing->emergency_status] ?? ['-', 'bg-slate-100 text-slate-600'];
    @endphp
    <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-black/5">
        <div class="flex items-center justify-between">
            <h2 class="text-sm font-bold text-slate-800">Pengajuan Hari Ini</h2>
            <span class="rounded-full px-3 py-1 text-xs font-bold {{ $statusClass }}">{{ $statusLabel }}</span>
        </div>
        <p class="mt-2 text-sm text-slate-600">
            Diajukan pukul {{ $existing->checked_at->format('H:i') }}
        </p>
        <p class="mt-1 text-sm text-slate-600">
            <span class="font-semibold">Alasan:</span> {{ $existing->emergency_reason }}
        </p>
        @if ($existing->emergency_status !== 'pending' && $existing->emergency_decision_note)
        <div class="mt-3 rounded-xl bg-slate-50 p-3 text-sm text-slate-600">
            <span class="font-semibold">Catatan HRD:</span> {{ $existing->emergency_decision_note }}
        </div>
        @endif
    </div>
    @else
    <form action="{{ route('attendance.emergency-request.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4" id="emergencyForm">
        @csrf
        <input type="hidden" name="latitude" id="latitude">
        <input type="hidden" name="longitude" id="longitude">

        <div class="rounded-2xl bg-white p-4 shadow-sm ring-1 ring-black/5">
            <div class="flex items-center justify-between">
                <span class="text-sm font-semibold text-slate-700">
                    <i class="fas fa-map-marker-alt text-[#0F5C48]"></i> Lokasi Anda
                </span>
                <button type="button" id="retryLocationBtn" class="text-xs font-semibold text-[#0F5C48] hover:underline">
                    Coba Lagi
                </button>
            </div>
            <p id="locationStatus" class="mt-2 text-xs text-slate-500">Mendeteksi lokasi Anda...</p>
        </div>
        @error('latitude')
        <p class="text-xs font-medium text-rose-600">{{ $message }}</p>
        @enderror

        <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-black/5">
            <label class="mb-2 block text-sm font-semibold text-slate-700">Foto Selfie *</label>
            <input type="file" name="selfie_photo" id="selfiePhoto" accept="image/*" capture="user" required
                class="block w-full text-sm text-slate-600 file:mr-3 file:rounded-lg file:border-0 file:bg-[#0F5C48]/10 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-[#0F5C48] hover:file:bg-[#0F5C48]/20">
            <img id="selfiePreview" class="mt-3 hidden h-40 w-40 rounded-xl object-cover ring-1 ring-black/10">
            @error('selfie_photo')
            <p class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-black/5">
            <label class="mb-2 block text-sm font-semibold text-slate-700">Foto Bukti Kendala *</label>
            <p class="mb-2 text-xs text-slate-500">Contoh: foto kendaraan mogok, banjir, dsb.</p>
            <input type="file" name="proof_photo" id="proofPhoto" accept="image/*" required
                class="block w-full text-sm text-slate-600 file:mr-3 file:rounded-lg file:border-0 file:bg-[#0F5C48]/10 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-[#0F5C48] hover:file:bg-[#0F5C48]/20">
            <img id="proofPreview" class="mt-3 hidden h-40 w-40 rounded-xl object-cover ring-1 ring-black/10">
            @error('proof_photo')
            <p class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-black/5">
            <label class="mb-2 block text-sm font-semibold text-slate-700">Alasan / Keterangan *</label>
            <textarea name="reason" rows="4" required maxlength="500"
                class="w-full rounded-xl border border-slate-200 p-3 text-sm text-slate-700 focus:border-[#0F5C48] focus:outline-none focus:ring-1 focus:ring-[#0F5C48]"
                placeholder="Jelaskan kendala yang Anda alami...">{{ old('reason') }}</textarea>
            @error('reason')
            <p class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" id="emergencySubmitBtn" disabled
            class="w-full rounded-xl bg-gradient-to-br from-[#0F5C48] to-[#1B7A5C] px-4 py-3 text-sm font-bold text-white shadow-sm transition hover:opacity-90 opacity-50 cursor-not-allowed">
            Kirim Presensi Darurat
        </button>
    </form>
    @endif

</div>

<script>
    // Ambil lokasi (opsional — fitur ini tidak location-locked, cuma untuk info tambahan HRD)
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(pos) {
            document.getElementById('latitude').value = pos.coords.latitude;
            document.getElementById('longitude').value = pos.coords.longitude;
        }, function() {
            // Ditolak/gagal — biarkan kosong, tidak menghalangi submit
        });
    }

    // Preview foto sebelum upload
    function bindPreview(inputId, previewId) {
        var input = document.getElementById(inputId);
        var preview = document.getElementById(previewId);
        if (!input || !preview) return;
        input.addEventListener('change', function() {
            var file = this.files[0];
            if (!file) {
                preview.classList.add('hidden');
                return;
            }
            preview.src = URL.createObjectURL(file);
            preview.classList.remove('hidden');
        });
    }
    bindPreview('selfiePhoto', 'selfiePreview');
    bindPreview('proofPhoto', 'proofPreview');

    (function() {
        var latInput = document.getElementById('latitude');
        var lngInput = document.getElementById('longitude');
        var submitBtn = document.getElementById('emergencySubmitBtn');
        var locStatus = document.getElementById('locationStatus');

        function setStatus(msg, kind) {
            if (!locStatus) return;
            locStatus.textContent = msg;
            locStatus.className = 'mt-2 text-xs font-medium ' + (kind === 'error' ? 'text-rose-600' : kind === 'ok' ? 'text-emerald-600' : 'text-slate-500');
        }

        function lockSubmit(locked) {
            if (!submitBtn) return;
            submitBtn.disabled = locked;
            submitBtn.classList.toggle('opacity-50', locked);
            submitBtn.classList.toggle('cursor-not-allowed', locked);
        }

        function requestLocation() {
            if (!navigator.geolocation) {
                setStatus('Perangkat/browser Anda tidak mendukung deteksi lokasi. Gunakan browser lain.', 'error');
                lockSubmit(true);
                return;
            }

            setStatus('Mendeteksi lokasi Anda...', 'loading');
            lockSubmit(true);

            navigator.geolocation.getCurrentPosition(function(pos) {
                latInput.value = pos.coords.latitude;
                lngInput.value = pos.coords.longitude;
                setStatus('Lokasi berhasil terdeteksi.', 'ok');
                lockSubmit(false);
            }, function(err) {
                var msg = 'Gagal mendapatkan lokasi. ';
                if (err.code === err.PERMISSION_DENIED) {
                    msg += 'Izinkan akses lokasi di pengaturan browser Anda, lalu coba lagi.';
                } else {
                    msg += 'Pastikan GPS aktif dan koneksi stabil, lalu coba lagi.';
                }
                setStatus(msg, 'error');
                lockSubmit(true);
            }, {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0,
            });
        }

        requestLocation();

        var retryBtn = document.getElementById('retryLocationBtn');
        if (retryBtn) retryBtn.addEventListener('click', requestLocation);
    })();

    // Preview foto sebelum upload
    function bindPreview(inputId, previewId) {
        var input = document.getElementById(inputId);
        var preview = document.getElementById(previewId);
        if (!input || !preview) return;
        input.addEventListener('change', function() {
            var file = this.files[0];
            if (!file) {
                preview.classList.add('hidden');
                return;
            }
            preview.src = URL.createObjectURL(file);
            preview.classList.remove('hidden');
        });
    }
    bindPreview('selfiePhoto', 'selfiePreview');
    bindPreview('proofPhoto', 'proofPreview');
</script>
@endsection