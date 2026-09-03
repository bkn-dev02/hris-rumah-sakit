<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} — RSU KASIH INSANI</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-gradient-to-br from-[#042A22] via-[#0F5C48] to-[#1B7A5C] flex items-center justify-center p-4">

    <div class="w-full max-w-md rounded-2xl bg-white shadow-2xl overflow-hidden">

        <div class="bg-gradient-to-br from-[#042A22] via-[#0F5C48] to-[#1B7A5C] px-6 py-8 text-center">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-white/10 ring-1 ring-white/20">
                <span class="text-3xl" role="img" aria-label="Sedih">😔</span>
            </div>
            <h1 class="mt-4 text-lg font-bold text-white">{{ $title }}</h1>
        </div>

        <div class="px-6 py-6 text-center">
            <p class="text-sm leading-relaxed text-slate-600">
                {{ $message }}
            </p>

            <div class="mt-5 rounded-xl bg-[#A9C23F]/10 p-4 text-left">
                <p class="text-xs font-semibold uppercase tracking-wide text-[#6B8E2F]">Apa yang perlu dilakukan?</p>
                <p class="mt-1.5 text-sm text-slate-600">
                    Hubungi <strong>Admin</strong> atau <strong>HRD</strong> untuk menautkan akun Anda dengan data karyawan di sistem.
                </p>
            </div>

            <div class="mt-6 flex flex-col gap-2 sm:flex-row">
                <a href="{{ url()->previous() !== url()->current() ? url()->previous() : '/' }}"
                    class="flex-1 rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
                    Kembali
                </a>
                <form method="POST" action="{{ route('logout') }}" class="flex-1">
                    @csrf
                    <button type="submit"
                        class="w-full rounded-xl bg-gradient-to-br from-[#0F5C48] to-[#1B7A5C] px-4 py-2.5 text-sm font-semibold text-white transition hover:opacity-90">
                        Logout
                    </button>
                </form>
            </div>
        </div>

    </div>

</body>

</html>