@php $isEdit = isset($user); @endphp

<div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

    {{-- Username --}}
    <div>
        <label class="mb-1 block text-sm font-medium text-[#2a684f]">
            Username
        </label>

        <input
            type="text"
            name="username"
            value="{{ old('username', $user->username ?? '') }}"
            placeholder="Masukkan username"
            autocomplete="username"
            class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm
                   focus:border-[#2a684f] focus:outline-none focus:ring-1 focus:ring-[#dfeee1]">

        @error('username')
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>


    {{-- Email --}}
    <div>
        <label class="mb-1 block text-sm font-medium text-[#2a684f]">
            Email
        </label>

        <input
            type="email"
            name="email"
            value="{{ old('email', $user->email ?? '') }}"
            placeholder="Masukkan alamat email"
            autocomplete="email"
            class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm
                   focus:border-[#2a684f] focus:outline-none focus:ring-1 focus:ring-[#dfeee1]">

        @error('email')
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>


    {{-- Password --}}
    <div>
        <label class="mb-1 block text-sm font-medium text-[#2a684f]">
            Password
            @if($isEdit)
            <span class="font-normal text-slate-400">
                (kosongkan jika tidak diubah)
            </span>
            @endif
        </label>

        <div class="relative">
            <input
                type="password"
                name="password"
                id="password"
                placeholder="{{ $isEdit ? 'Masukkan password baru' : 'Masukkan password' }}"
                autocomplete="{{ $isEdit ? 'new-password' : 'new-password' }}"
                class="w-full rounded-lg border border-slate-200 px-3 py-2 pr-10 text-sm
                       focus:border-[#2a684f] focus:outline-none focus:ring-1 focus:ring-[#dfeee1]">

            <button
                type="button"
                onclick="togglePassword('password', 'passwordIcon')"
                class="absolute inset-y-0 right-0 flex items-center px-3 text-slate-400
                       hover:text-[#2a684f]"
                aria-label="Tampilkan password">
                <i id="passwordIcon" class="fa-solid fa-eye"></i>
            </button>
        </div>

        @error('password')
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>


    {{-- Konfirmasi Password --}}
    <div>
        <label class="mb-1 block text-sm font-medium text-[#2a684f]">
            Konfirmasi Password
        </label>

        <div class="relative">
            <input
                type="password"
                name="password_confirmation"
                id="password_confirmation"
                placeholder="Ulangi password"
                autocomplete="new-password"
                class="w-full rounded-lg border border-slate-200 px-3 py-2 pr-10 text-sm
                       focus:border-[#2a684f] focus:outline-none focus:ring-1 focus:ring-[#dfeee1]">

            <button
                type="button"
                onclick="togglePassword('password_confirmation', 'passwordConfirmationIcon')"
                class="absolute inset-y-0 right-0 flex items-center px-3 text-slate-400
                       hover:text-[#2a684f]"
                aria-label="Tampilkan konfirmasi password">
                <i id="passwordConfirmationIcon" class="fa-solid fa-eye"></i>
            </button>
        </div>
    </div>


    {{-- Status --}}
    <div class="flex items-center gap-2 sm:col-span-2">
        <input
            type="checkbox"
            name="is_active"
            value="1"
            id="is_active"
            @checked(old('is_active', $user->is_active ?? true))
        class="h-4 w-4 rounded border-slate-300 text-[#2a684f] focus:ring-[#dfeee1]"
        >

        <label for="is_active" class="text-sm text-[#2a684f]">
            Akun aktif
        </label>
    </div>


    {{-- Role --}}
    <div class="sm:col-span-2">
        <label class="mb-2 block text-sm font-medium text-[#2a684f]">
            Role
        </label>

        <div class="grid grid-cols-1 gap-2 rounded-lg border border-slate-200 p-4 sm:grid-cols-2">
            @forelse($roles as $role)

            <label class="flex items-center gap-2 text-sm text-[#2a684f]">
                <input
                    type="checkbox"
                    name="roles[]"
                    value="{{ $role->id }}"
                    @checked(in_array($role->id, old('roles', $assignedRoleIds ?? [])))
                class="h-4 w-4 rounded border-slate-300 text-[#2a684f] focus:ring-[#dfeee1]"
                >

                {{ $role->name }}

                @if($role->is_system)
                <span class="text-xs text-slate-400">
                    (sistem)
                </span>
                @endif
            </label>

            @empty

            <p class="text-sm text-slate-400">
                Belum ada role tersedia.
            </p>

            @endforelse
        </div>

        @error('roles')
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

</div>


<script>
    function togglePassword(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);

        if (input.type === 'password') {
            input.type = 'text';

            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';

            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>
