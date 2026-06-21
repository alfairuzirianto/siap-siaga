<div>
    <x-page-header title="Rincian Log Aktivitas #{{ $log->id }}" subtitle="Inspeksi metadata perubahan data mentah JSON di dalam database"
        :breadcrumbs="[['label' => 'Log Aktivitas', 'url' => route('activity-logs.index')], ['label' => 'Detail #' . $log->id]]">
        <a href="{{ route('activity-logs.index') }}" wire:navigate 
           class="btn-ghost">
            <i class="ti ti-arrow-left text-base"></i>
            <span>Kembali</span>
        </a>
    </x-page-header>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
        
        <!-- KOLOM KIRI: RINGKASAN DATA OPERATOR -->
        <div class="lg:col-span-1 space-y-6">
            <div class="card bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-4">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100 pb-2">Informasi Aktivitas</h3>
                
                <div class="space-y-3 text-sm">
                    <div>
                        <span class="text-xs text-slate-400 block font-medium">Aktor</span>
                        <span class="font-bold text-slate-800">{{ $log->user?->nama_lengkap ?? 'Sistem Otomatis' }}</span>
                    </div>
                    <div>
                        <span class="text-xs text-slate-400 block font-medium">Username</span>
                        <span class="text-xs text-slate-600 bg-slate-50 px-2 py-0.5 rounded-md inline-block mt-0.5">{{ $log->user?->username ?? 'system' }}</span>
                    </div>
                    <div class="border-t border-slate-50 pt-2">
                        <span class="text-xs text-slate-400 block font-medium">Waktu Kejadian</span>
                        <span class="font-medium text-slate-700 block mt-0.5"><i class="ti ti-calendar-time text-slate-400 mr-1"></i>{{ formatTanggal($log->created_at, 'd F Y, H:i:s') }} WIB</span>
                    </div>
                    <div class="border-t border-slate-50 pt-2">
                        <span class="text-xs text-slate-400 block font-medium">Tindakan</span>
                        <div class="mt-1">
                            <span class="inline-flex items-center rounded-md px-2.5 py-0.5 text-xs font-bold ring-1 ring-inset
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
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2 border-t border-slate-50 pt-2">
                        <div>
                            <span class="text-xs text-slate-400 block font-medium">Modul Tabel</span>
                            <span class="font-semibold text-slate-700 text-xs capitalize">{{ str_replace('_', ' ', $log->nama_table) }}</span>
                        </div>
                        <div>
                            <span class="text-xs text-slate-400 block font-medium">Record ID</span>
                            <span class="font-semibold text-slate-700 text-xs">#{{ $log->record_id }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- KOLOM KANAN: PERBANDINGAN METADATA JSON UTUH -->
        <div class="lg:col-span-2 space-y-4">
            <div class="card bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-5">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100 pb-2">Komparasi Payload Objek Database</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Old Value -->
                    <div class="space-y-1.5">
                        <span class="text-xs font-bold text-red-600 uppercase tracking-wide bg-red-50 px-2.5 py-0.5 rounded-md inline-block">Data Sebelum Aksi (Old Value)</span>
                        <pre class="p-4 bg-slate-900 text-slate-300 text-xs rounded-xl overflow-x-auto max-h-[500px] leading-relaxed"><code>{{ $log->old_value ? json_encode($log->old_value, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : '{}' }}</code></pre>
                    </div>
                    
                    <!-- New Value -->
                    <div class="space-y-1.5">
                        <span class="text-xs font-bold text-green-700 uppercase tracking-wide bg-green-50 px-2.5 py-0.5 rounded-md inline-block">Data Sesudah Aksi (New Value)</span>
                        <pre class="p-4 bg-slate-900 text-slate-300 text-xs rounded-xl overflow-x-auto max-h-[500px] leading-relaxed"><code>{{ $log->new_value ? json_encode($log->new_value, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : '{}' }}</code></pre>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
