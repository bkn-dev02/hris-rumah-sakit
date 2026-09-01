<header class="fixed inset-x-0 top-0 z-50 flex h-16 w-full items-center justify-between gap-5 bg-[#edf5ee] px-4 shadow-md transition-all duration-300 text-[#1f4d3d] md:px-8">

    <!-- Left: Brand -->
    <div class="flex h-full items-center justify-center gap-4">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-10 w-10 rounded-full border border-[#6aa77d] object-cover shadow-sm">
        <span class="text-xs font-bold italic md:text-sm lg:text-lg">Rumah Sakit Umum Kasih Insani</span>
    </div>

    <!-- RIGHT: Actions -->
    <div class="flex items-center gap-2 sm:gap-3">
        <button
            type="button"
            class="flex h-10 w-10 items-center justify-center rounded-lg border border-[#cfe1d3] bg-white text-[#1f4d3d] shadow-sm transition hover:bg-[#eaf7ee] hover:text-[#143e32] lg:hidden"
            @click="mobileSidebarOpen = !mobileSidebarOpen"
            aria-label="Buka menu navigasi"
        >
            <i class="fa-solid fa-bars text-lg"></i>
        </button>

        <div class="relative hidden lg:block" x-data="{ open: false }">
            <button
                @click="open = !open"
                @click.outside="open = false"
                class="flex items-center gap-2.5 rounded-xl border border-[#dfeee1] bg-white px-3 py-2 transition-all duration-200 hover:border-[#a7d1b0] hover:bg-[#f1faf3]">
                <x-shared::avatar
                    :src="auth()->user()->employee?->photo ? asset('storage/' . auth()->user()->employee->photo) : null"
                    :name="auth()->user()->employee?->name ?? auth()->user()->username"
                    size="sm" />
                <div class="flex flex-col text-left">
                    <span class="text-sm font-medium text-[#1d3b31]">{{ auth()->user()->employee?->name ?? auth()->user()->username }}</span>
                    <span class="text-xs text-[#567564]">{{ auth()->user()->roles->first()?->name ?? 'Tanpa Role' }}</span>
                </div>
                <i class="fa-solid fa-chevron-down text-xs text-[#567564] transition-transform" :class="open && 'rotate-180'"></i>
            </button>

            <div
                x-show="open"
                x-transition
                x-cloak
                class="absolute right-0 mt-2 w-56 rounded-xl border border-[#dfeee1] bg-white py-2 shadow-lg">
                <a href="{{ route('profile.show') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-[#2a4d44] hover:bg-[#edf9f0] hover:text-[#163c32]">
                    <i class="fa-solid fa-user w-4 text-center"></i> Profil Saya
                </a>

                <div class="my-1 border-t border-[#ebf3ee]"></div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex w-full items-center gap-2 px-4 py-2 text-sm text-[#2a4d44] hover:bg-[#fff1f1] hover:text-[#8c3a42]">
                        <i class="fa-solid fa-right-from-bracket w-4 text-center"></i> Logout
                    </button>
                </form>
            </div>
        </div>

    </div>

</header>