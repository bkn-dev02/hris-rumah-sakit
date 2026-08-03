<div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">Nama Position</label>
        <input type="text" name="name" value="{{ old('name', $position->name ?? '') }}"
            class="w-full rounded-lg border border-slate-200 py-2 px-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
        @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">Kode</label>
        <input type="text" name="code" value="{{ old('code', $position->code ?? '') }}"
            class="w-full rounded-lg border border-slate-200 py-2 px-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
        @error('code') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">Level Jenjang</label>
        <input type="number" name="level" min="1" value="{{ old('level', $position->level ?? '') }}"
            class="w-full rounded-lg border border-slate-200 py-2 px-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
        <p class="mt-1 text-xs text-slate-400">Angka lebih besar = jenjang lebih tinggi. Dipakai sistem untuk menentukan promosi/demosi otomatis.</p>
        @error('level') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="flex items-center gap-2">
        <input type="checkbox" name="is_active" value="1" id="is_active"
            @checked(old('is_active', $position->is_active ?? true))
        class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
        <label for="is_active" class="text-sm text-slate-700">Position aktif</label>
    </div>
</div>