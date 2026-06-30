<div>
    <x-page-header 
        title="Detail Pengajuan" 
        subtitle="Pengajuan peminjaman: {{ $peminjaman->nomor_peminjaman }}"
        :breadcrumbs="[
            ['label' => 'Peminjaman', 'url' => route('peminjaman.index')],
            ['label' => $peminjaman->nomor_peminjaman]
        ]"
    />

    {{-- Cek Konteks untuk Menyesuaikan UI --}}
    @php
        $hasRightPanel = 
            ($peminjaman->status === App\Models\Peminjaman::DIPINJAM) ||
            ($peminjaman->beritaAcara->where('is_valid', true)->isNotEmpty());
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
                        <span class="text-xs text-slate-400 block font-medium">Pengguna</span>
                        <span class="font-bold text-slate-800">{{ $peminjaman->nama_pengguna }}</span>
                        <span class="text-xs text-slate-400 block mt-0.5">NIP: {{ $peminjaman->nip? : '—' }}</span>
                    </div>

                    <div class="border-t border-slate-50 pt-2">
                        <span class="text-xs text-slate-400 block font-medium">Keperluan</span>
                        <p class="font-medium text-slate-700 leading-snug mt-0.5">{{ $peminjaman->tujuan_keperluan }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-2 border-t border-slate-50 pt-2">
                        <div>
                            <span class="text-xs text-slate-400 block font-medium">Tanggal Pinjam</span>
                            <span class="font-semibold text-slate-700 block mt-0.5"><i class="ti ti-calendar mr-1 text-slate-400"></i>{{ $peminjaman->tgl_pinjam?->format('d M Y') }}</span>
                        </div>
                        <div>
                            <span class="text-xs text-slate-400 block font-medium">Tanggal Kembali</span>
                            <span class="font-semibold text-slate-700 block mt-0.5"><i class="ti ti-calendar-off mr-1 text-slate-400"></i>{{ $peminjaman->tgl_kembali?->format('d M Y') }}</span>
                        </div>
                    </div>
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
            {{-- CASE 1: Status 'Sedang Dipinjam' dan BELUM membuat BA Peminjaman --}}
            @if($peminjaman->status === App\Models\Peminjaman::DIPINJAM && $peminjaman->beritaAcara->where('jenis_ba', App\Models\BeritaAcara::BA_PEMINJAMAN)->isEmpty())
                <div class="card bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                    <h3 class="text-sm font-semibold text-slate-800 mb-4 flex items-center gap-2">
                        <i class="ti ti-file-description text-primary-500 text-lg"></i>
                        Penerbitan BA Peminjaman
                    </h3>
                    
                    <form wire:submit.prevent="generateBAPinjam" class="space-y-4">
                        @foreach($dokumentasiItems as $index => $item)
                            <div class="p-4 bg-slate-50 rounded-xl border border-slate-100 space-y-3">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h4 class="text-xs font-bold text-slate-700">{{ $item['nama_jenis'] }}</h4>
                                        {{-- Menampilkan seluruh nomor seri unit yang berada di dalam jenis alat ini --}}
                                        <p class="text-[11px] text-slate-400">Unit S/N: {{ $item['nomor_seri'] }}</p>
                                    </div>
                                </div>

                                {{-- Input Keterangan --}}
                                <div>
                                    <label class="text-[11px] font-medium text-slate-400 block mb-1">Keterangan Kondisi Jenis</label>
                                    <input type="text" wire:model="dokumentasiItems.{{ $index }}.keterangan" 
                                        class="form-input text-xs w-full rounded-xl border-slate-200 focus:border-primary-500">
                                    @error("dokumentasiItems.{$index}.keterangan") <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                {{-- Upload Foto Dokumentasi --}}
                                <div>
                                    <label class="text-[11px] font-medium text-slate-400 block mb-1">Foto Serah Terima (Maks. 2MB)</label>
                                    <input type="file" wire:model="tempFotos.{{ $index }}" multiple accept="image/*"
                                        class="text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
                                    
                                    <div wire:loading wire:target="tempFotos.{{ $index }}" class="mt-2 w-full">
                                        <div class="flex items-center gap-2 text-xs text-primary-600 font-medium">
                                            <svg class="animate-spin h-4 w-4 text-primary-600" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            <span>Sedang memproses gambar...</span>
                                        </div>
                                    </div>
                                    
                                    @error("tempFotos.{$index}.*") 
                                        <span class="text-xs text-red-500 mt-1.5 block font-medium">
                                            <i class="ti ti-alert-circle mr-0.5"></i> {{ $message }}
                                        </span> 
                                    @enderror
                                    
                                    {{-- Preview Foto --}}
                                    @if(!empty($item['foto_paths']))
                                        <div class="grid grid-cols-4 gap-2 mt-3">
                                            @foreach($item['foto_paths'] as $fIndex => $path)
                                                <div class="relative h-28 rounded-lg overflow-hidden border border-slate-200 group">
                                                    <img src="{{ asset('storage/' . $path) }}" class="w-full h-full object-cover">
                                                    <button type="button" 
                                                            wire:click="removeFoto({{ $index }}, {{ $fIndex }})"
                                                            class="absolute top-1 right-1 bg-red-600 text-white rounded-full p-0.5 shadow-md hover:bg-red-700 opacity-90 transition-all">
                                                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                    @error("dokumentasiItems.{$index}.foto_paths") <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        @endforeach

                        <button type="submit" wire:loading.attr="disabled" wire:target="tempFotos, generateBAPinjam"
                                class="w-full flex justify-center items-center gap-2 px-4 py-2.5 text-xs font-medium text-white bg-primary-600 hover:bg-primary-700 shadow-sm rounded-xl transition-colors">
                            <i class="ti ti-device-floppy text-base" wire:loading.remove wire:target="tempFotos, generateBAPinjam"></i>
                            <svg wire:loading wire:target="tempFotos, generateBAPinjam" class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span wire:loading.remove wire:target="tempFotos, generateBAPinjam">Terbitkan BA Peminjaman</span>
                            <span wire:loading wire:target="tempFotos">Mengunggah Foto...</span>
                            <span wire:loading wire:target="generateBAPinjam" wire:loading.remove wire:target="tempFotos">Memproses Berita Acara...</span>
                        </button>
                    </form>
                </div>

            {{-- CASE 2: Status 'Sedang Dipinjam' dan SUDAH terbit BA Peminjaman (Admin Menyelesaikan & Mengembalikan Alat) --}}
            @elseif($peminjaman->status === App\Models\Peminjaman::DIPINJAM && $peminjaman->beritaAcara->where('jenis_ba', App\Models\BeritaAcara::BA_PEMINJAMAN)->isNotEmpty())
                <div class="card bg-white rounded-2xl border border-slate-200 p-6 shadow-sm text-center space-y-4">
                    <div class="mx-auto w-12 h-12 bg-orange-50 text-orange-500 rounded-full flex items-center justify-center text-xl">
                        <i class="ti ti-clock-play"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-slate-800">Peminjaman Sedang Berjalan</h3>
                        <p class="text-xs text-slate-400 mt-1">Alat sedang digunakan. Klik tombol di bawah ini jika barang sudah dikembalikan untuk menyelesaikan alur.</p>
                    </div>
                    
                    <button type="button"
                            x-on:click="
                                Swal.fire({
                                    title: 'Selesaikan peminjaman ini?',
                                    text: 'Tindakan ini akan mengembalikan status semua peralatan menjadi Tersedia dan menerbitkan BA Pengembalian otomatis.',
                                    icon: 'info',
                                    showCancelButton: true,
                                    confirmButtonColor: '#dc2626',
                                    cancelButtonColor: '#6b7280',
                                    confirmButtonText: 'Ya, Kembalikan!',
                                    cancelButtonText: 'Batal'
                                }).then((result) => {
                                    if (result.isConfirmed) { 
                                        $wire.selesaikanPeminjaman()
                                    }
                                });
                            "
                            class="w-full flex justify-center items-center gap-2 px-4 py-2.5 text-xs font-medium text-white bg-green-600 hover:bg-green-700 shadow-sm rounded-xl transition-colors">
                        <i class="ti ti-circle-check text-base"></i>
                        Selesaikan & Kembalikan Peralatan
                    </button>
                </div>
            @endif

            {{-- CASE 3: Menampilkan Arsip Berita Acara yang Sudah Terbit (Peminjaman Aktif maupun Selesai) --}}
            @if($peminjaman->beritaAcara->isNotEmpty())
                <div class="card bg-white rounded-2xl border border-slate-200 p-6 shadow-sm space-y-3">
                    <h3 class="text-sm font-semibold text-slate-800 flex items-center gap-2 mb-2">
                        <i class="ti ti-archive text-slate-400 text-lg"></i>
                        Dokumen Berita Acara
                    </h3>
                    
                    <div class="divide-y divide-slate-100">
                        @foreach($peminjaman->beritaAcara as $ba)
                            <div class="py-2.5 flex items-center justify-between first:pt-0 last:pb-0">
                                <div class="flex items-center gap-2.5">
                                    <div class="p-2 {{ $ba->jenis_ba === App\Models\BeritaAcara::BA_PEMINJAMAN ? 'bg-blue-50 text-blue-600' : 'bg-green-50 text-green-600' }} rounded-lg text-base">
                                        <i class="ti ti-file-text"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs font-medium text-slate-700">{{ $ba->jenis_ba }}</p>
                                        <p class="text-[10px] text-slate-400">{{ $ba->nomor_ba }}</p>
                                    </div>
                                </div>
                                
                                {{-- Hubungkan ke route cetak PDF BA Anda --}}
                                <a href="{{ route('ba.download', $ba->id) }}" target="_blank"
                                class="p-1 text-slate-400 hover:text-primary-600 transition-colors" title="Cetak/Download PDF">
                                    <i class="ti ti-download text-base"></i>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
            </div>
        </div>
        @endif
</div>