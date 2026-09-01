@php $isEdit = isset($department); @endphp

<div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
    <div>
        <label class="mb-1 block text-sm font-medium text-[#1f4d3d]">Nama Department</label>
        <input type="text" name="name" value="{{ old('name', $department->name ?? '') }}"
            class="w-full rounded-lg border border-[#dfeee1] py-2 px-3 text-sm focus:border-[#2a684f] focus:outline-none focus:ring-1 focus:ring-[#dfeee1]" placeholder="Cth: Departemen IT">
        @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-[#1f4d3d]">Kode</label>
        <input type="text" name="code" value="{{ old('code', $department->code ?? '') }}"
            class="w-full rounded-lg border border-[#dfeee1] py-2 px-3 text-sm focus:border-[#2a684f] focus:outline-none focus:ring-1 focus:ring-[#dfeee1]" placeholder="Cth: departemen_it">
        @error('code') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="sm:col-span-2">
        <label class="mb-1 block text-sm font-medium text-[#1f4d3d]">Department Induk</label>
        <select name="parent_id" class="w-full rounded-lg border border-[#dfeee1] py-2 px-3 text-sm focus:border-[#2a684f] focus:outline-none focus:ring-1 focus:ring-[#dfeee1]">
            <option value="">-- Tidak ada (level teratas) --</option>
            @foreach($departments as $option)
            <option value="{{ $option->id }}" @selected(old('parent_id', $department->parent_id ?? null) == $option->id)>
                {{ $option->name }}
            </option>
            @endforeach
        </select>
        @error('parent_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="flex items-center gap-2 sm:col-span-2">
        <input type="checkbox" name="is_active" value="1" id="is_active"
            @checked(old('is_active', $department->is_active ?? true))
        class="h-4 w-4 rounded border-slate-300 text-[#2a684f] focus:ring-[#dfeee1]">
        <label for="is_active" class="text-sm text-[#1f4d3d]">Department aktif</label>
    </div>
</div>
