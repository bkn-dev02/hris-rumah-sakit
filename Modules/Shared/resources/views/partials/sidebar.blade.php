<aside class="fixed inset-y-0 left-0 top-16 z-40 flex w-64 -translate-x-full flex-col overflow-y-auto bg-sky-950 shadow-lg transition-transform duration-200 ease-out peer-checked:translate-x-0 lg:translate-x-0">
    <nav class="flex-1 overflow-y-auto px-3 py-4 lg:pt-6" x-data="{ openMenu: '{{ $openMenu }}' }">
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

                <div class="flex items-center overflow-hidden rounded-lg border-l-4 transition-all duration-200 {{ $isActive ? 'border-white bg-white text-slate-700 shadow-sm' : 'border-transparent text-slate-100 hover:border-slate-300 hover:bg-slate-500/70' }}">
                    <a href="{{ Route::has($menu['route']) ? route($menu['route']) : '#' }}" class="group flex flex-1 items-center gap-3 px-3 py-2.5 text-sm font-medium">
                        <i class="{{ $menu['icon'] }} w-5 text-center {{ $isActive ? 'text-slate-700' : 'text-slate-200 group-hover:text-white' }}"> </i>
                        <span>
                            {{ $menu['label'] }}
                        </span>
                    </a>

                    <button
                        type="button"
                        @click="openMenu = openMenu === '{{ $menu['label'] }}' ? '' : '{{ $menu['label'] }}'"
                        class="flex items-center justify-center px-3 py-2.5 text-slate-300 transition-colors hover:text-white">
                        <i class="fa-solid fa-chevron-down text-xs transition-transform duration-200" :class="openMenu === '{{ $menu['label'] }}' && 'rotate-180'"></i>
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
                    class="mt-1 space-y-1 pl-5">

                    @foreach($menu['children'] as $child)

                    @php
                    $childActive = request()->routeIs(...$child['active']);
                    @endphp

                    <li>
                        <a href="{{ Route::has($child['route']) ? route($child['route']) : '#' }}" class="group flex items-center gap-3 rounded-lg border-l-2 px-3 py-2 text-sm transition-all duration-200 {{ $childActive ? 'border-white bg-slate-100 font-medium text-slate-700 shadow-sm' : 'border-transparent text-slate-200 hover:border-slate-300 hover:bg-slate-500/70 hover:text-white' }}">
                            <i class="{{ $child['icon'] }} w-4 text-center text-xs {{ $childActive ? 'text-slate-700' : 'text-slate-300 group-hover:text-white' }}"></i>
                            <span> {{ $child['label'] }} </span>
                        </a>
                    </li>

                    @endforeach
                </ul>

                @else

                <a href="{{ Route::has($menu['route']) ? route($menu['route']) : '#' }}" class="group flex items-center gap-3 rounded-lg border-l-4 px-3 py-2.5 text-sm font-medium transition-all duration-200 {{ $isActive ? 'border-white bg-white text-slate-700 shadow-sm' : 'border-transparent text-slate-100 hover:border-slate-300 hover:bg-slate-500/70 hover:text-white' }}">
                    <i class="{{ $menu['icon'] }} w-5 text-center {{ $isActive ? 'text-slate-700' : 'text-slate-200 group-hover:text-white' }} }}"> </i>
                    <span>{{ $menu['label'] }}</span>
                </a>

                @endif
            </li>

            @endforeach

        </ul>
    </nav>
</aside>