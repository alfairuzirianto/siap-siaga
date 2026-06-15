<div>
    <x-page-header
        :title="$title"
        subtitle="{{ $title === 'Edit Peralatan' ? 'Perbarui informasi detail mengenai peralatan siaga' : 'Daftarkan peralatan siaga baru ke dalam sistem' }}"
        :breadcrumbs="[
            ['label' => 'Peralatan', 'url' => route('peralatan.index')],
            ['label' => $title],
        ]" />

    {{-- ==============================
         MAIN CONTENT
         ============================== --}}
    <div class="max-w-4xl mx-auto">
        <div class="card bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Informasi Peralatan</span>
                <div class="flex items-center">
                    @if($title === 'Edit Peralatan')
                        <x-badge-status :status="$status" type="alat" />
                    @else
                        <span class="inline-flex items-center rounded-full bg-green-50 px-2.5 py-1 text-xs font-semibold text-green-700 ring-1 ring-inset ring-green-600/20">
                            Tersedia
                        </span>
                    @endif
                </div>
            </div>
            {{-- ==============================
                 Form Utama Create & Update
                 ============================== --}}
            <form wire:submit.prevent="save" class="p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-1.5">
                        <label class="form-label text-slate-700 font-medium text-sm flex items-center gap-1">
                            Nomor Seri <span class="text-red-500">*</span>
                        </label>
                        <div class="relative rounded-xl">
                            <input class="form-input w-full rounded-xl border-slate-200 focus:border-primary-500 focus:ring-primary-500 text-sm placeholder:text-slate-400" 
                                   type="text" 
                                   wire:model="nomor_seri"
                                   placeholder="Contoh: GNS-0001">
                        </div>
                        @error('nomor_seri') <p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-1.5">
                        <label class="form-label text-slate-700 font-medium text-sm">
                            Jenis Peralatan <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <select wire:model="peralatan_jenis_id" 
                                    class="form-input w-full rounded-xl border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-700 appearance-none focus:border-primary-500 focus:ring-primary-500 transition-all pr-10">
                                @foreach($jenis as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama_jenis }}</option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-slate-400 text-sm">
                                <i class="ti ti-chevron-down"></i>
                            </div>
                        </div>
                        @error('peralatan_jenis_id') <p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-1.5 md:col-span-1">
                        <label class="form-label text-slate-700 font-medium text-sm">Kapasitas</label>
                        <div class="flex rounded-xl gap-4">
                            <input type="number" step="any" wire:model="kapasitas" 
                                   class="form-input block w-full rounded-l-xl border-slate-200 focus:border-primary-500 focus:ring-primary-500 text-sm" 
                                   placeholder="Contoh: 500">
                            <input type="text" wire:model="satuan" 
                                   class="form-input block w-32 -ml-px rounded-r-xl border-slate-200 focus:border-primary-500 focus:ring-primary-500 text-sm" 
                                   placeholder="Satuan">
                        </div>
                        @error('kapasitas') <p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p> @enderror
                        @error('satuan') <p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-1.5">
                        <label class="form-label text-slate-700 font-medium text-sm">Lokasi</label>
                        <input class="form-input w-full rounded-xl border-slate-200 focus:border-primary-500 focus:ring-primary-500 text-sm placeholder:text-slate-400" 
                               type="text" 
                               wire:model="lokasi"
                               placeholder="Contoh: Gudang A">
                        @error('lokasi') <p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="space-y-2 border-t border-slate-100 pt-5">
                    <label class="form-label text-slate-700 font-medium text-sm block">Dokumentasi Foto Alat</label>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-start">
                        <div class="md:col-span-1 flex flex-col items-center justify-center border border-dashed border-slate-200 rounded-xl bg-slate-50/50 min-h-[160px]">
                            @if($foto)
                                <img src="{{ $foto->temporaryUrl() }}" alt="Preview Baru" class="size-full object-cover rounded-xl shadow-sm">
                            @elseif($existingFoto)
                                <img src="{{ asset('storage/' . $existingFoto) }}" alt="Foto Alat Saat Ini" class="size-full object-cover rounded-xl shadow-sm">
                            @else
                                <div class="text-center text-slate-400 space-y-1">
                                    <i class="ti ti-photo text-3xl"></i>
                                    <p class="text-xs">Belum ada foto</p>
                                </div>
                            @endif
                        </div>
                        <div class="md:col-span-2">
                            <label class="relative flex flex-col items-center justify-center w-full h-[160px] border-2 border-slate-300 border-dashed rounded-xl cursor-pointer bg-white hover:bg-slate-50/80 hover:border-primary-400 transition-all duration-150 group">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6 text-center px-4">
                                    <div class="p-2 rounded-lg bg-slate-50 group-hover:bg-primary-50 transition-colors mb-2">
                                        <i class="ti ti-cloud-upload text-2xl text-slate-400 group-hover:text-primary-500"></i>
                                    </div>
                                    <p class="mb-1 text-sm text-slate-600 font-medium">Klik untuk unggah atau seret berkas</p>
                                    <p class="text-xs text-slate-400">PNG, JPG, atau JPEG (Maks. 2MB)</p>
                                </div>
                                <input type="file" wire:model="foto" class="hidden" accept="image/*">
                            </label>
                            <div wire:loading wire:target="foto" class="mt-2 w-full">
                                <div class="flex items-center gap-2 text-xs text-primary-600 font-medium">
                                    <svg class="animate-spin h-4 w-4 text-primary-600" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span>Sedang memproses & mengompres gambar...</span>
                                </div>
                            </div>
                            @error('foto') <p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <a href="{{ route('peralatan.index') }}" 
                       class="btn-ghost">
                        Batal
                    </a>
                <button type="submit" 
                        wire:loading.attr="disabled"
                        wire:target="foto, save"
                        class="inline-flex items-center gap-2 px-5 py-2 text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 disabled:opacity-50 disabled:cursor-not-allowed shadow-sm rounded-xl transition-all">
                    <i class="ti ti-device-floppy text-base" wire:loading.remove wire:target="foto, save"></i>
                    <svg wire:loading wire:target="foto, save" class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span wire:loading.remove wire:target="foto">
                        {{ $title === 'Edit Peralatan' ? 'Simpan' : 'Tambah' }}
                    </span>
                    <span wire:loading wire:target="foto">
                        Mengunggah Foto...
                    </span>
                </button>
                </div>
            </form>
        </div>
    </div>
</div>