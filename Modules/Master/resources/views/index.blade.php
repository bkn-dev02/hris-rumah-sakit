@extends('shared::layouts.app')

@section('title', 'Master Data')

@section('content')
<div class="min-h-full bg-slate-50">
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="mb-8">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                <div>
                    <div class="mb-2 flex items-center gap-2">
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-sky-100">
                            <i class="fa-solid fa-database text-sky-700"></i>
                        </div>

                        <span class="text-sm font-medium text-sky-700">
                            Data Management
                        </span>
                    </div>

                    <h1 class="text-2xl font-bold tracking-tight text-slate-800 sm:text-3xl">
                        Master Data
                    </h1>

                    <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-500">
                        Kelola data acuan yang digunakan di seluruh sistem.
                    </p>
                </div>

            </div>
        </div>


        {{-- Master Data Cards --}}
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-3">

            @foreach($cards as $card)

            @if(auth()->user()?->hasPermission($card['permission']))

            <a
                href="{{ route($card['route']) }}"
                class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-sky-200 hover:shadow-xl hover:shadow-sky-100/50">

                {{-- Decorative Background --}}
                <div class="absolute -right-8 -top-8 h-24 w-24 rounded-full bg-sky-50 transition-transform duration-500 group-hover:scale-150">
                </div>

                <div class="relative">

                    {{-- Top Section --}}
                    <div class="flex items-start justify-between">

                        {{-- Icon --}}
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-sky-50 ring-1 ring-sky-100 transition-all duration-300 group-hover:bg-sky-700 group-hover:shadow-lg group-hover:shadow-sky-700/20">
                            <i class="{{ $card['icon'] }} text-lg text-sky-700 transition-colors duration-300 group-hover:text-white"></i>
                        </div>

                        {{-- Arrow --}}
                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-50 text-slate-400 transition-all duration-300 group-hover:bg-sky-50 group-hover:text-sky-700">
                            <i class="fa-solid fa-arrow-up-right text-xs"></i>
                        </div>

                    </div>


                    {{-- Content --}}
                    <div class="mt-6">

                        <p class="text-3xl font-bold tracking-tight text-slate-800">
                            {{ $card['count'] }}
                        </p>

                        <p class="mt-1 text-sm font-medium text-slate-500 transition-colors group-hover:text-sky-700">
                            {{ $card['label'] }}
                        </p>

                    </div>


                    {{-- Bottom Indicator --}}
                    <div class="mt-6 flex items-center gap-2 text-xs font-medium text-slate-400 transition-colors group-hover:text-sky-700">
                        <span>Kelola data</span>

                        <i class="fa-solid fa-arrow-right text-[10px] transition-transform duration-300 group-hover:translate-x-1"></i>
                    </div>

                </div>

            </a>

            @endif

            @endforeach

        </div>

    </div>
</div>
@endsection