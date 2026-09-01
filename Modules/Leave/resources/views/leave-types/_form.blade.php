<div class="space-y-5">

    {{-- Kode --}}
    <div>
        <label
            for="code"
            class="mb-1.5 block text-sm font-semibold text-[#173f34]">
            Kode
        </label>

        <input
            id="code"
            type="text"
            name="code"
            value="{{ old('code', $leaveType->code ?? '') }}"
            placeholder="Contoh: CT01"
            class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-700 shadow-sm outline-none transition duration-200 placeholder:text-slate-400 focus:border-[#2a684f] focus:ring-2 focus:ring-[#dfeee1]">

        @error('code')
        <p class="mt-1.5 flex items-center gap-1.5 text-xs font-medium text-rose-600">
            <i class="fa-solid fa-circle-exclamation"></i>
            {{ $message }}
        </p>
        @enderror
    </div>


    {{-- Nama --}}
    <div>
        <label
            for="name"
            class="mb-1.5 block text-sm font-semibold text-[#173f34]">
            Nama
        </label>

        <input
            id="name"
            type="text"
            name="name"
            value="{{ old('name', $leaveType->name ?? '') }}"
            placeholder="Contoh: Cuti Tahunan"
            class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-700 shadow-sm outline-none transition duration-200 placeholder:text-slate-400 focus:border-[#2a684f] focus:ring-2 focus:ring-[#dfeee1]">

        @error('name')
        <p class="mt-1.5 flex items-center gap-1.5 text-xs font-medium text-rose-600">
            <i class="fa-solid fa-circle-exclamation"></i>
            {{ $message }}
        </p>
        @enderror
    </div>


    {{-- Deskripsi --}}
    <div>
        <label
            for="description"
            class="mb-1.5 block text-sm font-semibold text-[#173f34]">
            Deskripsi
        </label>

        <textarea
            id="description"
            name="description"
            rows="3"
            placeholder="Masukkan deskripsi jenis cuti..."
            class="w-full resize-none rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-700 shadow-sm outline-none transition duration-200 placeholder:text-slate-400 focus:border-[#2a684f] focus:ring-2 focus:ring-[#dfeee1]">{{ old('description', $leaveType->description ?? '') }}</textarea>

        @error('description')
        <p class="mt-1.5 flex items-center gap-1.5 text-xs font-medium text-rose-600">
            <i class="fa-solid fa-circle-exclamation"></i>
            {{ $message }}
        </p>
        @enderror
    </div>


    {{-- Options --}}
    <div class="grid grid-cols-1 gap-3 border-t border-slate-100 pt-5 sm:grid-cols-2">

        {{-- Butuh Kuota --}}
        <label class="flex cursor-pointer items-center gap-3 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 transition duration-200 hover:border-[#dfeee1] hover:bg-[#f8fbf8]">

            <input
                type="checkbox"
                name="requires_quota"
                value="1"
                @checked(old('requires_quota', $leaveType->requires_quota ?? true))
            class="h-4 w-4 rounded border-slate-300 text-[#1f4d3d] focus:ring-2 focus:ring-[#dfeee1]">

            <div>
                <p class="text-sm font-semibold text-[#173f34]">
                    Butuh Kuota
                </p>

                <p class="text-xs text-slate-400">
                    Cuti menggunakan batas kuota
                </p>
            </div>

        </label>


        {{-- Aktif --}}
        <label class="flex cursor-pointer items-center gap-3 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 transition duration-200 hover:border-[#dfeee1] hover:bg-[#f8fbf8]">

            <input
                type="checkbox"
                name="is_active"
                value="1"
                @checked(old('is_active', $leaveType->is_active ?? true))
            class="h-4 w-4 rounded border-slate-300 text-[#1f4d3d] focus:ring-2 focus:ring-[#dfeee1]">

            <div>
                <p class="text-sm font-semibold text-[#173f34]">
                    Aktif
                </p>

                <p class="text-xs text-slate-400">
                    Jenis cuti dapat digunakan
                </p>
            </div>

        </label>

    </div>

</div>
