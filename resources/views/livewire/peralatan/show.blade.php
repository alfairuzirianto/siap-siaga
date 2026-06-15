<div>
    <x-page-header
        title="Detail Peralatan: {{ $peralatan->nomor_seri }}"
        :breadcrumbs="[
            ['label' => 'Peralatan', 'url' => route('peralatan.index')],
            ['label' => $peralatan->nomor_seri],
        ]">

        {{-- ==============================
             AKSI
             ============================== --}}
        @can('update', $peralatan)
        <a href="{{ route('peralatan.edit', $peralatan) }}" wire:navigate 
           class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 shadow-sm shadow-primary-500/10 rounded-xl transition-all active:scale-[0.98]">
            <i class="ti ti-edit text-base"></i>
            <span>Edit</span>
        </a>
        @endcan
    </x-page-header>

    {{-- ==============================
         KONTEN UTAMA
         ============================== --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
        
        {{-- ==============================
             Foto & Spesifikasi
             ============================== --}}
        <div class="lg:col-span-1 space-y-6">
            <div class="card bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="relative bg-slate-900 aspect-[16/10] lg:aspect-[4/3] flex items-center justify-center overflow-hidden group">
                    @if($peralatan->foto)
                        <img src="{{ asset('storage/' . $peralatan->foto) }}"
                             alt="{{ $peralatan->nomor_seri }}"
                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                    @else
                        <div class="flex flex-col items-center gap-2 text-slate-500 py-16">
                            <div class="p-4 rounded-full bg-slate-800 text-slate-600 mb-1">
                                <i class="ti ti-photo-off text-4xl"></i>
                            </div>
                            <span class="text-xs font-medium text-slate-400">Belum ada dokumentasi foto</span>
                        </div>
                    @endif
                </div>
            </div>
            <div class="card bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-4">
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Spesifikasi Alat</h3>
                
                <div class="space-y-3">
                    <div class="grid grid-cols-2 gap-4 border-t border-slate-50 pt-2">
                        <div>
                            <span class="text-xs text-slate-400 block font-medium">Jenis</span>
                            <span class="text-sm font-semibold text-slate-700">{{ $peralatan->jenis?->nama_jenis ?? '—' }}</span>
                        </div>
                        <div>
                            <span class="text-xs text-slate-400 block font-medium">Status</span>
                            <x-badge-status :status="$peralatan->status" type="alat" />
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4 border-t border-slate-50 pt-2">
                        <div>
                            <span class="text-xs text-slate-400 block font-medium">Kapasitas</span>
                            <span class="text-sm font-semibold text-slate-700">
                                {{ $peralatan->kapasitas ? number_format($peralatan->kapasitas, 0, ',', '.') : '—' }}
                            </span>
                        </div>
                        <div>
                            <span class="text-xs text-slate-400 block font-medium">Satuan</span>
                            <span class="text-sm font-semibold text-slate-700">{{ $peralatan->satuan ?? '—' }}</span>
                        </div>
                    </div>
                    <div class="border-t border-slate-50 pt-2">
                        <span class="text-xs text-slate-400 block font-medium">Lokasi Penyimpanan</span>
                        <div class="flex items-center gap-1.5 mt-0.5 text-sm font-medium text-slate-700">
                            <i class="ti ti-map-pin text-slate-400 text-base"></i>
                            <span>{{ $peralatan->lokasi ?? 'Belum ditentukan' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ==============================
             Riwayat
             ============================== --}}
        <div class="lg:col-span-2 space-y-6">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="card bg-white rounded-xl border border-slate-200/80 p-4 flex items-center gap-3.5 shadow-sm">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 ring-1 ring-blue-100">
                        <i class="ti ti-user-plus text-xl"></i>
                    </div>
                    <div class="min-w-0">
                        <span class="text-[11px] text-slate-400 font-medium block uppercase tracking-wider">Dibuat Oleh</span>
                        <span class="text-sm font-semibold text-slate-700 truncate block">{{ $peralatan->creator?->nama_lengkap ?? '—' }}</span>
                        <span class="text-[11px] text-slate-400 block mt-0.5">{{ $peralatan->created_at?->format('d M Y, H:i') }} WIB</span>
                    </div>
                </div>
                <div class="card bg-white rounded-xl border border-slate-200/80 p-4 flex items-center gap-3.5 shadow-sm">
                    <div class="w-10 h-10 rounded-xl bg-slate-50 text-slate-600 flex items-center justify-center shrink-0 ring-1 ring-slate-200/60">
                        <i class="ti ti-edit-circle text-xl"></i>
                    </div>
                    <div class="min-w-0">
                        <span class="text-[11px] text-slate-400 font-medium block uppercase tracking-wider">Terakhir Diperbarui</span>
                        <span class="text-sm font-semibold text-slate-700 truncate block">{{ $peralatan->updater?->nama_lengkap ?? '—' }}</span>
                        <span class="text-[11px] text-slate-400 block mt-0.5">
                            {{ ($peralatan->updated_at != $peralatan->created_at) ? $peralatan->updated_at->format('d M Y, H:i') . ' WIB' : '—' }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="card bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="border-b border-slate-100 bg-slate-50/60 px-4 flex items-center justify-between">
                    <div class="flex gap-2">
                        <button wire:click="switchTab('pemeliharaan')"
                                class="py-3 px-3 border-b-2 text-sm font-medium transition-all flex items-center gap-2 outline-none focus:outline-none focus:ring-0 focus-visible:outline-none {{ $activeTab === 'pemeliharaan' ? 'border-primary-600 text-primary-600 font-semibold' : 'border-transparent text-slate-500 hover:text-primary-600' }}">
                            <i class="ti ti-tool text-base"></i>
                            <span>Riwayat Maintenance</span>
                            <span class="text-xs px-1.5 py-0.5 rounded-full bg-slate-200/70 text-slate-600 font-bold ml-0.5">
                                {{ $peralatan->pemeliharaan->count() }}
                            </span>
                        </button>
                        <button wire:click="switchTab('peminjaman')"
                                class="py-3 px-3 border-b-2 text-sm font-medium transition-all flex items-center gap-2 outline-none focus:outline-none focus:ring-0 focus-visible:outline-none {{ $activeTab === 'peminjaman' ? 'border-primary-600 text-primary-600 font-semibold' : 'border-transparent text-slate-500 hover:text-primary-600' }}">
                            <i class="ti ti-file-description text-base"></i>
                            <span>Riwayat Peminjaman</span>
                            <span class="text-xs px-1.5 py-0.5 rounded-full bg-slate-200/70 text-slate-600 font-bold ml-0.5">
                                {{ $peralatan->peminjamanDetail->count() }}
                            </span>
                        </button>
                    </div>
                </div>

                {{-- ==========================================
                     Tab Riwayat Pemeliharaan
                     ========================================== --}}
                <div x-show="$wire.activeTab === 'pemeliharaan'" class="p-6">
                    @if($peralatan->pemeliharaan->isNotEmpty())
                        <div class="overflow-x-auto -mx-6">
                            <table class="w-full text-left border-collapse min-w-[600px]">
                                <thead>
                                    <tr class="border-b border-slate-100 text-xs font-bold uppercase tracking-wider text-slate-400 bg-slate-50/40">
                                        <th class="py-3 px-6">No. Pemeliharaan / Tgl</th>
                                        <th class="py-3 px-4">Petugas</th>
                                        <th class="py-3 px-4">Jenis Tindakan</th>
                                        <th class="py-3 px-4 text-right">Biaya Perbaikan</th>
                                        <th class="py-3 px-6">Deskripsi Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50 text-sm">
                                    @foreach($peralatan->pemeliharaan as $maint)
                                        <tr class="hover:bg-slate-50/50 transition-colors">
                                            <td class="py-3.5 px-6 whitespace-nowrap">
                                                <span class="font-semibold text-slate-800 block">{{ $maint->nomor_pemeliharaan }}</span>
                                                <span class="text-xs text-slate-400">{{ $maint->tanggal_pemeliharaan?->format('d M Y') }}</span>
                                            </td>
                                            <td class="py-3.5 px-4 text-slate-600 font-medium whitespace-nowrap">
                                                {{ $maint->nama_petugas }}
                                            </td>
                                            <td class="py-3.5 px-4 whitespace-nowrap">
                                                <span class="inline-flex items-center rounded-md px-2 py-0.5 text-xs font-medium ring-1 ring-inset {{ $maint->jenis_pemeliharaan === 'Korektif' ? 'bg-rose-50 text-rose-700 ring-rose-600/10' : 'bg-cyan-50 text-cyan-700 ring-cyan-600/10' }}">
                                                    {{ $maint->jenis_pemeliharaan }}
                                                </span>
                                            </td>
                                            <td class="py-3.5 px-4 text-right font-mono font-medium text-slate-700 whitespace-nowrap">
                                                Rp {{ number_format($maint->biaya, 0, ',', '.') }}
                                            </td>
                                            <td class="py-3.5 px-6 text-slate-500 max-w-xs truncate" title="{{ $maint->deskripsi }}">
                                                {{ $maint->deskripsi ?? '—' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <x-empty-state
                            icon="ti-tool"
                            title="Belum ada riwayat pemeliharaan"
                            message="Alat siaga logistik ini belum pernah mencatat tindakan korektif atau preventif." />
                    @endif
                </div>

                {{-- ==========================================
                     Tab Riwayat Peminjaman
                     ========================================== --}}
                <div x-show="$wire.activeTab === 'peminjaman'" class="p-6" style="display: none;">
                    @if($peralatan->peminjamanDetail->isNotEmpty())
                        <div class="overflow-x-auto -mx-6">
                            <table class="w-full text-left border-collapse min-w-[600px]">
                                <thead>
                                    <tr class="border-b border-slate-100 text-xs font-bold uppercase tracking-wider text-slate-400 bg-slate-50/40">
                                        <th class="py-3 px-6">No. Pinjam / Pemohon</th>
                                        <th class="py-3 px-4">Tujuan Penggunaan</th>
                                        <th class="py-3 px-4">Rencana Durasi</th>
                                        <th class="py-3 px-4">Status Alur</th>
                                        <th class="py-3 px-6">Realisasi Kembali</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50 text-sm">
                                    @foreach($peralatan->peminjamanDetail as $detail)
                                        @php $pinjam = $detail->peminjaman; @endphp
                                        @if($pinjam)
                                        <tr class="hover:bg-slate-50/50 transition-colors">
                                            <td class="py-3.5 px-6 whitespace-nowrap">
                                                <span class="font-semibold text-slate-800 block">{{ $pinjam->nomor_peminjaman }}</span>
                                                <span class="text-xs text-slate-500 font-medium">{{ $pinjam->pengguna?->name ?? '—' }}</span>
                                            </td>
                                            <td class="py-3.5 px-4 text-slate-500 max-w-xs truncate" title="{{ $pinjam->tujuan_keperluan }}">
                                                {{ $pinjam->tujuan_keperluan }}
                                            </td>
                                            <td class="py-3.5 px-4 text-slate-600 text-xs whitespace-nowrap">
                                                <div class="font-medium text-slate-700">
                                                    {{ $pinjam->tgl_rencana_pinjam?->format('d M Y') }}
                                                </div>
                                                <div class="text-slate-400 mt-0.5">
                                                    s/d {{ $pinjam->tgl_rencana_kembali?->format('d M Y') }}
                                                </div>
                                            </td>
                                            <td class="py-3.5 px-4 whitespace-nowrap">
                                                <x-badge-status :status="$pinjam->status" type="pengajuan" />
                                            </td>
                                            <td class="py-3.5 px-6 whitespace-nowrap font-medium text-slate-600">
                                                @if($pinjam->tgl_realisasi_kembali)
                                                    <span class="text-green-600 flex items-center gap-1">
                                                        <i class="ti ti-calendar-check text-base"></i>
                                                        {{ $pinjam->tgl_realisasi_kembali->format('d M Y') }}
                                                    </span>
                                                @else
                                                    <span class="text-slate-400 italic text-xs">Belum dikembalikan</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <x-empty-state
                            icon="ti-file-description"
                            title="Belum ada riwayat peminjaman"
                            message="Peralatan logistik ini belum memiliki riwayat pengajuan sirkulasi sirkuit peminjaman." />
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>