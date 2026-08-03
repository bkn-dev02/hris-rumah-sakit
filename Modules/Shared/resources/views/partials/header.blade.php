<header class="fixed inset-x-0 top-0 bg-slate-50 z-50 w-full h-16 gap-5 flex items-center justify-between transition-all duration-300 shadow-md text-blue-700">

    <!-- Left: Brand -->
    <div class="flex-1 h-full flex justify-center items-center gap-4">
        <img src="#" alt="Logo" class="h-10 w-10 rounded-full object-cover">
        <span class="font-bold text-lg italic">Rumah Sakit</span>
    </div>
    <!-- Middle: Search -->
    <div class="flex-3 h-full flex justify-center items-center">
        <input type="text" placeholder="Cari..." class="w-1/2 py-2.5 pl-10 pr-12 text-sm text-gray-700 bg-white/90 backdrop-blur-sm border-2 border-blue-200 rounded-full shadow-lg focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-300 focus:ring-opacity-50 transition-all duration-300 placeholder-gray-400 hover:shadow-blue-100/50">
    </div>

    <!-- RIGHT: Actions -->
    <div class="flex-1 flex items-center gap-2 sm:gap-3">

        <!-- Notifikasi -->
        <button class="relative p-2.5 text-slate-500 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all duration-200 hover-lift">
            <i class="fas fa-bell text-lg"></i>
            <span class="notification-dot absolute top-1 right-1.5"></span>
        </button>

        <!-- Profil -->
        <div class="relative" x-data="{ open: false }">
            <button
                @click="open = !open"
                @click.outside="open = false"
                class="flex items-center gap-2.5 bg-slate-50 hover:bg-blue-50 rounded-xl px-3 py-2 transition-all duration-200 hover-lift border border-slate-100 hover:border-blue-200">
                <x-shared::avatar
                    :src="auth()->user()->employee?->photo ? asset('storage/' . auth()->user()->employee->photo) : null"
                    :name="auth()->user()->employee?->name ?? auth()->user()->username"
                    size="sm" />
                <div class="flex flex-col text-left">
                    <span class="text-sm font-medium text-slate-800">{{ auth()->user()->employee?->name ?? auth()->user()->username }}</span>
                    <span class="text-xs text-slate-400">{{ auth()->user()->roles->first()?->name ?? 'Tanpa Role' }}</span>
                </div>
                <i class="fa-solid fa-chevron-down text-xs text-slate-400 transition-transform" :class="open && 'rotate-180'"></i>
            </button>

            <div
                x-show="open"
                x-transition
                x-cloak
                class="absolute right-0 mt-2 w-56 rounded-xl border border-slate-100 bg-white py-2 shadow-lg">
                <a href="{{ route('profile.show') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-slate-600 hover:bg-blue-50 hover:text-blue-600">
                    <i class="fa-solid fa-user w-4 text-center"></i> Profil Saya
                </a>

                <div class="my-1 border-t border-slate-100"></div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex w-full items-center gap-2 px-4 py-2 text-sm text-slate-600 hover:bg-red-50 hover:text-red-600">
                        <i class="fa-solid fa-right-from-bracket w-4 text-center"></i> Logout
                    </button>
                </form>
            </div>
        </div>

    </div>

</header>