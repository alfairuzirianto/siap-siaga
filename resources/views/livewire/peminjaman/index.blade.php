<div>
    <x-page-header 
        title="Peminjaman" 
        subtitle="Riwayat pengajuan peminjaman"
        :breadcrumbs="[['label' => 'Peminjaman']]">

        <a href="{{ route('peminjaman.create') }}" wire:navigate class="btn-primary flex items-center gap-2 py-2 px-4 rounded-xl text-sm font-medium">
            <i class="ti ti-plus text-base"></i>
            <span>Buat Peminjaman</span>
        </a>
    </x-page-header>

    <div class="grid grid-cols-1 gap-6">
        <div class="card bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-4 bg-slate-50/60 border-b border-slate-100 flex flex-col sm:flex-row gap-3 justify-between">
                <div class="relative flex-1">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                        <i class="ti ti-search text-base"></i>
                    </span>
                    <input type="text" wire:model.live.debounce.300ms="search" 
                            class="form-input rounded-xl border-slate-200 pl-9 text-sm py-2" placeholder="Cari nomor peminjaman, nama pemohon">
                </div>
                <div class="sm:w-48">
                    <select wire:model.live="filterStatus" class="form-input rounded-xl border-slate-200 text-sm py-2">
                        <option value="">-- Semua Status --</option>
                        @foreach(App\Models\Peminjaman::STATUS_PEMINJAMAN as $status)
                            <option value="{{ $status }}">{{ $status }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            @if($peminjamans->isEmpty())
                <div class="p-12">
                    <x-empty-state 
                        icon="ti-file-description"
                        title="Tidak ada peminjaman"
                        message="Belum ada pengajuan peminjaman." />
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="table">
                        <thead>
                            <tr class="border-b border-slate-200 text-xs font-bold uppercase text-slate-400 bg-slate-50/40 tracking-wider">
                                <th class="py-3.5 px-6">Nomor</th>
                                <th class="py-3.5 px-4">Pengguna</th>
                                <th class="py-3.5 px-4">Keperluan</th>
                                <th class="py-3.5 px-4">Tanggal</th>
                                <th class="py-3.5 px-4">Status</th>
                                <th class="py-3.5 px-6 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700 font-medium">
                            @foreach($peminjamans as $peminjaman)
                                <tr class="hover:bg-slate-50/30 transition-colors" wire:key="pjm-row-{{ $peminjaman->id }}">
                                    <td class="py-3.5 px-6 font-semibold text-slate-800 whitespace-nowrap">{{ $peminjaman->nomor_peminjaman }}</td>
                                    <td class="py-3.5 px-4 whitespace-nowrap">
                                        <span class="text-slate-800 block font-bold">{{ $peminjaman->nama_pengguna }}</span>
                                        <span class="text-[11px] text-slate-400 font-normal">{{ $peminjaman->unit? : '—' }}</span>
                                    </td>
                                    <td class="py-3.5 px-4 max-w-xs truncate" title="{{ $peminjaman->tujuan_keperluan }}">{{ $peminjaman->tujuan_keperluan }}</td>
                                    <td class="py-3.5 px-4 text-xs whitespace-nowrap font-normal">
                                        <div class="text-slate-700 font-medium">{{ $peminjaman->tgl_pinjam ? formatTanggal($peminjaman->tgl_pinjam, 'd M Y'): '—' }}</div>
                                        <div class="text-slate-400 mt-0.5">s/d {{ $peminjaman->tgl_kembali ? formatTanggal($peminjaman->tgl_kembali, 'd M Y'): '—' }}</div>
                                    </td>
                                    <td class="py-3.5 px-4 whitespace-nowrap">
                                        <x-badge-status :status="$peminjaman->status" type="pengajuan" />
                                    </td>
                                    <td class="py-3.5 px-6 text-center whitespace-nowrap">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <a href="{{ route('peminjaman.show', $peminjaman) }}" wire:navigate class="btn-ghost p-2 sm:p-1.5 text-slate-600 rounded-md">
                                                <i class="ti ti-eye text-base"></i>
                                            </a>
                                            @if ($peminjaman->status === App\Models\Peminjaman::SELESAI)
                                                <button class="btn-ghost p-2 sm:p-1.5 text-red-500 hover:bg-red-50 rounded-md"
                                                    x-on:click="
                                                        Swal.fire({
                                                            title: 'Hapus peminjaman?',
                                                            text: 'Data peminjaman ini akan dihapus permanen.',
                                                            icon: 'warning',
                                                            showCancelButton: true,
                                                            confirmButtonColor: '#dc2626',
                                                            cancelButtonColor: '#6b7280',
                                                            confirmButtonText: 'Ya, Hapus!',
                                                            cancelButtonText: 'Batal'
                                                        }).then((result) => {
                                                            if (result.isConfirmed) { 
                                                                $wire.delete({{ $peminjaman->id }})
                                                            }
                                                        });
                                                    ">
                                                    <i class="ti ti-trash text-base"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            <div class="p-4 border-t border-slate-100">
                {{ $peminjamans->links() }}
            </div>
            @endif
        </div>
    </div>
</div>