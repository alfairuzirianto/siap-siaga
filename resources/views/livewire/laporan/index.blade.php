<div>
    <x-page-header title="Pusat Laporan" subtitle="Pantau hasil ringkasan aset peralatan siaga" />

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- CARD LAPORAN PERALATAN --}}
        <div class="card bg-white border border-slate-200 rounded-2xl shadow-sm p-6 flex flex-col justify-between">
            <div class="space-y-4">
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                    <i class="ti ti-box text-2xl"></i>
                </div>
                <div>
                    <h3 class="font-bold text-slate-800 text-base font-display">Laporan Inventaris Peralatan</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Daftar kondisi, lokasi gudang, dan total kesiapan unit fisik dinas.</p>
                </div>
                <div class="border-t border-slate-50 pt-3 grid grid-cols-2 gap-2 text-xs font-medium text-slate-500">
                    <div>Total Aset: <strong class="text-slate-800 font-bold block text-sm">{{ $ringkasan['peralatan']['total'] }} Unit</strong></div>
                    <div>Tersedia: <strong class="text-green-600 font-bold block text-sm">{{ $ringkasan['peralatan']['tersedia'] }} Unit</strong></div>
                </div>
            </div>
            <a href="{{ route('laporan.show', 'peralatan') }}" wire:navigate class="mt-6 w-full text-center px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-colors">
                Buka Laporan
            </a>
        </div>

        {{-- CARD LAPORAN PEMELIHARAAN --}}
        <div class="card bg-white border border-slate-200 rounded-2xl shadow-sm p-6 flex flex-col justify-between">
            <div class="space-y-4">
                <div class="w-12 h-12 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center">
                    <i class="ti ti-tool text-2xl"></i>
                </div>
                <div>
                    <h3 class="font-bold text-slate-800 text-base font-display">Laporan Pemeliharaan Pemeliharaan</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Histori perbaikan berkala, tracking unit rusak, dan optimalisasi fungsi.</p>
                </div>
                <div class="border-t border-slate-50 pt-3 text-xs font-medium text-slate-500">
                    <div>Total Kasus Pemeliharaan: <strong class="text-slate-800 font-bold block text-sm">{{ $ringkasan['pemeliharaan']['total'] }} Riwayat</strong></div>
                </div>
            </div>
            <a href="{{ route('laporan.show', 'pemeliharaan') }}" wire:navigate class="mt-6 w-full text-center px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-colors">
                Buka Laporan
            </a>
        </div>

        {{-- CARD LAPORAN PEMINJAMAN --}}
        <div class="card bg-white border border-slate-200 rounded-2xl shadow-sm p-6 flex flex-col justify-between">
            <div class="space-y-4">
                <div class="w-12 h-12 rounded-xl bg-yellow-50 text-yellow-600 flex items-center justify-center">
                    <i class="ti ti-file-description text-2xl"></i>
                </div>
                <div>
                    <h3 class="font-bold text-slate-800 text-base font-display">Laporan Transaksi Peminjaman</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Analisis intensitas pinjam pakai barang antar-sektor dan ketepatan pemulangan.</p>
                </div>
                <div class="border-t border-slate-50 pt-3 grid grid-cols-2 gap-2 text-xs font-medium text-slate-500">
                    <div>Aktif: <strong class="text-yellow-600 font-bold block text-sm">{{ $ringkasan['peminjaman']['aktif'] }} Transaksi</strong></div>
                    <div>Selesai: <strong class="text-green-600 font-bold block text-sm">{{ $ringkasan['peminjaman']['selesai'] }} Transaksi</strong></div>
                </div>
            </div>
            <a href="{{ route('laporan.show', 'peminjaman') }}" wire:navigate class="mt-6 w-full text-center px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-colors">
                Buka Laporan
            </a>
        </div>
    </div>
</div>