<div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
    <div class="sm:col-span-2">
        <label class="mb-1 block text-sm font-medium text-sky-700">Nama Lokasi</label>
        <input type="text" name="name" value="{{ old('name', $location->name ?? '') }}"
            class="w-full rounded-lg border border-sky-200 py-2 px-3 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500">
        @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-sky-700">Latitude</label>
        <input type="text" name="latitude" value="{{ old('latitude', $location->latitude ?? '') }}" placeholder="-6.2000000"
            class="w-full rounded-lg border border-sky-200 py-2 px-3 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500">
        @error('latitude') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-sky-700">Longitude</label>
        <input type="text" name="longitude" value="{{ old('longitude', $location->longitude ?? '') }}" placeholder="106.8166667"
            class="w-full rounded-lg border border-sky-200 py-2 px-3 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500">
        @error('longitude') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-sky-700">Radius (meter)</label>
        <input type="number" name="radius_meters" min="10" value="{{ old('radius_meters', $location->radius_meters ?? 100) }}"
            class="w-full rounded-lg border border-sky-200 py-2 px-3 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500">
        <p class="mt-1 text-xs text-sky-400">Jarak maksimal (meter) dari titik ini yang masih dianggap valid untuk check-in/check-out.</p>
        @error('radius_meters') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="flex items-center gap-2">
        <input type="checkbox" name="is_active" value="1" id="is_active"
            @checked(old('is_active', $location->is_active ?? true))
        class="h-4 w-4 rounded border-sky-300 text-sky-600 focus:ring-sky-500">
        <label for="is_active" class="text-sm text-sky-700">Lokasi aktif</label>
    </div>
</div>