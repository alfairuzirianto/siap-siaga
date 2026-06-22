<div>
    <x-page-header 
        title="Detail Pengajuan" 
        subtitle="Pengajuan peminjaman: {{ $peminjaman->nomor_peminjaman }}"
        :breadcrumbs="auth()->user()->isAdmin() ? [
            ['label' => 'Peminjaman', 'url' => route('peminjaman.index')],
            ['label' => $peminjaman->nomor_peminjaman]
        ] : []"
    />

    {{-- Cek Konteks untuk Menyesuaikan UI --}}
    @php
        $hasRightPanel = 
            ($peminjaman->status === App\Models\Peminjaman::PINJAM_DIAJUKAN && auth()->user()->isSupervisor()) ||
            ($peminjaman->status === App\Models\Peminjaman::KEMBALI_DIAJUKAN && auth()->user()->isSupervisor()) ||
            ($peminjaman->status === App\Models\Peminjaman::PINJAM_DISETUJUI && auth()->user()->isAdmin()) ||
            ($peminjaman->status === App\Models\Peminjaman::KEMBALI_DISETUJUI && auth()->user()->isAdmin()) ||
            (in_array($peminjaman->status, [App\Models\Peminjaman::DIPINJAM, App\Models\Peminjaman::KEMBALI_DITOLAK]) && auth()->id() === $peminjaman->pengguna_id) ||
            ($peminjaman->beritaAcara->where('is_valid', true)->isNotEmpty() && ! auth()->user()->isPengguna());
    @endphp

    <div class="grid grid-cols-1 {{ $hasRightPanel ? 'lg:grid-cols-3' : 'max-w-4xl mx-auto' }} gap-6 items-start">
        {{-- ==============================
             PANEL DETAIL PEMINJAMAN
             ============================== --}}
        <div class="{{ $hasRightPanel ? 'lg:col-span-1 space-y-6' : 'w-full grid grid-cols-1 md:grid-cols-2 gap-6' }}">
            {{-- Card Detail Peminjaman --}}
            <div class="card bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-2.5">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Detail</h3>
                    <x-badge-status :status="$peminjaman->status" type="pengajuan" />
                </div>

                <div class="space-y-3 text-sm">
                    <div>
                        <span class="text-xs text-slate-400 block font-medium">Nama Pemohon</span>
                        <span class="font-bold text-slate-800">{{ $peminjaman->pengguna?->nama_lengkap }}</span>
                        <span class="text-xs text-slate-400 block mt-0.5">NIP: {{ $peminjaman->pengguna?->nip ?: '—' }}</span>
                    </div>

                    <div class="border-t border-slate-50 pt-2">
                        <span class="text-xs text-slate-400 block font-medium">Keperluan</span>
                        <p class="font-medium text-slate-700 leading-snug mt-0.5">{{ $peminjaman->tujuan_keperluan }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-2 border-t border-slate-50 pt-2">
                        <div>
                            <span class="text-xs text-slate-400 block font-medium">Rencana Pinjam</span>
                            <span class="font-semibold text-slate-700 block mt-0.5"><i class="ti ti-calendar mr-1 text-slate-400"></i>{{ $peminjaman->tgl_rencana_pinjam?->format('d M Y') }}</span>
                        </div>
                        <div>
                            <span class="text-xs text-slate-400 block font-medium">Rencana Kembali</span>
                            <span class="font-semibold text-slate-700 block mt-0.5"><i class="ti ti-calendar-off mr-1 text-slate-400"></i>{{ $peminjaman->tgl_rencana_kembali?->format('d M Y') }}</span>
                        </div>
                    </div>

                    @if($peminjaman->approverPinjam)
                        <div class="border-t border-slate-50 pt-2 bg-slate-50/50 p-2.5 rounded-xl border border-slate-100">
                            <span class="text-[10px] text-slate-400 block uppercase font-bold tracking-wide">Divalidasi Oleh</span>
                            <span class="text-xs font-bold text-slate-700 block mt-0.5">{{ $peminjaman->approverPinjam->nama_lengkap }}</span>
                            @if($peminjaman->keterangan_pinjam)
                                <span class="text-[10px] block uppercase font-bold tracking-wide mt-4
                                    {{ match($peminjaman->status) {
                                        App\Models\Peminjaman::PINJAM_DISETUJUI => 'text-green-500',
                                        App\Models\Peminjaman::DIPINJAM => 'text-green-500',
                                        App\Models\Peminjaman::SELESAI => 'text-green-500',
                                        App\Models\Peminjaman::KEMBALI_DIAJUKAN => 'text-green-500',
                                        App\Models\Peminjaman::KEMBALI_DISETUJUI => 'text-green-500',
                                        App\Models\Peminjaman::KEMBALI_DITOLAK => 'text-green-500',
                                        App\Models\Peminjaman::PINJAM_DITOLAK => 'text-rose-500',
                                        default => 'text-slate-600'
                                    } }}">
                                    Catatan Validasi Peminjaman
                                </span>
                                <p class="text-xs text-slate-600 italic mt-0.5 font-normal">{{ $peminjaman->keterangan_pinjam }}</p>
                            @endif
                            @if($peminjaman->keterangan_kembali)
                                <span class="text-[10px] block uppercase font-bold tracking-wide mt-4
                                    {{ match($peminjaman->status) {
                                        App\Models\Peminjaman::KEMBALI_DISETUJUI => 'text-green-500',
                                        App\Models\Peminjaman::SELESAI => 'text-green-500',
                                        App\Models\Peminjaman::KEMBALI_DITOLAK => 'text-rose-500',
                                        App\Models\Peminjaman::KEMBALI_DIAJUKAN => 'text-rose-500',
                                        default => 'text-slate-600'
                                    } }}">
                                    Catatan Validasi Pengembalian
                                </span>
                                <p class="text-xs text-slate-600 italic mt-0.5 font-normal">{{ $peminjaman->keterangan_kembali }}</p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
            {{-- Card Daftar Peralatan yang Dipinjam --}}
            <div class="card bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-3">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100 pb-2">Daftar Peralatan ({{ $peminjaman->details->count() }} Unit)</h3>
                <div class="divide-y divide-slate-50">
                    @foreach($peminjaman->details as $detail)
                        <div class="py-2 flex items-center justify-between first:pt-0 last:pb-0">
                            <div class="min-w-0">
                                <span class="font-bold text-slate-800 text-sm block">{{ $detail->peralatan?->nomor_seri }}</span>
                                <span class="text-xs text-slate-400 block">{{ $detail->peralatan?->jenis?->nama_jenis }}</span>
                            </div>
                            <x-badge-status :status="$detail->peralatan?->status" type="alat" />
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        {{-- ==============================
             PANEL AKSI
             ============================== --}}
        @if($hasRightPanel)
        <div class="lg:col-span-2 space-y-6">
            {{-- Validasi Supervisor --}}
            @if(in_array($peminjaman->status, [App\Models\Peminjaman::PINJAM_DIAJUKAN, App\Models\Peminjaman::KEMBALI_DIAJUKAN]) && auth()->user()->isSupervisor())
                <div class="card bg-white rounded-2xl border border-blue-200 shadow-md p-6 text-center space-y-4">
                    <div class="w-12 h-12 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center mx-auto">
                        <i class="ti ti-shield-lock text-2xl"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-800 uppercase tracking-wider">
                            {{ $peminjaman->status === App\Models\Peminjaman::PINJAM_DIAJUKAN ? 'Validasi Peminjaman' : 'Validasi Pengembalian' }}
                        </h4>
                        <p class="text-xs text-slate-500 mt-1">Periksa rincian pengajuan sebelum konfirmasi validasi.</p>
                    </div>
                    {{-- Preview Dokumentasi Pengembalian --}}
                    @if($peminjaman->status === App\Models\Peminjaman::KEMBALI_DIAJUKAN)
                        @foreach($peminjaman->beritaAcara()->where('jenis_ba', App\Models\BeritaAcara::BA_PENGEMBALIAN)->where('is_valid', false)->get() as $draft)
                            <div class="text-left bg-slate-50 border border-slate-200 p-4 rounded-xl space-y-3">
                                <span class="text-[10px] font-bold text-amber-600 bg-amber-50 px-2 py-0.5 rounded border border-amber-200 uppercase">Dokumentasi Unit dari Pengguna</span>
                                @foreach($draft->dokumentasi as $doc)
                                    <p class="text-xs text-slate-600 mt-1">"{{ $doc->keterangan }}"</p>
                                    <div class="grid grid-cols-4 gap-2">
                                        @foreach($doc->foto as $img)
                                            <div class="min-h-24 rounded-lg overflow-hidden border border-slate-200"><img src="{{ asset('storage/' . $img) }}" class="w-full h-full object-cover"></div>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    @endif
                    <div class="flex items-center justify-center gap-3 pt-2">
                        <button wire:click="openModal('{{ $peminjaman->status === App\Models\Peminjaman::PINJAM_DIAJUKAN ? App\Models\Peminjaman::PINJAM_DISETUJUI : App\Models\Peminjaman::KEMBALI_DISETUJUI }}')"
                                class="px-5 py-2 bg-green-600 hover:bg-green-700 text-white text-xs font-bold rounded-xl transition-colors">
                            Validasi
                        </button>
                        <button wire:click="openModal('{{ $peminjaman->status === App\Models\Peminjaman::PINJAM_DIAJUKAN ? App\Models\Peminjaman::PINJAM_DITOLAK : App\Models\Peminjaman::KEMBALI_DITOLAK }}')"
                                class="px-5 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-xl transition-colors">
                            Tolak
                        </button>
                    </div>
                </div>
            @endif

            {{-- Pembuatan BA Peminjaman oleh Admin --}}
            @if($peminjaman->status === App\Models\Peminjaman::PINJAM_DISETUJUI && auth()->user()->isAdmin())
                <div class="card bg-white rounded-2xl border border-primary-200 shadow-md overflow-hidden">
                    <div class="px-6 py-4 border-b border-primary-100 bg-primary-50/40">
                        <h3 class="text-sm font-bold text-primary-800 uppercase tracking-wider">Terbitkan Berita Acara Peminjaman</h3>
                    </div>
                    <form wire:submit.prevent="generateBAPinjam" class="p-6 space-y-5">
                        @foreach($dokumentasiItems as $index => $item)
                            <div class="p-4 border border-slate-200 rounded-xl bg-slate-50/50 space-y-4" wire:key="ba-block-{{ $index }}">
                                <div class="flex items-center gap-2 border-b border-slate-100 pb-2">
                                    <span class="font-bold text-slate-800 text-sm">{{ $item['nomor_seri'] }}</span>
                                    <span class="text-xs text-slate-400">({{ $item['nama_jenis'] }})</span>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div class="space-y-1">
                                        <label class="text-[11px] font-bold text-slate-600 uppercase block">Catatan Kondisi</label>
                                        <textarea wire:model="dokumentasiItems.{{ $index }}.keterangan" rows="4" class="form-input rounded-xl text-xs"></textarea>
                                    </div>
                                    <div class="space-y-2 md:col-span-2">
                                        <label class="text-[11px] font-bold text-slate-600 uppercase block">Lampiran Gambar Fisik</label>
                                        <div class="grid grid-cols-4 gap-2">
                                            <label class="flex flex-col items-center justify-center h-20 border-2 border-dashed border-slate-300 rounded-xl cursor-pointer bg-white">
                                                <i class="ti ti-camera text-slate-400 text-lg"></i>
                                                <input type="file" wire:model="tempFotos.{{ $index }}" multiple class="hidden" accept="image/*">
                                            </label>
                                            @foreach($item['foto_paths'] as $fIndex => $path)
                                                <div class="relative h-20 rounded-xl overflow-hidden border border-slate-200">
                                                    <img src="{{ asset('storage/' . $path) }}" class="w-full h-full object-cover">
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <div class="flex items-center justify-end pt-2">
                            <button type="submit" class="px-5 py-2.5 text-xs font-medium text-white bg-primary-600 rounded-xl shadow-sm">
                                Terbitkan BA
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            {{-- Pengajuan Pengembalian oleh Pengguna --}}
            @if(in_array($peminjaman->status, [App\Models\Peminjaman::DIPINJAM, App\Models\Peminjaman::KEMBALI_DITOLAK]) && auth()->id() === $peminjaman->pengguna_id)
                <div class="card bg-white rounded-2xl border border-primary-200 shadow-md overflow-hidden">
                    <div class="px-6 py-4 border-b border-primary-100 bg-primary-50/40">
                        <h3 class="text-sm font-bold text-primary-800 uppercase tracking-wider">
                            {{ $peminjaman->status === App\Models\Peminjaman::KEMBALI_DITOLAK ? 'Revisi Laporan Pengembalian' : 'Formulir Pengembalian' }}
                        </h3>
                    </div>
                    <form wire:submit.prevent="ajukanPengembalian" class="p-6 space-y-5">
                        @foreach($dokumentasiItems as $index => $item)
                            <div class="p-4 border border-slate-200 rounded-xl bg-slate-50/50 space-y-4" wire:key="return-block-{{ $index }}">
                                <div class="flex items-center gap-2 border-b border-slate-100 pb-2">
                                    <span class="font-bold text-slate-800 text-sm">{{ $item['nomor_seri'] }}</span>
                                    <span class="text-xs text-slate-400">({{ $item['nama_jenis'] }})</span>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div class="space-y-1">
                                        <label class="text-[11px] font-bold text-slate-600 uppercase block">Kondisi</label>
                                        <textarea wire:model="dokumentasiItems.{{ $index }}.keterangan" rows="4" class="form-input rounded-xl text-xs"></textarea>
                                    </div>
                                    <div class="space-y-2 md:col-span-2">
                                        <label class="text-[11px] font-bold text-slate-600 uppercase block">Lampiran Bukti</label>
                                        <div class="grid grid-cols-4 gap-2">
                                            <label class="flex flex-col items-center justify-center h-20 border-2 border-dashed border-slate-300 rounded-xl cursor-pointer bg-white">
                                                <i class="ti ti-camera text-slate-400 text-lg"></i>
                                                <input type="file" wire:model="tempFotos.{{ $index }}" multiple class="hidden" accept="image/*">
                                            </label>
                                            @foreach($item['foto_paths'] as $fIndex => $path)
                                                <div class="relative h-20 rounded-xl overflow-hidden border border-slate-200">
                                                    <img src="{{ asset('storage/' . $path) }}" class="w-full h-full object-cover">
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <div class="flex items-center justify-end pt-2">
                            <button type="submit" class="px-5 py-2.5 text-xs font-bold text-white bg-primary-600 shadow-sm rounded-xl">
                                Kirim Ulang Laporan Pengembalian
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            {{-- Pembuatan BA Pengembalian oleh Admin --}}
            @if($peminjaman->status === App\Models\Peminjaman::KEMBALI_DISETUJUI && auth()->user()->isAdmin())
                <div class="card bg-white rounded-2xl border border-green-200 shadow-md p-6 text-center space-y-4">
                    <div class="w-12 h-12 rounded-full bg-green-50 text-green-600 flex items-center justify-center mx-auto">
                        <i class="ti ti-file-certificate text-2xl"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Terbitkan Berita Acara Pengembalian</h4>
                        <p class="text-xs text-slate-500 mt-1">Persetujuan sirkulasi balik disahkan oleh Supervisor. Selesaikan berkas arsip resmi.</p>
                    </div>
                    <button wire:click="generateBAKembali" class="px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white text-xs font-bold rounded-xl mx-auto block">
                        Terbitkan BA
                    </button>
                </div>
            @endif

            {{-- Arsip Berita Acara Valid --}}
            @if($peminjaman->beritaAcara->where('is_valid', true)->isNotEmpty() && auth()->user()->hasRole('Admin', 'Supervisor'))
                <div class="card bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-3">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100 pb-2">Berita Acara</h3>
                    <div class="divide-y divide-slate-50">
                        @foreach($peminjaman->beritaAcara->where('is_valid', true) as $ba)
                            <div class="py-2.5 flex items-center justify-between">
                                <div>
                                    <span class="font-bold text-slate-800 text-sm block">Berita Acara {{ $ba->jenis_ba }}</span>
                                    <span class="text-xs font-mono text-slate-400">{{ $ba->nomor_ba }}</span>
                                </div>
                                <div class="md:flex gap-3">
                                    <a href="{{ route('ba.download', $ba) }}" class="inline-flex items-center gap-1 text-xs font-bold text-primary-600 hover:underline">
                                        <i class="ti ti-download"></i> Unduh
                                    </a>
                                    @if(auth()->user()->isAdmin())
                                        <button class="text-xs font-bold text-red-600 hover:underline flex items-center gap-0.5 outline-none focus:outline-none"
                                                x-on:click="
                                                    Swal.fire({
                                                        title: 'Hapus Berita Acara?',
                                                        text: 'Berita acara ini akan dihapus dan akan diterbitkan ulang.',
                                                        icon: 'warning',
                                                        showCancelButton: true,
                                                        confirmButtonColor: '#dc2626',
                                                        cancelButtonColor: '#6b7280',
                                                        confirmButtonText: 'Ya, Hapus!',
                                                        cancelButtonText: 'Batal'
                                                    }).then((result) => {
                                                        if (result.isConfirmed) { 
                                                            $wire.regenerateBA({{ $ba->id }})
                                                        }
                                                    });
                                                ">
                                            <i class="ti ti-refresh"></i> Terbitkan Ulang
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
        @endif
    </div>
    
    {{-- Konfirmasi Validasi Supervisor --}}
    @if($isApprovalModalOpen)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="fixed inset-0 bg-black/40 backdrop-blur-xs" wire:click="$set('isApprovalModalOpen', false)"></div>
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative transform overflow-hidden rounded-2xl bg-white p-6 shadow-2xl transition-all w-full max-w-md text-left">
                    
                    @php
                        $isApproval = in_array($targetStatus, [App\Models\Peminjaman::PINJAM_DISETUJUI, App\Models\Peminjaman::KEMBALI_DISETUJUI]);
                        $isFasePinjam = in_array($targetStatus, [App\Models\Peminjaman::PINJAM_DISETUJUI, App\Models\Peminjaman::PINJAM_DITOLAK]);
                        
                        $modalTitle = $isApproval 
                            ? ($isFasePinjam ? 'Persetujuan Peminjaman' : 'Persetujuan Pengembalian')
                            : ($isFasePinjam ? 'Penolakan Peminjaman' : 'Penangguhan Pengembalian');
                            
                        $buttonColor = $isApproval 
                            ? 'bg-green-600 hover:bg-green-700' 
                            : 'bg-red-600 hover:bg-red-700';
                    @endphp

                    <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-3">
                        Konfirmasi {{ $modalTitle }}
                    </h3>
                    
                    <div class="space-y-4">
                        <div class="space-y-1.5">
                            <label class="text-xs text-slate-400 font-medium">
                                Catatan
                            </label>
                            <textarea wire:model="keterangan" rows="3" 
                                      class="form-input rounded-xl border-slate-200 text-sm w-full focus:border-primary-500" 
                                      placeholder="{{ $isApproval ? 'Tulis catatan persetujuan jika ada...' : 'Tulis alasan penolakan/penangguhan secara jelas...' }}"></textarea>
                        </div>
                        
                        <div class="flex justify-end gap-2.5">
                            <button type="button" wire:click="$set('isApprovalModalOpen', false)" class="btn-ghost">Batal</button>
                            <button type="button" wire:click="validasi" 
                                    class="px-4 py-2 text-xs font-medium text-white rounded-xl transition-colors shadow-sm outline-none focus:outline-none {{ $buttonColor }}">
                                {{ $isApproval ? 'Validasi' : 'Tolak' }}
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    @endif
</div>