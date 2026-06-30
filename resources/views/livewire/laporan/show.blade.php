<div class="space-y-6">
    <x-page-header 
        title="Rincian Analisis Data" 
        subtitle="Eksplorasi riwayat logis & berkas taktis kategori {{ $jenis }}"
        :breadcrumbs="[['label' => 'Pusat Laporan', 'url' => route('laporan.index')], ['label' => 'Rincian ' . ucfirst($jenis)]]">
        
        <div class="flex justify-end">
            <a href="{{ route('laporan.download', $jenis) }}" target="_blank" class="inline-flex items-center gap-2 px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-xl shadow-sm transition-all transform hover:-translate-y-0.5">
                <i class="ti ti-file-type-pdf text-sm"></i> Cetak Dokumen PDF
            </a>
        </div>
    </x-page-header>

    {{-- DYNAMIC MINI STATS BAR GRID --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @if($jenis === 'peralatan')
            <div class="bg-white border border-slate-200/80 p-4 rounded-2xl shadow-sm flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg"><i class="ti ti-box"></i></div>
                <div><span class="text-[11px] font-medium text-slate-400 uppercase tracking-wider block">Total Aset Terdaftar</span><strong class="text-slate-800 text-lg font-bold">{{ \App\Models\Peralatan::count() }} Unit</strong></div>
            </div>
            <div class="bg-white border border-slate-200/80 p-4 rounded-2xl shadow-sm flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-green-50 text-green-600 flex items-center justify-center text-lg"><i class="ti ti-circle-check"></i></div>
                <div><span class="text-[11px] font-medium text-slate-400 uppercase tracking-wider block">Unit Siap Digunakan</span><strong class="text-green-600 text-lg font-bold">{{ \App\Models\Peralatan::where('status', \App\Models\Peralatan::STATUS_TERSEDIA)->count() }} Unit</strong></div>
            </div>
            <div class="bg-white border border-slate-200/80 p-4 rounded-2xl shadow-sm flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg"><i class="ti ti-lock"></i></div>
                <div><span class="text-[11px] font-medium text-slate-400 uppercase tracking-wider block">Sedang Dipinjam Sektor</span><strong class="text-amber-600 text-lg font-bold">{{ \App\Models\Peralatan::where('status', \App\Models\Peralatan::STATUS_DIPINJAM)->count() }} Unit</strong></div>
            </div>
        @elseif($jenis === 'pemeliharaan')
            <div class="bg-white border border-slate-200/80 p-4 rounded-2xl shadow-sm flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center text-lg"><i class="ti ti-tool"></i></div>
                <div><span class="text-[11px] font-medium text-slate-400 uppercase tracking-wider block">Total Pemeliharaan</span><strong class="text-slate-800 text-lg font-bold">{{ \App\Models\Pemeliharaan::count() }} Log</strong></div>
            </div>
            <div class="bg-white border border-slate-200/80 p-4 rounded-2xl shadow-sm flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-cyan-50 text-cyan-600 flex items-center justify-center text-lg"><i class="ti ti-refresh"></i></div>
                <div><span class="text-[11px] font-medium text-slate-400 uppercase tracking-wider block">Tindakan Preventif</span><strong class="text-cyan-700 text-lg font-bold">{{ \App\Models\Pemeliharaan::where('jenis_pemeliharaan', 'Preventif')->count() }} Kali</strong></div>
            </div>
            <div class="bg-white border border-slate-200/80 p-4 rounded-2xl shadow-sm flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-lg"><i class="ti ti-alert-triangle"></i></div>
                <div><span class="text-[11px] font-medium text-slate-400 uppercase tracking-wider block">Perbaikan Korektif</span><strong class="text-rose-700 text-lg font-bold">{{ \App\Models\Pemeliharaan::where('jenis_pemeliharaan', 'Korektif')->count() }} Kali</strong></div>
            </div>
        @else
            <div class="bg-white border border-slate-200/80 p-4 rounded-2xl shadow-sm flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-yellow-50 text-yellow-600 flex items-center justify-center text-lg"><i class="ti ti-arrows-left-right"></i></div>
                <div><span class="text-[11px] font-medium text-slate-400 uppercase tracking-wider block">Total Transaksi</span><strong class="text-slate-800 text-lg font-bold">{{ \App\Models\Peminjaman::count() }} Berkas</strong></div>
            </div>
            <div class="bg-white border border-slate-200/80 p-4 rounded-2xl shadow-sm flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg"><i class="ti ti-clock-play"></i></div>
                <div><span class="text-[11px] font-medium text-slate-400 uppercase tracking-wider block">Sirkulasi Aktif</span><strong class="text-amber-600 text-lg font-bold">{{ \App\Models\Peminjaman::where('status', \App\Models\Peminjaman::DIPINJAM)->count() }} Aktif</strong></div>
            </div>
            <div class="bg-white border border-slate-200/80 p-4 rounded-2xl shadow-sm flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-green-50 text-green-600 flex items-center justify-center text-lg"><i class="ti ti-circle-check"></i></div>
                <div><span class="text-[11px] font-medium text-slate-400 uppercase tracking-wider block">Sirkulasi Selesai</span><strong class="text-green-600 text-lg font-bold">{{ \App\Models\Peminjaman::where('status', \App\Models\Peminjaman::SELESAI)->count() }} Arsip</strong></div>
            </div>
        @endif
    </div>

    {{-- MAIN TABLE CARD --}}
    <div class="card bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            {{-- TABEL INVENTARIS PERALATAN --}}
            @if($jenis === 'peralatan')
                <table class="table w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="bg-slate-50/70 text-slate-400 font-semibold text-xs uppercase border-b border-slate-200/60">
                            <th class="py-3.5 px-5">Spesifikasi Alat</th>
                            <th class="py-3.5 px-5">Kapasitas Ukur</th>
                            <th class="py-3.5 px-5">Lokasi Simpan</th>
                            <th class="py-3.5 px-5 text-center">Status Unit</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                        @foreach($records as $row)
                            <tr class="hover:bg-slate-50/40 transition-colors">
                                <td class="py-3.5 px-5">
                                    <span class="font-bold text-slate-800 block">{{ $row->nomor_seri }}</span>
                                    <span class="text-[11px] text-slate-400 font-normal block mt-0.5">{{ $row->jenis?->nama_jenis }}</span>
                                </td>
                                <td class="py-3.5 px-5 text-slate-600">
                                    {{ $row->kapasitas ? number_format($row->kapasitas, fmod($row->kapasitas, 1) == 0 ? 0 : 1, ',', '.') : '—' }} {{ $row->satuan ?? '' }}
                                </td>
                                <td class="py-3.5 px-5 text-slate-600">{{ $row->lokasi ?? '—' }}</td>
                                <td class="py-3.5 px-5 text-center"><x-badge-status :status="$row->status" type="alat" /></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            {{-- TABEL TRANSAKSI PEMELIHARAAN --}}
            @elseif($jenis === 'pemeliharaan')
                <table class="table w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="bg-slate-50/70 text-slate-400 font-semibold text-xs uppercase border-b border-slate-200/60">
                            <th class="py-3.5 px-5">Target Peralatan</th>
                            <th class="py-3.5 px-5">Kode Log</th>
                            <th class="py-3.5 px-5">Deskripsi Perbaikan</th>
                            <th class="py-3.5 px-5">Waktu Eksekusi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                        @foreach($records as $row)
                            <tr class="hover:bg-slate-50/40 transition-colors">
                                <td class="py-3.5 px-5">
                                    <span class="font-bold text-slate-800 block">{{ $row->peralatan?->jenis?->nama_jenis }}</span>
                                    <span class="text-[11px] text-slate-400 font-mono block mt-0.5">S/N: {{ $row->peralatan?->nomor_seri }}</span>
                                </td>
                                <td class="py-3.5 px-5 font-mono text-xs text-slate-600">{{ $row->nomor_pemeliharaan }}</td>
                                <td class="py-3.5 px-5 text-xs text-slate-500 max-w-sm truncate">{{ $row->deskripsi ?? '—' }}</td>
                                <td class="py-3.5 px-5 text-xs text-slate-600">{{ $row->tanggal_pemeliharaan->format('d M Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            {{-- TABEL TRANSAKSI SIRKULASI PEMINJAMAN --}}
            @else
                <table class="table w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="bg-slate-50/70 text-slate-400 font-semibold text-xs uppercase border-b border-slate-200/60">
                            <th class="py-3.5 px-5">Identitas Peminjam</th>
                            <th class="py-3.5 px-5">Nomor Berkas</th>
                            <th class="py-3.5 px-5">Tujuan Keperluan</th>
                            <th class="py-3.5 px-5 text-center">Status Berkas</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                        @foreach($records as $row)
                            <tr class="hover:bg-slate-50/40 transition-colors">
                                <td class="py-3.5 px-5">
                                    <span class="font-bold text-slate-800 block">{{ $row->nama_pengguna }}</span>
                                    <span class="text-[11px] text-slate-400 block mt-0.5">{{ $row->unit }} — {{ $row->jabatan }}</span>
                                </td>
                                <td class="py-3.5 px-5 font-mono text-xs text-slate-600">{{ $row->nomor_peminjaman }}</td>
                                <td class="py-3.5 px-5 text-xs text-slate-500 max-w-xs truncate">{{ $row->tujuan_keperluan }}</td>
                                <td class="py-3.5 px-5 text-center"><x-badge-status :status="$row->status" type="pengajuan" /></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <div class="p-4 bg-slate-50/50 border-t border-slate-100">
            {{ $records->links() }}
        </div>
    </div>
</div>