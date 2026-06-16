<div>
    <x-page-header
        title="{{ $pemeliharaan && $pemeliharaan->exists ? 'Edit Pemeliharaan' : 'Catat Pemeliharaan' }}"
        subtitle="{{ $pemeliharaan && $pemeliharaan->exists ? 'Perbarui informasi detail pemeliharaan' : 'Catat riwayat pemeliharaan peralatan' }}"
        :breadcrumbs="[
            ['label' => 'Maintenance', 'url' => route('pemeliharaan.index')],
            ['label' => $pemeliharaan && $pemeliharaan->exists ? $pemeliharaan->nomor_pemeliharaan : 'Catat Pemeliharaan']]
        "/>

    {{-- ==============================
         MAIN CONTENT
         ============================== --}}
    <div class="max-w-4xl mx-auto">
        <div class="card bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <form wire:submit.prevent="save" class="p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    {{-- No Pemeliharaan --}}
                    <div class="space-y-1.5">
                        <label class="form-label text-slate-700 font-medium text-sm">Nomor Pemeliharaan <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="nomor_pemeliharaan" class="form-input rounded-xl border-slate-200 focus:border-primary-500 text-sm">
                        @error('nomor_pemeliharaan') <p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Pilih Alat Siaga (Menggunakan Reusable Select Box Component) --}}
                    <div class="space-y-1.5">
                        <label class="form-label text-slate-700 font-medium text-sm">
                            Peralatan Siaga <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <select wire:model="peralatan_id" 
                                    class="form-input w-full rounded-xl border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-700 appearance-none focus:border-primary-500 focus:ring-primary-500 transition-all pr-10">
                                <option value="">-- Pilih Peralatan --</option>
                                @foreach($peralatans as $peralatan)
                                    <option value="{{ $peralatan->id }}">{{ $peralatan->nomor_seri }} ({{ $peralatan->jenis?->nama_jenis }})</option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-slate-400 text-sm">
                                <i class="ti ti-chevron-down"></i>
                            </div>
                        </div>
                        @error('peralatan_id') <p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Nama Petugas Vendor / Internal --}}
                    <div class="space-y-1.5">
                        <label class="form-label text-slate-700 font-medium text-sm">Nama Penanggung Jawab / Petugas <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="nama_petugas" class="form-input rounded-xl border-slate-200 focus:border-primary-500 text-sm" placeholder="Contoh: Ahmad Vendor PT Sinergi">
                        @error('nama_petugas') <p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Tanggal Pemeliharaan --}}
                    <div class="space-y-1.5">
                        <label class="form-label text-slate-700 font-medium text-sm">Tanggal Pelaksanaan <span class="text-red-500">*</span></label>
                        <input type="date" wire:model="tanggal_pemeliharaan" class="form-input rounded-xl border-slate-200 focus:border-primary-500 text-sm">
                        @error('tanggal_pemeliharaan') <p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Jenis Tindakan (Menggunakan Reusable Select Box Component) --}}
                    <div class="space-y-1.5">
                        <label class="form-label text-slate-700 font-medium text-sm">
                            Jenis Pemeliharaan <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <select wire:model="jenis_pemeliharaan" 
                                    class="form-input w-full rounded-xl border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-700 appearance-none focus:border-primary-500 focus:ring-primary-500 transition-all pr-10">
                                <option value="">-- Pilih Jenis Pemeliharaan --</option>
                                @foreach(App\Models\Pemeliharaan::JENIS_PEMELIHARAAN as $jenis)
                                    <option value="{{ $jenis }}">{{ $jenis }}</option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-slate-400 text-sm">
                                <i class="ti ti-chevron-down"></i>
                            </div>
                        </div>
                        @error('jenis_pemeliharaan') <p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Estimasi / Total Biaya --}}
                    <div class="space-y-1.5">
                        <label class="form-label text-slate-700 font-medium text-sm">Total Biaya Perbaikan <span class="text-red-500">*</span></label>
                        <div class="relative rounded-xl shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 text-xs font-semibold">Rp</div>
                            <input type="number" wire:model="biaya" class="form-input rounded-xl border-slate-200 pl-9 focus:border-primary-500 text-sm" placeholder="0">
                        </div>
                        @error('biaya') <p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Deskripsi Tindakan --}}
                <div class="space-y-1.5 border-t border-slate-100 pt-4">
                    <label class="form-label text-slate-700 font-medium text-sm">Deskripsi Kerusakan & Tindakan</label>
                    <textarea wire:model="deskripsi" rows="3" class="form-input rounded-xl border-slate-200 focus:border-primary-500 text-sm" placeholder="Tuliskan komponen apa saja yang diganti atau diservis..."></textarea>
                    @error('deskripsi') <p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <a href="{{ route('pemeliharaan.index') }}" wire:navigate class="btn-ghost">
                        Batal
                    </a>
                    <button type="submit" wire:loading.attr="disabled" wire:target="save"
                            class="inline-flex items-center gap-2 px-5 py-2 text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 shadow-sm rounded-xl transition-all active:scale-[0.98]">
                        <svg wire:loading wire:target="save" class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>
                            {{ $pemeliharaan && $pemeliharaan->exists ? 'Simpan' : 'Catat'}}
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
