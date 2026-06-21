<div>
    <x-page-header 
        :title="$isPeminjaman ? 'Validasi Peminjaman' : 'Validasi Pengembalian'"
        :subtitle="$isPeminjaman ? 'Daftar pengajuan peminjaman peralatan siaga' : 'Daftar pengajuan pengembalian peralatan siaga'" />

    <div class="card bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex flex-col sm:flex-row items-center gap-3">
            <div class="sm:w-48">
                <select wire:model.live="filterStatus" class="form-input rounded-xl border-slate-200 text-sm py-2">
                    <option value="">-- Semua Status --</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status }}">{{ $status }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        @if($pengajuans->isEmpty())
        <div class="p-12">
            <x-empty-state 
                icon="ti-shield-check"
                title="Tidak ada pengajuan"
                message="Belum ada pengajuan saat ini." />
        </div>
        @else
            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr class="border-b border-slate-200 text-xs font-bold uppercase text-slate-400 bg-slate-50/40 tracking-wider">
                            <th class="py-3.5 px-6">Pemohon</th>
                            <th class="py-3.5 px-4">Keperluan</th>
                            <th class="py-3.5 px-4">Rencana Durasi</th>
                            <th class="py-3.5 px-4">Status Saat Ini</th>
                            <th class="py-3.5 px-6 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700 font-medium">
                        @foreach($pengajuans as $row)
                            <tr class="hover:bg-slate-50/30 transition-colors" wire:key="req-row-{{ $row->id }}">
                                <td class="py-3.5 px-6 whitespace-nowrap">
                                    <span class="text-slate-800 font-bold block">{{ $row->pengguna?->nama_lengkap }}</span>
                                    <span class="text-xs text-slate-400">{{ $row->nomor_peminjaman }}</span>
                                </td>
                                <td class="py-3.5 px-4 max-w-xs truncate" title="{{ $row->tujuan_keperluan }}">{{ $row->tujuan_keperluan }}</td>
                                <td class="py-3.5 px-4 text-xs whitespace-nowrap font-normal">
                                    <div class="text-slate-700 font-medium">{{ $row->tgl_rencana_pinjam?->format('d M Y') }}</div>
                                    <div class="text-slate-400 mt-0.5">s/d {{ $row->tgl_rencana_kembali?->format('d M Y') }}</div>
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <x-badge-status :status="$row->status" type="pengajuan" />
                                </td>
                                <td class="py-3.5 px-6 text-center whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-2">
                                        @if($row->status === App\Models\Peminjaman::PINJAM_DIAJUKAN)
                                            <a href="{{ route('peminjaman.show', $row) }}" wire:navigate
                                               class="inline-flex items-center gap-1.5 px-4 py-1.5 bg-primary-600 hover:bg-primary-700 text-white text-xs font-bold rounded-xl transition-colors shadow-xs outline-none focus:outline-none">
                                                <i class="ti ti-shield-check text-sm"></i> Periksa
                                            </a>
                                        @else
                                            <a href="{{ route('peminjaman.show', $row) }}" wire:navigate
                                               class="inline-flex items-center gap-1.5 px-4 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold rounded-xl transition-colors outline-none focus:outline-none">
                                                <i class="ti ti-eye text-sm"></i> Lihat Detail
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-slate-100 bg-slate-50/30">
                {{ $pengajuans->links() }}
            </div>
        @endif
    </div>
</div>