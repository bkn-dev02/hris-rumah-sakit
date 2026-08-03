<div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">Pegawai</label>
        <select name="employee_id" class="w-full rounded-lg border border-slate-200 py-2 px-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
            @foreach($employees as $employee)
            <option value="{{ $employee->id }}" @selected(old('employee_id', $checkIn->employee_id ?? '') == $employee->id)>{{ $employee->name }}</option>
            @endforeach
        </select>
        @error('employee_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">Waktu Check-In</label>
        <input type="datetime-local" name="checked_at" value="{{ old('checked_at', optional($checkIn->checked_at)->format('Y-m-d\TH:i') ?? '') }}"
            class="w-full rounded-lg border border-slate-200 py-2 px-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
        @error('checked_at') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">Latitude</label>
        <input type="text" name="latitude" value="{{ old('latitude', $checkIn->latitude ?? '') }}"
            class="w-full rounded-lg border border-slate-200 py-2 px-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
        @error('latitude') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">Longitude</label>
        <input type="text" name="longitude" value="{{ old('longitude', $checkIn->longitude ?? '') }}"
            class="w-full rounded-lg border border-slate-200 py-2 px-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
        @error('longitude') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="sm:col-span-2">
        <label class="mb-1 block text-sm font-medium text-slate-700">Lokasi</label>
        <select name="location_id" class="w-full rounded-lg border border-slate-200 py-2 px-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
            @foreach($locations as $location)
            <option value="{{ $location->id }}" @selected(old('location_id', $checkIn->location_id ?? '') == $location->id)>{{ $location->name }}</option>
            @endforeach
        </select>
        @error('location_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="sm:col-span-2">
        <label class="mb-1 block text-sm font-medium text-slate-700">Catatan</label>
        <textarea name="note" rows="3" class="w-full rounded-lg border border-slate-200 py-2 px-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">{{ old('note', $checkIn->note ?? '') }}</textarea>
        @error('note') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>
</div>