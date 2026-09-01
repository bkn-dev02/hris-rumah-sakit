@extends('shared::layouts.app')

@section('title', 'Master Data')

@section('content')
<div class="min-h-full bg-[#edf5ee]">
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="mb-8">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                <div>
                    <div class="mb-2 flex items-center gap-2">
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-[#dfeee1]">
                            <i class="fa-solid fa-database text-[#1f4d3d]"></i>
                        </div>

                        <span class="text-sm font-medium text-[#2a684f]">
                            Data Management
                        </span>
                    </div>

                    <h1 class="text-2xl font-bold tracking-tight text-[#1f4d3d] sm:text-3xl">
                        Master Data
                    </h1>

                    <p class="mt-2 max-w-2xl text-sm leading-6 text-[#4a665c]">
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
                class="group relative overflow-hidden rounded-2xl border border-[#dfeee1] bg-white p-6 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-[#a7d1b0] hover:shadow-xl hover:shadow-[#dfeee1]/70">

                {{-- Decorative Background --}}
                <div class="absolute -right-8 -top-8 h-24 w-24 rounded-full bg-[#edf5ee] transition-transform duration-500 group-hover:scale-150">
                </div>

                <div class="relative">

                    {{-- Top Section --}}
                    <div class="flex items-start justify-between">

                        {{-- Icon --}}
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#edf5ee] ring-1 ring-[#dfeee1] transition-all duration-300 group-hover:bg-[#1f4d3d] group-hover:shadow-lg group-hover:shadow-[#1f4d3d]/20">
                            <i class="{{ $card['icon'] }} text-lg text-[#1f4d3d] transition-colors duration-300 group-hover:text-white"></i>
                        </div>

                        {{-- Arrow --}}
                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-[#f4faf5] text-[#6a857d] transition-all duration-300 group-hover:bg-[#eaf5ee] group-hover:text-[#1f4d3d]">
                            <i class="fa-solid fa-arrow-up-right text-xs"></i>
                        </div>

                    </div>


                    {{-- Content --}}
                    <div class="mt-6">

                        <p class="text-3xl font-bold tracking-tight text-[#1f4d3d]">
                            {{ $card['count'] }}
                        </p>

                        <p class="mt-1 text-sm font-medium text-[#4a665c] transition-colors group-hover:text-[#2a684f]">
                            {{ $card['label'] }}
                        </p>

                    </div>


                    {{-- Bottom Indicator --}}
                    <div class="mt-6 flex items-center gap-2 text-xs font-medium text-[#6a857d] transition-colors group-hover:text-[#1f4d3d]">
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