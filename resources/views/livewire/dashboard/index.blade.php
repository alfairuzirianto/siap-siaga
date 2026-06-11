<div>
    <x-page-header
        title="Dashboard"
        subtitle="Selamat datang, {{ auth()->user()->nama_lengkap }}" />

    <div class="space-y-6">

        {{-- Stat Cards --}}
        @if (auth()->user()->isAdmin())
            
        @endif
        <div class="grid grid-cols-2 lg:grid-cols-3 gap-4">
            <x-stat-card
                label="Total Peralatan"
                :value="$data['totalEquipment']"
                icon="ti-box"
                color="blue"/>

            <x-stat-card
                label="Maintenance"
                :value="$data['maintenanceCount']"
                icon="ti-tool"
                color="orange"/>

            <x-stat-card
                label="Peminjaman Aktif"
                :value="$data['activeBorrow']"
                icon="ti-file-description"
                color="yellow"/>
        </div>

        @if(auth()->user()->isSupervisor())
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <x-stat-card
                label="Menunggu Approval"
                :value="$data['pendingApprovals']"
                icon="ti-shield-check"
                color="red"/>
        </div>
        @endif

        {{-- Quick Actions --}}
        @if(auth()->user()->isAdmin())
        <div class="card p-5">
            <h3 class="section-title mb-4">Aksi Cepat</h3>
            <div class="flex flex-wrap gap-3">
                <a href="" wire:navigate class="btn-primary flex items-center gap-2">
                    <i class="ti ti-plus"></i> Buat Pengajuan Pinjam
                </a>
                <a href="" wire:navigate class="btn-secondary flex items-center gap-2">
                    <i class="ti ti-box"></i> Tambah Peralatan
                </a>
                <a href="" wire:navigate class="btn-secondary flex items-center gap-2">
                    <i class="ti ti-tool"></i> Tambah Maintenance
                </a>
            </div>
        </div>
        @endif
        <!-- ============================================================
             PERUBAHAN: Section log aktivitas terakhir — hanya untuk Admin.
             Menampilkan 5 record terbaru, masing-masing dengan:
             aksi, nama user, table_name, dan waktu.
             Di bagian bawah ada link "Lihat Semua".
             ============================================================ -->
        @if(auth()->user()->isAdmin() && !empty($data['recentLogs']))
        <div class="card p-0 overflow-hidden">
 
            {{-- Header --}}
            <div class="flex items-center justify-between px-5 py-3.5 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center shrink-0">
                        <i class="ti ti-history text-slate-500 text-base"></i>
                    </div>
                    <h3 class="text-sm font-semibold text-slate-800">Log Aktivitas Terakhir</h3>
                </div>
                {{-- PERUBAHAN: Link ke halaman index log aktivitas --}}
                <a href="" wire:navigate
                   class="text-xs text-primary-600 hover:underline font-medium flex items-center gap-1">
                    Lihat Semua <i class="ti ti-arrow-right text-xs"></i>
                </a>
            </div>
 
            {{-- 5 Row Terakhir --}}
            <div class="divide-y divide-slate-100">
                @foreach($data['recentLogs'] as $log)
                {{-- PERUBAHAN: Setiap baris menampilkan aksi, user, model, dan waktu --}}
                <div class="flex items-center gap-3 px-5 py-3">
 
                    {{-- Avatar user --}}
                    <div class="w-7 h-7 rounded-full bg-primary-100 flex items-center justify-center shrink-0">
                        <span class="text-primary-700 text-[10px] font-semibold">
                            {{ strtoupper(substr($log->user?->nama ?? 'SY', 0, 2)) }}
                        </span>
                    </div>
 
                    {{-- Info log --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            {{-- Badge aksi dengan warna sesuai jenis --}}
                            @php
                            $logActionColor = match(true) {
                                str_contains(strtolower($log->aksi), 'create') || str_contains(strtolower($log->aksi), 'generate') => 'green',
                                str_contains(strtolower($log->aksi), 'update') => 'blue',
                                str_contains(strtolower($log->aksi), 'delete') => 'red',
                                str_contains(strtolower($log->aksi), 'approve') || str_contains(strtolower($log->aksi), 'reject') => 'purple',
                                default => 'slate',
                            };
                            // pengubahan kata aksi
                            $logActionDisplay = match(true) {
                                str_contains(strtolower($log->aksi), 'create') => 'Create',
                                str_contains(strtolower($log->aksi), 'update') => 'Update',
                                str_contains(strtolower($log->aksi), 'delete') => 'Delete',
                                str_contains(strtolower($log->aksi), 'approve') => 'Approve',
                                str_contains(strtolower($log->aksi), 'reject') => 'Reject',
                                str_contains(strtolower($log->aksi), 'generate') => 'Generate',
                                default => $log->aksi,
                            };
                            
                            @endphp
                            <span class="inline-flex items-center rounded-md bg-{{ $logActionColor }}-50 px-2 py-0.5 text-xs font-medium text-{{ $logActionColor }}-700 ring-1 ring-inset ring-{{ $logActionColor }}-600/10">
                                {{ $logActionDisplay }}
                            </span>
                            <span class="text-xs text-slate-600">{{ $log->user?->nama ?? 'System' }}</span>
                            @if($log->table_name)
                            <span class="text-xs text-slate-400">· {{ $log->table_name }}</span>
                            @endif
                        </div>
                    </div>
 
                    {{-- Waktu --}}
                    <span class="text-xs text-slate-400 shrink-0 whitespace-nowrap">
                        {{ $log->created_at->diffForHumans() }}
                    </span>
                </div>
                @endforeach
            </div>
 
            {{-- Footer link --}}
            <div class="px-5 py-3 border-t border-slate-100 bg-slate-50">
                <a href="" wire:navigate
                   class="text-sm text-primary-600 hover:underline font-medium flex items-center gap-1">
                    <i class="ti ti-history text-sm"></i>
                    Lihat seluruh riwayat aktivitas
                </a>
            </div>
 
        </div>
        @endif

    </div>
</div>
