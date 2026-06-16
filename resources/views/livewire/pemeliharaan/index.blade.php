<div>
    <x-page-header 
        title="Maintenance Peralatan" 
        subtitle="Pantau riwayat pemeliharaan"
        :breadcrumbs="[['label' => 'Maintenance']]">
        @can('create', App\Models\Pemeliharaan::class)
        <a href="{{ route('pemeliharaan.create') }}" wire:navigate class="btn-primary flex items-center gap-2 py-2 px-4 rounded-xl shadow-sm text-sm font-medium">
            <i class="ti ti-plus text-base"></i>
            <span>Catat Pemeliharaan</span>
        </a>
        @endcan
    </x-page-header>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="card p-5 flex items-center gap-4 bg-white rounded-2xl border border-slate-200">
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 ring-1 ring-amber-100 flex items-center justify-center shrink-0">
                <i class="ti ti-report-money text-2xl"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Biaya Pemeliharaan</p>
                <p class="text-xl font-bold text-slate-800 font-display mt-0.5">Rp {{ number_format($totalBiaya, 0, ',', '.') }}</p>
            </div>
        </div>
        <div class="card p-5 flex items-center gap-4 bg-white rounded-2xl border border-slate-200">
            <div class="w-12 h-12 rounded-xl bg-cyan-50 text-cyan-600 ring-1 ring-cyan-100 flex items-center justify-center shrink-0">
                <i class="ti ti-shield-check text-2xl"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Tindakan Preventif</p>
                <p class="text-xl font-bold text-slate-800 font-display mt-0.5">{{ $countPreventif }} Kali</p>
            </div>
        </div>
        <div class="card p-5 flex items-center gap-4 bg-white rounded-2xl border border-slate-200">
            <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 ring-1 ring-rose-100 flex items-center justify-center shrink-0">
                <i class="ti ti-tool text-2xl"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Tindakan Korektif</p>
                <p class="text-xl font-bold text-slate-800 font-display mt-0.5">{{ $countKorektif }} Kali</p>
            </div>
        </div>
    </div>

    <div class="card bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-4 bg-slate-50/60 border-b border-slate-100 flex flex-col sm:flex-row items-center gap-3 justify-between">
            <div class="relative flex-1">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                    <i class="ti ti-search text-base"></i>
                </span>
                <input type="text" wire:model.live.debounce.300ms="search" 
                       class="form-input rounded-xl border-slate-200 pl-9 text-sm py-2" placeholder="Cari No. Maintenance, No. Alat, Nama Petugas">
            </div>

            <div class="sm:w-48">
                <select wire:model.live="filterJenis" class="form-input rounded-xl border-slate-200 text-sm py-2">
                    <option value="">-- Semua Jenis --</option>
                    @foreach(App\Models\Pemeliharaan::JENIS_PEMELIHARAAN as $jenis)
                        <option value="{{ $jenis }}">{{ $jenis }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        @if($pemeliharaans->isEmpty())
            <div class="p-12">
                <x-empty-state icon="ti-tools" title="Data maintenance kosong" message="Tidak ditemukan log tindakan pemeliharaan yang sesuai kriteria pencarian." />
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 text-xs font-bold uppercase text-slate-400 bg-slate-50/30 tracking-wider">
                            <th class="py-3.5 px-6">No. Maintenance</th>
                            <th class="py-3.5 px-4">Peralatan</th>
                            <th class="py-3.5 px-4">Petugas</th>
                            <th class="py-3.5 px-4">Jenis</th>
                            <th class="py-3.5 px-4 text-right">Biaya</th>
                            <th class="py-3.5 px-6 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700 font-medium">
                        @foreach($pemeliharaans as $pemeliharaan)
                            <tr class="hover:bg-slate-50/40 transition-colors" wire:key="maint-row-{{ $pemeliharaan->id }}">
                                <td class="py-3.5 px-6 whitespace-nowrap">
                                    <span class="text-slate-800 font-semibold block">{{ $pemeliharaan->nomor_pemeliharaan }}</span>
                                    <span class="text-xs text-slate-400 font-normal">{{ $pemeliharaan->tanggal_pemeliharaan?->format('d M Y') }}</span>
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <span class="text-slate-800 block">{{ $pemeliharaan->peralatan?->nomor_seri }}</span>
                                    <span class="text-xs text-slate-400 font-normal">{{ $pemeliharaan->peralatan?->jenis?->nama_jenis }}</span>
                                </td>
                                <td class="py-3.5 px-4 text-slate-600 whitespace-nowrap">{{ $pemeliharaan->nama_petugas }}</td>
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <span class="inline-flex items-center rounded-md px-2 py-0.5 text-xs font-semibold ring-1 ring-inset {{ $pemeliharaan->jenis_pemeliharaan === 'Korektif' ? 'bg-rose-50 text-rose-700 ring-rose-600/10' : 'bg-cyan-50 text-cyan-700 ring-cyan-600/10' }}">
                                        {{ $pemeliharaan->jenis_pemeliharaan }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-right text-slate-800 whitespace-nowrap">Rp {{ number_format($pemeliharaan->biaya, 0, ',', '.') }}</td>
                                <td class="py-3.5 px-6 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        @can('view', $pemeliharaan)
                                        <a href="{{ route('pemeliharaan.show', $pemeliharaan) }}" wire:navigate class="btn-ghost p-1.5 rounded-lg outline-none focus:outline-none focus-visible:outline-none">
                                            <i class="ti ti-eye text-base"></i>
                                        </a>
                                        @endcan
                                        @can('update', $pemeliharaan)
                                        <a href="{{ route('pemeliharaan.edit', $pemeliharaan) }}" wire:navigate class="btn-ghost p-1.5 rounded-lg outline-none focus:outline-none focus-visible:outline-none">
                                            <i class="ti ti-edit text-base"></i>
                                        </a>
                                        @endcan
                                        @can('delete', $pemeliharaan)
                                        <button class="btn-ghost p-1.5 text-red-500 rounded-lg outline-none focus:outline-none focus-visible:outline-none"
                                                x-on:click="Swal.fire({
                                                    title: 'Hapus Log?', text: 'Data pemeliharaan ini akan dihapus permanen.', icon: 'warning',
                                                    showCancelButton: true, confirmButtonColor: '#dc2626', cancelButtonColor: '#6b7280',
                                                    confirmButtonText: 'Ya, Hapus!', cancelButtonText: 'Batal'
                                                }).then((result) => { if (result.isConfirmed) { $wire.delete({{ $pemeliharaan->id }}) } });">
                                            <i class="ti ti-trash text-base"></i>
                                        </button>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-slate-100 bg-slate-50/30">
                {{ $pemeliharaans->links() }}
            </div>
        @endif
    </div>
</div>
