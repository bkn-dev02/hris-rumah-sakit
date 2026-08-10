@extends('shared::layouts.app')

@section('title', 'Manajemen Cuti')

@section('content')
<div class="min-h-full bg-slate-50">
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">

        <div class="mb-8">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                <div>
                    <div class="mb-2 flex items-center gap-2">
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-sky-100">
                            <i class="fa-solid fa-calendar-days text-sky-700"></i>
                        </div>

                        <span class="text-sm font-medium text-sky-700">
                            Leave Management
                        </span>
                    </div>

                    <h1 class="text-2xl font-bold tracking-tight text-slate-800 sm:text-3xl">
                        Manajemen Cuti
                    </h1>

                    <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-500">
                        Kelola data cuti yang digunakan di seluruh sistem.
                    </p>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection