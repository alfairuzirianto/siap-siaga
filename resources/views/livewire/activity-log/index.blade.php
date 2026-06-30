<div>
    <x-page-header 
        title="Audit Trail Log Aktivitas"
        subtitle="Rekam jejak digital menyeluruh terhadap manipulasi data, otentikasi, dan validasi berkas sirkulasi"
        :breadcrumbs="[['label' => 'Log Aktivitas']]"
    />

    <div class="card bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <!-- Pencarian & Filter bar -->
        <div class="p-4 bg-slate-50/60 border-b border-slate-100 flex flex-col sm:flex-row items-center gap-3 justify-between">
            <div class="relative flex-1">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                    <i class="ti ti-search text-base"></i>
                </span>
                <input type="text" wire:model.live.debounce.300ms="search" 
                       class="form-input rounded-xl border-slate-200 pl-9 text-sm py-2" placeholder="Cari log">
            </div>

            <div class="sm:w-48">
                <select wire:model.live="filterAksi" class="form-input rounded-xl border-slate-200 text-sm py-2">
                    <option value="">-- Semua Aksi --</option>
                    @foreach(App\Models\ActivityLog::AKSI_USER as $aksiItem)
                        <option value="{{ $aksiItem }}">{{ $aksiItem }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        @if($logs->isEmpty())
            <div class="p-12">
                <x-empty-state icon="ti-history" title="Log kosong" message="Tidak ditemukan rekaman jejak log data yang sesuai." />
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 text-xs font-bold uppercase text-slate-400 bg-slate-50/30 tracking-wider">
                            <th class="py-3.5 px-6">Waktu Kejadian</th>
                            <th class="py-3.5 px-4">Aktor</th>
                            <th class="py-3.5 px-4">Tindakan</th>
                            <th class="py-3.5 px-4">Nama Modul</th>
                            <th class="py-3.5 px-4">ID Record</th>
                            <th class="py-3.5 px-6 text-center">Metadata</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                        @foreach($logs as $log)
                            <tr class="hover:bg-slate-50/40 transition-colors" wire:key="log-row-{{ $log->id }}">
                                <td class="py-3.5 px-6 whitespace-nowrap text-xs text-slate-500">
                                    {{ formatTanggal($log->created_at, 'd M Y') }}
                                    <br>
                                    {{ formatTanggal($log->created_at, 'H:i') }} WIB
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <span class="text-slate-800 font-semibold block">{{ $log->user?->nama_lengkap ?? 'Sistem Otomatis' }}</span>
                                    <span class="text-xs text-slate-400 font-normal">{{ $log->user?->username }}</span>
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <span class="inline-flex items-center rounded-md px-2 py-0.5 text-xs font-bold ring-1 ring-inset
                                        {{ match($log->aksi) {
                                            'Create' => 'bg-green-50 text-green-700 ring-green-600/10',
                                            'Update' => 'bg-amber-50 text-amber-700 ring-amber-600/10',
                                            'Delete' => 'bg-red-50 text-red-700 ring-red-600/10',
                                            'Menyetujui' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/10',
                                            'Menolak' => 'bg-rose-50 text-rose-700 ring-rose-600/10',
                                            default => 'bg-slate-50 text-slate-700 ring-slate-600/10'
                                        } }}">
                                        {{ $log->aksi }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap text-xs text-slate-600 capitalize">
                                    {{ str_replace('_', ' ', $log->nama_table) }}
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap text-xs text-slate-500">
                                    #{{ $log->record_id }}
                                </td>
                                <td class="py-3.5 px-6 whitespace-nowrap text-center">
                                    <a href="{{ route('activity-logs.show', $log) }}" wire:navigate 
                                       class="inline-flex items-center gap-1 px-3 py-1 bg-slate-100 hover:bg-slate-200/80 text-slate-600 text-xs rounded-xl font-medium transition-all outline-none focus:outline-none focus-visible:outline-none">
                                        <i class="ti ti-eye text-sm"></i> Lihat Rincian
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-slate-100 bg-slate-50/30">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>