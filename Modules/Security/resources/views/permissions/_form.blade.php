<div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">Module</label>
        <input type="text" name="module" list="module-list" value="{{ old('module', $permission->module ?? '') }}" placeholder="mis. Security, Master, Attendance"
            class="w-full rounded-lg border border-slate-200 py-2 px-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
        <datalist id="module-list">
            @foreach($modules as $module)
            <option value="{{ $module }}">
                @endforeach
        </datalist>
        @error('module') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">Nama Permission</label>
        <input type="text" name="name" value="{{ old('name', $permission->name ?? '') }}"
            class="w-full rounded-lg border border-slate-200 py-2 px-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
        @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="sm:col-span-2">
        <label class="mb-1 block text-sm font-medium text-slate-700">Kode</label>
        <input type="text" name="code" value="{{ old('code', $permission->code ?? '') }}" placeholder="mis. employees.view"
            class="w-full rounded-lg border border-slate-200 py-2 px-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
        <p class="mt-1 text-xs text-slate-400">Format: resource.aksi, huruf kecil. Kode ini dipakai langsung di route sebagai middleware — ubah dengan hati-hati kalau permission ini sudah dipakai.</p>
        @error('code') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="sm:col-span-2">
        <label class="mb-1 block text-sm font-medium text-slate-700">Deskripsi</label>
        <textarea name="description" rows="2" class="w-full rounded-lg border border-slate-200 py-2 px-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">{{ old('description', $permission->description ?? '') }}</textarea>
        @error('description') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="flex items-center gap-2 sm:col-span-2">
        <input type="checkbox" name="is_active" value="1" id="is_active"
            @checked(old('is_active', $permission->is_active ?? true))
        class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
        <label for="is_active" class="text-sm text-slate-700">Permission aktif</label>
    </div>
</div>