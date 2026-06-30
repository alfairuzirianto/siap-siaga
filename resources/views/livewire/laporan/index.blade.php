<div class="space-y-6">
    <x-page-header 
        title="Pusat Analisis & Laporan" 
        subtitle="Pantau ringkasan aset, manajemen sirkulasi, dan log tindakan taktis"
        :breadcrumbs="[['label' => 'Laporan']]" />

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- CARD LAPORAN PERALATAN --}}
        <div class="card bg-white border border-slate-200/80 rounded-2xl shadow-sm p-6 flex flex-col justify-between hover:border-blue-300 hover:shadow-md transition-all duration-200">
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl">
                        <i class="ti ti-box"></i>
                    </div>
                    <span class="text-[10px] font-bold text-blue-600 bg-blue-50 px-2.5 py-1 rounded-full uppercase tracking-wider">Inventaris</span>
                </div>
                
                <div>
                    <h3 class="font-bold text-slate-800 text-base font-display">Laporan Inventaris Peralatan</h3>
                    <p class="text-xs text-slate-400 mt-1 leading-relaxed">Daftar kondisi riil, kapasitas ukur, lokasi penyimpanan gudang, dan total kesiapan unit fisik dinas.</p>
                </div>

                {{-- Progress Bar Visual Kesiapan Alat --}}
                @php
                    $totalAlat = $ringkasan['peralatan']['total'];
                    $readyAlat = $ringkasan['peralatan']['tersedia'];
                    $persenReady = $totalAlat > 0 ? round(($readyAlat / $totalAlat) * 100) : 0;
                @endphp
                <div class="space-y-1.5 pt-1">
                    <div class="flex justify-between text-xs font-semibold">
                        <span class="text-slate-400">Rasio Kesiapan Alat</span>
                        <span class="text-green-600">{{ $persenReady }}% Siap</span>
                    </div>
                    <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                        <div class="bg-green-500 h-full rounded-full transition-all duration-500" style="width: {{ $persenReady }}%"></div>
                    </div>
                </div>

                <div class="border-t border-slate-100 pt-4 grid grid-cols-2 gap-4 text-xs font-medium text-slate-400">
                    <div>Total Aset <strong class="text-slate-800 font-bold block text-sm mt-0.5">{{ $totalAlat }} Unit</strong></div>
                    <div>Tersedia <strong class="text-green-600 font-bold block text-sm mt-0.5">{{ $readyAlat }} Unit</strong></div>
                </div>
            </div>
            
            <a href="{{ route('laporan.show', 'peralatan') }}" wire:navigate class="mt-6 w-full text-center px-4 py-2.5 bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-bold rounded-xl transition-all flex items-center justify-center gap-2">
                <span>Buka Rincian Analisis</span>
                <i class="ti ti-arrow-right text-sm"></i>
            </a>
        </div>

        {{-- CARD LAPORAN PEMELIHARAAN --}}
        <div class="card bg-white border border-slate-200/80 rounded-2xl shadow-sm p-6 flex flex-col justify-between hover:border-orange-300 hover:shadow-md transition-all duration-200">
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div class="w-12 h-12 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center text-xl">
                        <i class="ti ti-tool"></i>
                    </div>
                    <span class="text-[10px] font-bold text-orange-600 bg-orange-50 px-2.5 py-1 rounded-full uppercase tracking-wider">Maintenance</span>
                </div>
                
                <div>
                    <h3 class="font-bold text-slate-800 text-base font-display">Laporan Log Pemeliharaan</h3>
                    <p class="text-xs text-slate-400 mt-1 leading-relaxed">Histori tindakan perbaikan berkala, pelacakan unit rusak, penanganan kendala teknis, dan optimalisasi fungsi.</p>
                </div>

                @php
                    $preventif = $ringkasan['pemeliharaan']['preventif'] ?? 0;
                    $korektif = $ringkasan['pemeliharaan']['korektif'] ?? 0;
                    $totalMaint = $preventif + $korektif;
                    
                    $persenPreventif = $totalMaint > 0 ? round(($preventif / $totalMaint) * 100) : 0;
                    $persenKorektif = $totalMaint > 0 ? (100 - $persenPreventif) : 0;
                @endphp
                
                <div class="space-y-1.5 pt-1">
                    <div class="flex justify-between text-xs font-semibold">
                        <span class="text-slate-500">Perbandingan Tindakan</span>
                        <span class="text-slate-600 font-mono">{{ $persenPreventif }}% : {{ $persenKorektif }}%</span>
                    </div>
                    
                    <div class="w-full bg-slate-100 h-2.5 rounded-full overflow-hidden flex">
                        @if($totalMaint > 0)
                            <div class="bg-cyan-500 h-full transition-all duration-500" style="width: {{ $persenPreventif }}%" title="Preventif: {{ $persenPreventif }}%"></div>
                            <div class="bg-rose-500 h-full transition-all duration-500" style="width: {{ $persenKorektif }}%" title="Korektif: {{ $persenKorektif }}%"></div>
                        @else
                            <div class="bg-slate-200 w-full h-full"></div>
                        @endif
                    </div>
                    
                    <div class="flex justify-between text-[10px] font-medium text-slate-400">
                        <span class="flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-cyan-500"></span> Preventif</span>
                        <span class="flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Korektif</span>
                    </div>
                </div>

                <div class="border-t border-slate-100 pt-4 grid grid-cols-2 gap-4 text-xs font-medium text-slate-400">
                    <div>Preventif <strong class="text-cyan-700 font-bold block text-sm mt-0.5">{{ $preventif }} Log</strong></div>
                    <div>Korektif <strong class="text-rose-700 font-bold block text-sm mt-0.5">{{ $korektif }} Log</strong></div>
                </div>
            </div>
            
            <a href="{{ route('laporan.show', 'pemeliharaan') }}" wire:navigate class="mt-6 w-full text-center px-4 py-2.5 bg-orange-50 hover:bg-orange-100 text-orange-700 text-xs font-bold rounded-xl transition-all flex items-center justify-center gap-2">
                <span>Buka Rincian Analisis</span>
                <i class="ti ti-arrow-right text-sm"></i>
            </a>
        </div>

        {{-- CARD LAPORAN PEMINJAMAN --}}
        <div class="card bg-white border border-slate-200/80 rounded-2xl shadow-sm p-6 flex flex-col justify-between hover:border-yellow-400 hover:shadow-md transition-all duration-200">
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div class="w-12 h-12 rounded-xl bg-yellow-50 text-yellow-600 flex items-center justify-center text-xl">
                        <i class="ti ti-file-description"></i>
                    </div>
                    <span class="text-[10px] font-bold text-yellow-600 bg-yellow-50 px-2.5 py-1 rounded-full uppercase tracking-wider">Sirkulasi</span>
                </div>
                
                <div>
                    <h3 class="font-bold text-slate-800 text-base font-display">Laporan Sirkulasi Peminjaman</h3>
                    <p class="text-xs text-slate-400 mt-1 leading-relaxed">Analisis intensitas penugasan pinjam pakai barang antar-sektor, ketepatan pemulangan alat, dan audit berkas.</p>
                </div>

                {{-- Analitik Tambahan Peminjaman --}}
                @php
                    $totalPinjam = $ringkasan['peminjaman']['total'];
                    $aktifPinjam = $ringkasan['peminjaman']['aktif'];
                    $rateSirkulasi = $totalPinjam > 0 ? round(($aktifPinjam / $totalPinjam) * 100) : 0;
                @endphp
                <div class="space-y-1.5 pt-1">
                    <div class="flex justify-between text-xs font-semibold">
                        <span class="text-slate-400">Rasio Sirkulasi Aktif</span>
                        <span class="text-amber-600">{{ $rateSirkulasi }}% Berjalan</span>
                    </div>
                    <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                        <div class="bg-amber-500 h-full rounded-full transition-all duration-500" style="width: {{ $rateSirkulasi }}%"></div>
                    </div>
                </div>

                <div class="border-t border-slate-100 pt-4 grid grid-cols-2 gap-4 text-xs font-medium text-slate-400">
                    <div>Sirkulasi Aktif <strong class="text-amber-600 font-bold block text-sm mt-0.5">{{ $aktifPinjam }} Berkas</strong></div>
                    <div>Arsip Selesai <strong class="text-green-600 font-bold block text-sm mt-0.5">{{ $ringkasan['peminjaman']['selesai'] }} Berkas</strong></div>
                </div>
            </div>
            
            <a href="{{ route('laporan.show', 'peminjaman') }}" wire:navigate class="mt-6 w-full text-center px-4 py-2.5 bg-yellow-50 hover:bg-yellow-100 text-yellow-700 text-xs font-bold rounded-xl transition-all flex items-center justify-center gap-2">
                <span>Buka Rincian Analisis</span>
                <i class="ti ti-arrow-right text-sm"></i>
            </a>
        </div>
    </div>
</div>