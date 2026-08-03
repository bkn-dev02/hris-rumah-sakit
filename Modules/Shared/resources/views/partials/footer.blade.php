<footer class="fixed inset-x-0 bottom-0 z-9999 border-t border-slate-200 bg-white px-4 py-4 sm:px-6">
    <div class="flex flex-col items-center gap-3 text-center sm:flex-row sm:justify-between sm:text-left">

        {{-- Copyright --}}
        <p class="text-xs text-slate-400">
            &copy; {{ now()->year }} <span class="font-medium text-slate-500">HRIS Rumah Sakit</span>. Seluruh hak cipta dilindungi.
        </p>

        {{-- Info tengah — versi sistem --}}
        <p class="flex items-center gap-1.5 text-xs text-slate-400">
            <i class="fa-solid fa-code-branch text-[11px]"></i>
            Versi 1.0.0
        </p>

        {{-- Link kanan --}}
        <div class="flex items-center gap-4 text-xs text-slate-400">
            <a href="#" class="hover:text-[#0F3D3E]">Bantuan</a>
            <span class="h-3 w-px bg-slate-200"></span>
            <a href="#" class="hover:text-[#0F3D3E]">Kebijakan Privasi</a>
            <span class="h-3 w-px bg-slate-200"></span>
            <a href="#" class="hover:text-[#0F3D3E]">Hubungi IT</a>
        </div>

    </div>
</footer>