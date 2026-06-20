<div>
    <x-page-header 
        title="Ajukan Peminjaman" 
        subtitle="Buat pengajuan sesuai dengan keperluan"
        :breadcrumbs="[['label' => 'Peminjaman', 'url' => route('peminjaman.index')], ['label' => 'Pengajuan']]"
    />

    <div class="max-w-4xl mx-auto">
        <form wire:submit.prevent="save" class="space-y-6">
            
            <div class="card bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-4">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100 pb-2">Detail Pengajuan</h3>
                
                <div class="space-y-4">
                    <div class="space-y-1.5">
                        <label class="form-label text-sm text-slate-700 font-medium">Tujuan Penggunaan <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="tujuan_keperluan" 
                               class="form-input rounded-xl border-slate-200 text-sm focus:border-primary-500 focus:ring-primary-500">
                        @error('tujuan_keperluan') <p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="form-label text-sm text-slate-700 font-medium">Rencana Tanggal Mulai Pinjam <span class="text-red-500">*</span></label>
                            <input type="date" wire:model="tgl_rencana_pinjam" class="form-input rounded-xl border-slate-200 text-sm focus:border-primary-500 focus:ring-primary-500">
                            @error('tgl_rencana_pinjam') <p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="space-y-1.5">
                            <label class="form-label text-sm text-slate-700 font-medium">Rencana Tanggal Pengembalian <span class="text-red-500">*</span></label>
                            <input type="date" wire:model="tgl_rencana_kembali" class="form-input rounded-xl border-slate-200 text-sm focus:border-primary-500 focus:ring-primary-500">
                            @error('tgl_rencana_kembali') <p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="card bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                    <div>
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Pilih Peralatan <span class="text-red-500">*</span></h3>
                        <p class="text-slate-400 text-[11px] font-normal mt-0.5">Daftar peralatan yang tersedia.</p>
                    </div>
                    <span class="inline-flex items-center rounded-full bg-primary-50 px-2.5 py-1 text-xs font-bold text-primary-700 ring-1 ring-inset ring-primary-600/20">
                        {{ count($selectedPeralatan) }} Terpilih
                    </span>
                </div>

                @error('selectedPeralatan') 
                    <p class="text-xs text-red-600 bg-red-50 border border-red-100 rounded-xl p-3 font-medium">{{ $message }}</p> 
                @enderror

                @if($peralatans->isEmpty())
                    <x-empty-state icon="ti-box-off" title="Armada Habis" message="Seluruh peralatan logistik siaga saat ini sedang berstatus dipinjam atau maintenance." />
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        @foreach($peralatans as $peralatan)
                            @php $isChosen = in_array($peralatan->id, $selectedPeralatan); @endphp
                            
                            <div wire:key="select-alat-card-{{ $peralatan->id }}" 
                                 class="p-4 border rounded-xl flex items-center justify-between gap-3 transition-all duration-150 bg-white
                                        {{ $isChosen ? 'border-primary-500 ring-1 ring-primary-500 bg-primary-50/10' : 'border-slate-200/80 hover:border-slate-300' }}">
                                
                                <div class="min-w-0 flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-slate-50 border border-slate-100 flex items-center justify-center shrink-0 overflow-hidden text-slate-400">
                                        @if($peralatan->foto)
                                            <img src="{{ asset('storage/' . $peralatan->foto) }}" class="w-full h-full object-cover">
                                        @else
                                            <i class="ti ti-box text-base"></i>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <span class="font-bold text-slate-800 text-sm block truncate">{{ $peralatan->nomor_seri }}</span>
                                        <span class="text-xs text-slate-400 block truncate">{{ $peralatan->jenis?->nama_jenis }}</span>
                                    </div>
                                </div>

                                <button type="button" wire:click="toggleAlat({{ $peralatan->id }})"
                                        class="px-3 py-1.5 text-xs font-bold rounded-xl transition-all outline-none focus:outline-none shrink-0 border
                                               {{ $isChosen 
                                                   ? 'bg-primary-600 hover:bg-primary-700 text-white border-primary-600 shadow-xs' 
                                                   : 'bg-white hover:bg-slate-50 text-slate-600 border-slate-200' }}">
                                    @if($isChosen)
                                        <i class="ti ti-check mr-0.5"></i> Terpilih
                                    @else
                                        Pilih
                                    @endif
                                </button>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('peminjaman.index') }}" wire:navigate 
                   class="btn-ghost">
                    Batal
                </a>
                <button type="submit" wire:loading.attr="disabled"
                        class="inline-flex items-center gap-2 px-5 py-2 text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 shadow-sm rounded-xl transition-all active:scale-[0.98]">
                    <i class="ti ti-send text-base"></i>
                    <span>Ajukan</span>
                </button>
            </div>

        </form>
    </div>
</div>