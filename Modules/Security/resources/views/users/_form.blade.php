@php $isEdit = isset($user); @endphp

<div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">Username</label>
        <input type="text" name="username" value="{{ old('username', $user->username ?? '') }}"
            class="w-full rounded-lg border border-slate-200 py-2 px-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
        @error('username') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">Email</label>
        <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}"
            class="w-full rounded-lg border border-slate-200 py-2 px-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
        @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">
            Password {{ $isEdit ? '(kosongkan jika tidak diubah)' : '' }}
        </label>
        <input type="password" name="password"
            class="w-full rounded-lg border border-slate-200 py-2 px-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
        @error('password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">Konfirmasi Password</label>
        <input type="password" name="password_confirmation"
            class="w-full rounded-lg border border-slate-200 py-2 px-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
    </div>

    <div class="flex items-center gap-2 sm:col-span-2">
        <input type="checkbox" name="is_active" value="1" id="is_active"
            @checked(old('is_active', $user->is_active ?? true))
        class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
        <label for="is_active" class="text-sm text-slate-700">Akun aktif</label>
    </div>
    <div class="sm:col-span-2">
        <label class="mb-2 block text-sm font-medium text-slate-700">Role</label>

        <div class="grid grid-cols-1 gap-2 rounded-lg border border-slate-200 p-4 sm:grid-cols-2">
            @forelse($roles as $role)
            <label class="flex items-center gap-2 text-sm text-slate-700">
                <input
                    type="checkbox"
                    name="roles[]"
                    value="{{ $role->id }}"
                    @checked(in_array($role->id, old('roles', $assignedRoleIds ?? [])))
                class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                >
                {{ $role->name }}
                @if($role->is_system)
                <span class="text-xs text-slate-400">(sistem)</span>
                @endif
            </label>
            @empty
            <p class="text-sm text-slate-400">Belum ada role tersedia.</p>
            @endforelse
        </div>
        @error('roles') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

</div>