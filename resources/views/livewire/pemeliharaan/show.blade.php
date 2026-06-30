<div>
    <x-page-header 
        title="Detail Catatan Pemeliharaan" 
        :breadcrumbs="[
            ['label' => 'Maintenance', 'url' => route('pemeliharaan.index')],
            ['label' => $pemeliharaan->nomor_pemeliharaan]]
        ">
        <a href="{{ route('pemeliharaan.edit', $pemeliharaan) }}" wire:navigate 
           class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 shadow-sm rounded-xl transition-all active:scale-[0.98]">
            <i class="ti ti-edit text-base"></i>
            <span>Edit</span>
        </a>
    </x-page-header>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
        
        <div class="lg:col-span-2 space-y-6">
            <div class="card bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Informasi Pemeliharaan</span>
                    <span class="inline-flex items-center rounded-md px-2.5 py-0.5 text-xs font-semibold ring-1 ring-inset {{ $pemeliharaan->jenis_pemeliharaan === 'Korektif' ? 'bg-rose-50 text-rose-700 ring-rose-600/10' : 'bg-cyan-50 text-cyan-700 ring-cyan-600/10' }}">
                        {{ $pemeliharaan->jenis_pemeliharaan }}
                    </span>
                </div>

                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-xs text-slate-400 block font-medium">Nomor Maintenance</span>
                            <span class="text-base font-bold text-slate-800">{{ $pemeliharaan->nomor_pemeliharaan }}</span>
                        </div>
                        <div>
                            <span class="text-xs text-slate-400 block font-medium">Tanggal Pelaksanaan</span>
                            <span class="text-base font-semibold text-slate-800">{{ $pemeliharaan->tanggal_pemeliharaan?->format('d F Y') }}</span>
                        </div>
                        <div class="pt-2 border-t border-slate-50">
                            <span class="text-xs text-slate-400 block font-medium">Petugas Pelaksana</span>
                            <span class="text-sm font-semibold text-slate-700">{{ $pemeliharaan->nama_petugas }}</span>
                        </div>
                        <div class="pt-2 border-t border-slate-50">
                            <span class="text-xs text-slate-400 block font-medium">Biaya Penanganan</span>
                            <span class="text-sm font-bold text-amber-600">Rp {{ number_format($pemeliharaan->biaya, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100 space-y-2">
                        <span class="text-xs text-slate-400 block font-medium">Deskripsi Kerja</span>
                        <div class="p-4 bg-slate-50 rounded-xl text-sm text-slate-600 leading-relaxed">
                            {{ $pemeliharaan->deskripsi ?: 'Tidak ada deskripsi tambahan yang dicantumkan.' }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="card bg-white rounded-xl border border-slate-200/80 p-4 flex items-center gap-3 shadow-sm">
                    <div class="w-9 h-9 rounded-lg bg-slate-50 text-slate-500 flex items-center justify-center shrink-0"><i class="ti ti-file-plus text-lg"></i></div>
                    <div class="min-w-0">
                        <span class="text-[10px] text-slate-400 block uppercase tracking-wider font-semibold">Dibuat Oleh</span>
                        <span class="text-xs font-bold text-slate-700 truncate block">{{ $pemeliharaan->creator?->nama_lengkap ?? '—' }}</span>
                        <span class="text-[10px] text-slate-400 block">{{ $pemeliharaan->created_at ? formatTanggal($pemeliharaan->created_at, 'd M Y, H:i') . " WIB" : '—' }}</span>
                    </div>
                </div>
                <div class="card bg-white rounded-xl border border-slate-200/80 p-4 flex items-center gap-3 shadow-sm">
                    <div class="w-9 h-9 rounded-lg bg-slate-50 text-slate-500 flex items-center justify-center shrink-0"><i class="ti ti-edit text-lg"></i></div>
                    <div class="min-w-0">
                        <span class="text-[10px] text-slate-400 block uppercase tracking-wider font-semibold">Terakhir Diubah</span>
                        <span class="text-xs font-bold text-slate-700 truncate block">{{ $pemeliharaan->updater?->nama_lengkap ?? '—' }}</span>
                        <span class="text-[10px] text-slate-400 block">{{ ($pemeliharaan->updated_at != $pemeliharaan->created_at) ? formatTanggal($pemeliharaan->updated_at, 'd M Y, H:i') . " WIB" : '—' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="card bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-4 bg-slate-50/50 border-b border-slate-100">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Peralatan Bersangkutan</h3>
                </div>
                <div class="p-4 space-y-4 text-sm">
                    <div>
                        <span class="text-xs text-slate-400 block font-medium">Nomor Seri</span>
                        <span class="font-semibold text-slate-700">{{ $pemeliharaan->peralatan?->nomor_seri }}</span>
                    </div>
                    <div class="grid grid-cols-2 gap-2 border-t border-slate-50 pt-2">
                        <div>
                            <span class="text-xs text-slate-400 block font-medium">Jenis</span>
                            <span class="font-semibold text-slate-700">{{ $pemeliharaan->peralatan?->jenis?->nama_jenis }}</span>
                        </div>
                        <div>
                            <span class="text-xs text-slate-400 block font-medium">Status Saat Ini</span>
                            <div class="mt-0.5"><x-badge-status :status="$pemeliharaan->peralatan?->status" type="alat" /></div>
                        </div>
                    </div>
                    <div class="border-t border-slate-50 pt-2">
                        <span class="text-xs text-slate-400 block font-medium">Lokasi</span>
                        <span class="font-semibold text-slate-700 block mt-0.5">
                            <i class="ti ti-map-pin text-slate-400 me-1"></i>{{ $pemeliharaan->peralatan?->lokasi ?: '—' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>
