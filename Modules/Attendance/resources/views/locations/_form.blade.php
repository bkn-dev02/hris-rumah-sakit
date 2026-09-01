<div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
    <div class="sm:col-span-2">
        <label class="mb-1 block text-sm font-medium text-[#2a684f]">Nama Lokasi</label>
        <input type="text" name="name" value="{{ old('name', $location->name ?? '') }}"
            class="w-full rounded-lg border border-[#dfeee1] py-2 px-3 text-sm focus:border-[#2a684f] focus:outline-none focus:ring-1 focus:ring-[#dfeee1]">
        @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-[#2a684f]">Latitude</label>
        <input type="text" name="latitude" value="{{ old('latitude', $location->latitude ?? '') }}" placeholder="-6.2000000"
            class="w-full rounded-lg border border-[#dfeee1] py-2 px-3 text-sm focus:border-[#2a684f] focus:outline-none focus:ring-1 focus:ring-[#dfeee1]">
        @error('latitude') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-[#2a684f]">Longitude</label>
        <input type="text" name="longitude" value="{{ old('longitude', $location->longitude ?? '') }}" placeholder="106.8166667"
            class="w-full rounded-lg border border-[#dfeee1] py-2 px-3 text-sm focus:border-[#2a684f] focus:outline-none focus:ring-1 focus:ring-[#dfeee1]">
        @error('longitude') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-[#2a684f]">Radius (meter)</label>
        <input type="number" name="radius_meters" min="10" value="{{ old('radius_meters', $location->radius_meters ?? 100) }}"
            class="w-full rounded-lg border border-[#dfeee1] py-2 px-3 text-sm focus:border-[#2a684f] focus:outline-none focus:ring-1 focus:ring-[#dfeee1]">
        <p class="mt-1 text-xs text-[#2a684f]">Jarak maksimal (meter) dari titik ini yang masih dianggap valid untuk check-in/check-out.</p>
        @error('radius_meters') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="flex items-center gap-2">
        <input type="checkbox" name="is_active" value="1" id="is_active"
            @checked(old('is_active', $location->is_active ?? true))
        class="h-4 w-4 rounded border-[#dfeee1] text-[#2a684f] focus:ring-[#dfeee1]">
        <label for="is_active" class="text-sm text-[#2a684f]">Lokasi aktif</label>
    </div>
</div>
