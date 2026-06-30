<div>
    <x-page-header
        title="Dashboard"
        subtitle="Selamat datang, {{ auth()->user()->nama_lengkap }}" />

    <div class="space-y-6">
        {{-- Stat Card --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 lg:grid-cols-3">
            <x-stat-card
                label="Total Peralatan"
                :value="$data['totalPeralatan']"
                icon="ti-box"
                color="blue"/>
            <x-stat-card
                label="Riwayat Pemeliharaan"
                :value="$data['totalPemeliharaan']"
                icon="ti-tool"
                color="orange"/>
            <x-stat-card
                label="Peminjaman Aktif"
                :value="$data['peminjamanAktif']"
                icon="ti-file-description"
                color="yellow"/>
        </div>

        {{-- Quick Actions --}}
        <div class="card p-5 bg-white border border-slate-200 rounded-2xl shadow-sm">
            <h3 class="text-sm font-semibold text-slate-800 mb-4 font-display">Aksi Cepat</h3>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <a href="{{ route('peminjaman.index') }}" wire:navigate class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-xs font-bold rounded-xl transition-colors shadow-xs">
                    <i class="ti ti-file-description"></i> Kelola Peminjaman
                </a>
                <a href="{{ route('peralatan.create') }}" wire:navigate class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-colors">
                    <i class="ti ti-box"></i> Tambah Peralatan
                </a>
                <a href="{{ route('pemeliharaan.create') }}" wire:navigate class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-colors">
                    <i class="ti ti-tool"></i> Catat Pemeliharaan
                </a>
            </div>
        </div>

        {{-- Log Aktivitas --}}
        @if(!empty($data['recentLogs']))
        <div class="card p-0 overflow-hidden">
            <div class="flex items-center justify-between px-5 py-3.5 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center shrink-0">
                        <i class="ti ti-history text-slate-500 text-base"></i>
                    </div>
                    <h3 class="text-sm font-semibold text-slate-800">Aktivitas Terakhir</h3>
                </div>
                <a href="" wire:navigate
                class="text-xs text-primary-600 hover:underline font-medium flex items-center gap-1">
                    Lihat Semua <i class="ti ti-arrow-right text-xs"></i>
                </a>
            </div>

            <div class="divide-y divide-slate-100">
                @foreach($data['recentLogs'] as $log)
                <div class="flex items-center gap-3 px-5 py-3">
                    <div class="w-7 h-7 rounded-full bg-primary-100 flex items-center justify-center shrink-0">
                        <span class="text-primary-700 text-[10px] font-semibold">
                            {{ strtoupper(substr($log->user?->nama_lengkap ?? '##', 0, 2)) }}
                        </span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="text-xs text-slate-600">{{ $log->user?->nama_lengkap ?? 'System' }}</span>
                            <span class="inline-flex items-center rounded-md px-2 py-0.5 text-xs font-medium ring-1 ring-inset 
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
                            @if($log->nama_table)
                            <span class="text-xs text-slate-400">· {{ $log->nama_table }}</span>
                            @endif
                        </div>
                    </div>
                    <span class="text-xs text-slate-400 shrink-0 whitespace-nowrap">
                        {{ $log->created_at->diffForHumans() }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>