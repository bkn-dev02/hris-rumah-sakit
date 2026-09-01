<aside
    x-data="{ openMenu: '{{ $openMenu }}' }"
    x-show="window.innerWidth >= 1024 || mobileSidebarOpen"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0 -translate-x-4"
    x-transition:enter-end="opacity-100 translate-x-0"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100 translate-x-0"
    x-transition:leave-end="opacity-0 -translate-x-4"
    @click.outside="if (window.innerWidth < 1024) mobileSidebarOpen = false"
    class="fixed inset-y-0 left-0 top-16 z-40 flex w-[85vw] max-w-xs flex-col overflow-hidden bg-[#1f4d3d] shadow-2xl transition-transform duration-200 ease-out lg:w-64 lg:translate-x-0 lg:shadow-lg"
    :class="window.innerWidth < 1024 ? (mobileSidebarOpen ? 'translate-x-0' : '-translate-x-full') : ''">
    <div class="border-b border-[#2d5d4d] bg-[#173f34] px-4 py-4 lg:hidden">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <x-shared::avatar
                    :src="auth()->user()->employee?->photo ? asset('storage/' . auth()->user()->employee->photo) : null"
                    :name="auth()->user()->employee?->name ?? auth()->user()->username"
                    size="sm" />
                <div class="min-w-0">
                    <p class="truncate text-sm font-semibold text-white">{{ auth()->user()->employee?->name ?? auth()->user()->username }}</p>
                    <p class="truncate text-[11px] text-[#dfeee1]">{{ auth()->user()->roles->first()?->name ?? 'Tanpa Role' }}</p>
                </div>
            </div>

            <button type="button" @click="mobileSidebarOpen = false" class="flex h-8 w-8 items-center justify-center rounded-full text-[#edf5ee] transition hover:bg-white/10 hover:text-white" aria-label="Tutup menu">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    </div>

    @php
        $primaryRoleName = auth()->user()->roles->first()?->name ?? 'User';
        $normalizedRoleName = strtolower(trim($primaryRoleName));

        if (str_contains($normalizedRoleName, 'admin')) {
            $rolePanelLabel = 'Panel Admin';
        } elseif (str_contains($normalizedRoleName, 'pegawai') || str_contains($normalizedRoleName, 'staff') || str_contains($normalizedRoleName, 'employee')) {
            $rolePanelLabel = 'Panel Pegawai';
        } else {
            $rolePanelLabel = 'Panel ' . ucfirst($primaryRoleName);
        }
    @endphp

    <div class="hidden border-b border-[#2d5d4d] bg-[#173f34] px-3 py-3 lg:block">
        <div class="rounded-xl border border-[#dfeee1]/20 bg-white/5 px-3 py-3 text-center">
            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-[#dfeee1] text-[#173f34] shadow-sm ring-2 ring-white/10">
                @php
                    $roleIcon = str_contains(strtolower($primaryRoleName), 'admin') ? 'fa-user-shield' : 'fa-user';
                @endphp
                <i class="fa-solid {{ $roleIcon }} text-lg"></i>
            </div>
            <p class="mt-2 text-base font-bold text-white md:text-lg">{{ $rolePanelLabel }}</p>
        </div>
    </div>

    <nav class="flex-1 overflow-y-auto px-3 py-4 pb-40 lg:pt-4 lg:pb-4">
        <ul class="space-y-1">
            @foreach($menus as $menu)
            @php
            $hasChildren = isset($menu['children']);
            $childPatterns = $hasChildren ? collect($menu['children'])
            ->flatMap(fn ($c) => $c['active'])
            ->all()
            : [];
            $isActive = request()->routeIs(...$menu['active'], ...$childPatterns);
            @endphp

            <li>
                @if($hasChildren)

                <div class="flex items-center overflow-hidden rounded-xl border-l-4 transition-all duration-200 {{ $isActive ? 'border-[#dfeee1] bg-[#edf5ee] text-[#1f4d3d] shadow-sm ring-1 ring-white/10' : 'border-transparent text-[#edf5ee] hover:border-[#dfeee1] hover:bg-[#2a684f]/80' }}">
                    <a href="{{ Route::has($menu['route']) ? route($menu['route']) : '#' }}" class="group flex flex-1 items-center gap-3 px-3 py-2.5 text-sm font-medium">
                        <i class="{{ $menu['icon'] }} w-5 text-center {{ $isActive ? 'text-[#1f4d3d]' : 'text-[#dfeee1] group-hover:text-white' }}"> </i>
                        <span>
                            {{ $menu['label'] }}
                        </span>
                    </a>

                    <button
                        type="button"
                        @click="openMenu = openMenu === '{{ $menu['label'] }}' ? '' : '{{ $menu['label'] }}'"
                        class="flex items-center justify-center px-3 py-2.5 text-[#dfeee1] transition-colors hover:text-white">
                        <i class="fa-solid fa-chevron-down text-[10px] transition-transform duration-200" :class="openMenu === '{{ $menu['label'] }}' && 'rotate-180'"></i>
                    </button>
                </div>
                <ul
                    x-show="openMenu === '{{ $menu['label'] }}'"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 -translate-y-1"
                    class="mt-2 space-y-1.5 pl-4 pr-1">

                    @foreach($menu['children'] as $child)

                    @php
                    $childActive = request()->routeIs(...$child['active']);
                    $showEmergencyBadge = $child['route'] === 'attendance.emergency.index'
                    && app(\Modules\Attendance\Contracts\Services\CheckInServiceInterface::class)->hasUnseenEmergency();
                    $showLeaveBadge = $child['route'] === 'leave.requests.index'
                    && app(\Modules\Leave\Contracts\Services\LeaveRequestServiceInterface::class)->hasUnseenPending();
                    @endphp

                    <li>
                        <a href="{{ Route::has($child['route']) ? route($child['route']) : '#' }}" class="group flex items-center gap-3 rounded-lg border-l-2 px-3 py-2 text-sm transition-all duration-200 {{ $childActive ? 'border-[#2a684f] bg-[#edf5ee] font-medium text-[#1f4d3d] shadow-sm ring-1 ring-[#dfeee1]/60' : 'border-transparent text-[#dfeee1] hover:border-[#dfeee1] hover:bg-[#2a684f]/70 hover:text-white' }}">
                            <i class="{{ $child['icon'] }} w-4 text-center text-xs {{ $childActive ? 'text-[#1f4d3d]' : 'text-[#dfeee1] group-hover:text-white' }}"></i>
                            <span class="flex flex-1 items-center gap-2">
                                {{ $child['label'] }}
                                @if ($showEmergencyBadge || $showLeaveBadge)
                                <span class="h-2 w-2 rounded-full bg-rose-500"></span>
                                @endif
                            </span>
                        </a>
                    </li>

                    @endforeach
                </ul>

                @else

                <a href="{{ Route::has($menu['route']) ? route($menu['route']) : '#' }}" class="group flex items-center gap-3 rounded-lg border-l-4 px-3 py-2.5 text-sm font-medium transition-all duration-200 {{ $isActive ? 'border-[#dfeee1] bg-[#edf5ee] text-[#1f4d3d] shadow-sm' : 'border-transparent text-[#edf5ee] hover:border-[#dfeee1] hover:bg-[#2a684f]/80 hover:text-white' }}">
                    <i class="{{ $menu['icon'] }} w-5 text-center {{ $isActive ? 'text-[#1f4d3d]' : 'text-[#dfeee1] group-hover:text-white' }}"> </i>
                    <span>{{ $menu['label'] }}</span>
                </a>

                @endif
            </li>

            @endforeach

        </ul>
    </nav>

    <div class="fixed inset-x-0 bottom-0 z-50 border-t border-[#2d5d4d] bg-[#173f34] px-4 py-3 shadow-[0_-8px_25px_rgba(12,36,29,0.18)] lg:hidden">
        <div class="space-y-2">
            <a href="{{ route('profile.show') }}" class="flex items-center justify-center gap-2 rounded-lg bg-white/10 px-3 py-2.5 text-sm font-medium text-[#edf5ee] transition hover:bg-white/15">
                <i class="fa-solid fa-user text-sm"></i>
                Profil Saya
            </a>

            <form method="POST" action="{{ route('logout') }}" class="m-0">
                @csrf
                <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-lg bg-[#dfeee1] px-3 py-2.5 text-sm font-medium text-[#1f4d3d] transition hover:bg-white">
                    <i class="fa-solid fa-right-from-bracket text-sm"></i>
                    Logout
                </button>
            </form>
        </div>
    </div>
</aside>