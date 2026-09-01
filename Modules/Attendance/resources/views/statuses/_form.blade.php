<div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
    <div>
        <label class="mb-1 block text-sm font-medium text-[#2a684f]">Nama Status</label>
        <input type="text" name="name" value="{{ old('name', $status->name ?? '') }}"
            class="w-full rounded-lg border border-[#dfeee1] py-2 px-3 text-sm focus:border-[#2a684f] focus:outline-none focus:ring-1 focus:ring-[#dfeee1]">
        @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-[#2a684f]">Kode</label>
        <input type="text" name="code" value="{{ old('code', $status->code ?? '') }}" placeholder="mis. HADIR, TERLAMBAT"
            class="w-full rounded-lg border border-[#dfeee1] py-2 px-3 text-sm focus:border-[#2a684f] focus:outline-none focus:ring-1 focus:ring-[#dfeee1]">
        <p class="mt-1 text-xs text-[#2a684f]">Kode ini dipakai sistem secara internal. Hati-hati mengubah kode HADIR, TERLAMBAT, PULANG_CEPAT, LUPA_CHECKOUT â€” dipakai proses otomatis.</p>
        @error('code') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-[#2a684f]">Kategori</label>
        <select name="category" class="w-full rounded-lg border border-[#dfeee1] py-2 px-3 text-sm focus:border-[#2a684f] focus:outline-none focus:ring-1 focus:ring-[#dfeee1]">
            @foreach(['normal' => 'Normal', 'exception' => 'Exception (hasil approval)', 'review' => 'Perlu Review'] as $value => $label)
            <option value="{{ $value }}" @selected(old('category', $status->category ?? 'normal') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @error('category') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-[#2a684f]">Tipe Penentuan</label>
        <select name="determination_type" class="w-full rounded-lg border border-[#dfeee1] py-2 px-3 text-sm focus:border-[#2a684f] focus:outline-none focus:ring-1 focus:ring-[#dfeee1]">
            <option value="auto" @selected(old('determination_type', $status->determination_type ?? '') === 'auto')>Otomatis (dihitung sistem)</option>
            <option value="manual" @selected(old('determination_type', $status->determination_type ?? '') === 'manual')>Manual (admin/approval)</option>
        </select>
        @error('determination_type') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="flex items-center gap-2 sm:col-span-2">
        <input type="checkbox" name="is_active" value="1" id="is_active"
            @checked(old('is_active', $status->is_active ?? true))
        class="h-4 w-4 rounded border-[#dfeee1] text-[#2a684f] focus:ring-[#dfeee1]">
        <label for="is_active" class="text-sm text-[#2a684f]">Status aktif</label>
    </div>
</div>
