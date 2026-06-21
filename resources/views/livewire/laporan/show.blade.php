{{-- resources/views/livewire/laporan/show.blade.php --}}
<div>
    <x-page-header 
        title="Rincian Data Laporan" 
        subtitle="Analisis data tabel historis berkas {{ $jenis }}"
        :breadcrumbs="[['label' => 'Pusat Laporan', 'url' => route('laporan.index')], ['label' => 'Rincian ' . ucfirst($jenis)]]">
        
        <div class="flex justify-end">
            <a href="{{ route('laporan.download', $jenis) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-xl shadow-sm transition-colors">
                <i class="ti ti-file-type-pdf text-base"></i> Cetak Laporan
            </a>
        </div>
    </x-page-header>

    <div class="space-y-4">
        <div class="card bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                {{-- TABEL PERALATAN --}}
                @if($jenis === 'peralatan')
                    <table class="table w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="bg-slate-50 text-slate-400 font-bold text-xs uppercase border-b border-slate-200">
                                <th class="p-4">S/N Unit</th>
                                <th class="p-4">Jenis Peralatan</th>
                                <th class="p-4">Lokasi</th>
                                <th class="p-4">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                            @foreach($records as $row)
                                <tr>
                                    <td class="p-4 font-bold">{{ $row->nomor_seri }}</td>
                                    <td class="p-4">{{ $row->jenis?->nama_jenis }}</td>
                                    <td class="p-4">{{ $row->lokasi ?? '—' }}</td>
                                    <td class="p-4"><x-badge-status :status="$row->status" type="alat" /></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                {{-- TABEL PEMELIHARAAN --}}
                @elseif($jenis === 'pemeliharaan')
                    <table class="table w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="bg-slate-50 text-slate-400 font-bold text-xs uppercase border-b border-slate-200">
                                <th class="p-4">Alat</th>
                                <th class="p-4">Keterangan Perbaikan</th>
                                <th class="p-4">Tanggal Pemeliharaan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                            @foreach($records as $row)
                                <tr>
                                    <td class="p-4 font-bold">{{ $row->peralatan?->jenis?->nama_jenis }} <span class="text-xs font-normal text-slate-400 block font-mono">{{ $row->peralatan?->nomor_seri }}</span></td>
                                    <td class="p-4 text-xs text-slate-500">{{ $row->keterangan ?? '—' }}</td>
                                    <td class="p-4 text-xs">{{ $row->tanggal_pemeliharaan->format('d M Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                {{-- TABEL PEMINJAMAN --}}
                @else
                    <table class="table w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="bg-slate-50 text-slate-400 font-bold text-xs uppercase border-b border-slate-200">
                                <th class="p-4">Pemohon</th>
                                <th class="p-4">Tujuan Keperluan</th>
                                <th class="p-4">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                            @foreach($records as $row)
                                <tr>
                                    <td class="p-4"><span class="font-bold block">{{ $row->nomor_peminjaman }}</span><span class="text-xs text-slate-400 block">{{ $row->pengguna?->nama_lengkap }}</span></td>
                                    <td class="p-4 text-xs text-slate-500 max-w-xs truncate">{{ $row->tujuan_keperluan }}</td>
                                    <td class="p-4"><x-badge-status :status="$row->status" type="pengajuan" /></td>
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
</div>