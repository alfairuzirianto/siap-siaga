<div>
    <x-page-header 
        title="Peminjaman" 
        subtitle="Riwayat pengajuan peminjaman"
        :breadcrumbs="auth()->user()->isAdmin() ? [
            ['label' => 'Peminjaman']
        ] : []">

        @can('create', App\Models\Peminjaman::class)
            <a href="{{ route('peminjaman.create') }}" wire:navigate class="btn-primary flex items-center gap-2 py-2 px-4 rounded-xl text-sm font-medium">
                <i class="ti ti-plus text-base"></i>
                <span>Buat Pengajuan Pinjam</span>
            </a>
        @endcan
    </x-page-header>

    <div class="grid grid-cols-1 gap-6">
        <div class="card bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-4 bg-slate-50/60 border-b border-slate-100 flex flex-col sm:flex-row items-center gap-3 justify-between">
                @if(auth()->user()->isAdmin())
                <div class="relative flex-1">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                        <i class="ti ti-search text-base"></i>
                    </span>
                    <input type="text" wire:model.live.debounce.300ms="search" 
                            class="form-input rounded-xl border-slate-200 pl-9 text-sm py-2" placeholder="Cari nomor peminjaman, nama pemohon">
                </div>
                @endif
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
                                <th class="py-3.5 px-6">No. Peminjaman</th>
                                @if(auth()->user()->isAdmin())
                                    <th class="py-3.5 px-4">Pemohon</th>
                                @endif
                                <th class="py-3.5 px-4">Keperluan</th>
                                <th class="py-3.5 px-4">Rencana Durasi</th>
                                <th class="py-3.5 px-4">Status</th>
                                <th class="py-3.5 px-6 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700 font-medium">
                            @foreach($peminjamans as $peminjaman)
                                <tr class="hover:bg-slate-50/30 transition-colors" wire:key="pjm-row-{{ $peminjaman->id }}">
                                    <td class="py-3.5 px-6 font-semibold text-slate-800 whitespace-nowrap">{{ $peminjaman->nomor_peminjaman }}</td>
                                    @if(auth()->user()->isAdmin())
                                        <td class="py-3.5 px-4 whitespace-nowrap">
                                            <span class="text-slate-800 block font-bold">{{ $peminjaman->pengguna?->nama_lengkap }}</span>
                                            <span class="text-[11px] text-slate-400 font-normal">NIP: {{ $peminjaman->pengguna?->nip ?: '—' }}</span>
                                        </td>
                                    @endif
                                    <td class="py-3.5 px-4 max-w-xs truncate" title="{{ $peminjaman->tujuan_keperluan }}">{{ $peminjaman->tujuan_keperluan }}</td>
                                    <td class="py-3.5 px-4 text-xs whitespace-nowrap font-normal">
                                        <div class="text-slate-700 font-medium">{{ $peminjaman->tgl_rencana_pinjam?->format('d M Y') }}</div>
                                        <div class="text-slate-400 mt-0.5">s/d {{ $peminjaman->tgl_rencana_kembali?->format('d M Y') }}</div>
                                    </td>
                                    <td class="py-3.5 px-4 whitespace-nowrap">
                                        <x-badge-status :status="$peminjaman->status" type="pengajuan" />
                                    </td>
                                    <td class="py-3.5 px-6 text-center whitespace-nowrap">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <a href="{{ route('peminjaman.show', $peminjaman) }}" wire:navigate
                                                class="inline-flex items-center gap-1 px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-semibold rounded-xl transition-all outline-none focus:outline-none">
                                                <i class="ti ti-eye text-sm"></i> Lihat Detail
                                            </a>
                                            @if(auth()->user()->isPengguna() && $peminjaman->status === App\Models\Peminjaman::PINJAM_DIAJUKAN)
                                                <button x-on:click="
                                                            Swal.fire({
                                                                title: 'Batalkan Pengajuan?',
                                                                text: 'Pengajuan ini akan dibatalkan.',
                                                                icon: 'warning',
                                                                showCancelButton: true,
                                                                confirmButtonColor: '#dc2626',
                                                                cancelButtonColor: '#6b7280',
                                                                confirmButtonText: 'Konfirmasi',
                                                                cancelButtonText: 'Batal'
                                                            }).then((result) => {
                                                                if (result.isConfirmed) { 
                                                                    $wire.batal({{ $peminjaman->id }})
                                                                }
                                                            });
                                                        "
                                                        class="px-2.5 py-1 text-xs text-red-600 bg-red-50 hover:bg-red-100 ring-1 ring-red-200/60 rounded-xl transition-all font-semibold outline-none focus:outline-none">
                                                    Batalkan
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