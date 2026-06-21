<div x-data="{ showForm: false }" 
     @open-category-form.window="showForm = true" 
     @close-category-form.window="showForm = false">
     
    <x-page-header
        :title="$selectedJenisId ? 'Daftar ' . $selectedJenis : 'Peralatan'"
        :subtitle="$selectedJenisId ? 'Melihat seluruh jenis peralatan ini' : 'Daftar seluruh peralatan siaga'"
        :breadcrumbs="$selectedJenisId ? [['label' => 'Peralatan', 'url' => '#'], ['label' => $selectedJenis]] : [['label' => 'Peralatan']]">
        
        {{-- ==============================
             AKSI
             ============================== --}}
        @if(!$selectedJenisId)
            @can('create', App\Models\Peralatan::class)
                <button wire:click="createJenis" class="btn btn-secondary flex items-center gap-2 text-xs sm:text-sm py-2 px-3 rounded-xl transition-all">
                    <i class="ti ti-plus"></i>
                    <span>Jenis</span>
                </button>
                <a href="{{ route('peralatan.create') }}" wire:navigate class="btn-primary flex items-center gap-2 text-xs sm:text-sm py-2 px-3 rounded-xl">
                    <i class="ti ti-plus"></i>
                    <span>Peralatan</span>
                </a>
            @endcan
        @else
            <button wire:click="resetJenis" class="btn btn-ghost flex items-center gap-2 rounded-xl text-xs sm:text-sm">
                <i class="ti ti-arrow-left"></i>
                <span>Kembali</span>
            </button>
        @endif
    </x-page-header>

    {{-- ==============================
         MAIN CONTENT
         ============================== --}}
    <div class="mt-4 sm:mt-6">

        {{-- ==============================
             Display Peralatan TIAP Jenis
             ============================== --}}
        @if(!$selectedJenisId)
            @if($peralatanJenis->isEmpty())
                <x-empty-state
                    icon="ti-box"
                    title="Belum ada peralatan"
                    message="Data kategori dan peralatan siaga belum tersedia."
                    :action="auth()->user()->can('create', App\Models\Peralatan::class) ? ['href' => route('peralatan.create'), 'label' => 'Tambah Peralatan'] : null" />
            @else
                <div class="grid grid-cols-1 xs:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-4 animate-fade-in">
                    @if($isCreatingJenis) {{-- Form menambah jenis peralatan --}}
                        <div class="card p-4 border-2 border-primary-500 bg-white rounded-2xl shadow-sm flex flex-col justify-between min-h-[220px]">
                            <div class="space-y-3">
                                <div class="w-8 h-8 rounded-xl bg-primary-50 text-primary-600 flex items-center justify-center">
                                    <i class="ti ti-folder-plus text-lg"></i>
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Tambah Jenis Peralatan</label>
                                    <input type="text" 
                                           wire:model="nama_jenis" 
                                           wire:keydown.enter="saveJenis"
                                           wire:keydown.escape="cancelJenis"
                                           class="form-input text-xs rounded-xl p-2 border-slate-300 focus:border-primary-500 focus:ring-1 focus:ring-primary-500 w-full"
                                           placeholder="Nama jenis peralatan" 
                                           autofocus>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 justify-end pt-2 border-t border-slate-100">
                                <button wire:click="cancelJenis" class="btn btn-ghost text-[11px] py-1 px-3 rounded-lg">Batal</button>
                                <button wire:click="saveJenis" class="btn btn-primary text-[11px] py-1 px-3 rounded-lg flex items-center gap-1">
                                    <i class="ti ti-check"></i> Simpan
                                </button>
                            </div>
                        </div>
                    @endif
                    @foreach($peralatanJenis as $jenis) {{-- Card Data --}}
                        <div class="card p-0 overflow-hidden flex flex-col justify-between hover:shadow-md hover:border-primary-400 transition-all duration-200 bg-white border border-slate-200 rounded-2xl group relative">
                            <div wire:click="selectJenis({{ $jenis->id }}, '{{ $jenis->nama_jenis }}')" 
                                 class="w-full aspect-[16/10] sm:aspect-[4/3] bg-slate-100 flex flex-col items-center justify-center text-slate-400 gap-1 shrink-0 border-b border-slate-100 overflow-hidden cursor-pointer">
                                @if($jenis->peralatan->first() && $jenis->peralatan->first()->foto)
                                    <img src="{{ Storage::url($jenis->peralatan->first()->foto) }}"
                                        alt="{{ $jenis->nama_jenis }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                @else
                                    <i class="ti ti-box text-2xl sm:text-3xl group-hover:scale-110 transition-transform duration-200"></i>
                                @endif
                            </div>
                            <div class="p-3 sm:p-4 flex-1 flex flex-col justify-between bg-white">
                                <div>
                                    @if($editingJenisId === $jenis->id) {{-- Inline Edit Nama Jenis --}}
                                        <div class="space-y-2 min-h-[36px] sm:min-h-[40px]">
                                            <input type="text" 
                                                   wire:model="nama_jenis" 
                                                   wire:keydown.enter="saveJenis"
                                                   wire:keydown.escape="cancelJenis"
                                                   class="form-input text-xs rounded-lg p-1.5 border-primary-500 focus:ring-1 focus:ring-primary-500 w-full"
                                                   placeholder="Nama kategori..." 
                                                   autofocus>
                                            <div class="flex items-center gap-1 justify-end">
                                                <button wire:click="cancelJenis" class="p-1 text-slate-400 hover:text-slate-600" title="Batal">
                                                    <i class="ti ti-x text-sm"></i>
                                                </button>
                                                <button wire:click="saveJenis" class="p-1 text-green-600 hover:text-green-700" title="Simpan">
                                                    <i class="ti ti-check text-sm"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @else
                                        <div class="flex items-center justify-between gap-1 min-h-[36px] sm:min-h-[40px]">
                                            <h3 wire:click="selectJenis({{ $jenis->id }}, '{{ $jenis->nama_jenis }}')" 
                                                class="font-semibold text-slate-800 text-md leading-snug line-clamp-2 group-hover:text-primary-600 transition-colors cursor-pointer flex-1">
                                                {{ $jenis->nama_jenis }}
                                            </h3>
                                            @if(auth()->user()->isAdmin())
                                            <button wire:click.stop="editJenis({{ $jenis->id }}, '{{ $jenis->nama_jenis }}')" 
                                                    class="text-slate-500 hover:text-primary-600 p-0.5 rounded transition-colors shrink-0"
                                                    title="Edit Nama">
                                                <i class="ti ti-edit text-xs sm:text-sm"></i>
                                            </button>
                                            @endif
                                        </div>
                                    @endif
                                    <div wire:click="selectJenis({{ $jenis->id }}, '{{ $jenis->nama_jenis }}')"
                                         class="grid grid-cols-3 gap-0.5 sm:gap-1 bg-slate-50 border border-slate-100 rounded-xl p-1.5 sm:p-2 mt-2 text-center text-[10px] sm:text-xs cursor-pointer">
                                        <div>
                                            <p class="text-[9px] sm:text-[10px] font-medium text-slate-500 uppercase tracking-wider">Total</p>
                                            <p class="text-xs sm:text-sm font-bold text-slate-800 mt-0.5">{{ $jenis->total_alat }}</p>
                                        </div>
                                        <div>
                                            <p class="text-[9px] sm:text-[10px] font-medium text-green-600 uppercase tracking-wider">Ready</p>
                                            <p class="text-xs sm:text-sm font-bold text-green-600 mt-0.5">{{ $jenis->total_tersedia }}</p>
                                        </div>
                                        <div>
                                            <p class="text-[9px] sm:text-[10px] font-medium text-orange-600 uppercase tracking-wider">Used</p>
                                            <p class="text-xs sm:text-sm font-bold text-orange-600 mt-0.5">{{ $jenis->total_dipinjam }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div wire:click="selectJenis({{ $jenis->id }}, '{{ $jenis->nama_jenis }}')"
                                     class="flex items-center justify-between mt-2.5 sm:mt-3 pt-2 border-t border-slate-100 text-[11px] sm:text-xs font-medium text-slate-400 group-hover:text-primary-600 transition-colors cursor-pointer">
                                    <span>Lihat peralatan</span>
                                    <i class="ti ti-arrow-right text-sm sm:text-base transform group-hover:translate-x-1 transition-transform"></i>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                @error('nama_jenis') 
                    <div class="mt-3 text-xs text-red-500 bg-red-50 p-2.5 rounded-xl border border-red-100 max-w-sm">
                        {{ $message }}
                    </div> 
                @enderror
            @endif
            
        {{-- ==============================
             Display Peralatan PER Jenis
             ============================== --}}
        @else
            <div wire:loading wire:target="selectJenis, resetJenis" class="w-full p-12 text-center text-slate-500 text-sm">
                <i class="ti ti-loader-2 animate-spin text-2xl text-primary-600 mb-2 block mx-auto"></i>
                Memuat daftar peralatan...
            </div>
            <div wire:loading.remove class="card p-0 overflow-hidden border border-slate-200 bg-white rounded-2xl shadow-sm animate-fade-in">
                <div class="px-4 sm:px-5 py-3 bg-slate-50 border-b border-slate-100 flex justify-between items-center">
                    <h4 class="font-semibold text-slate-700 text-xs sm:text-sm">
                        Total: <span class="text-primary-600 font-bold">{{ count($peralatans) }} Peralatan</span>
                    </h4>
                </div>
                @if(count($peralatans) === 0)
                    <div class="p-8 sm:p-12 text-center text-slate-400 text-sm flex flex-col items-center gap-2">
                        <i class="ti ti-box text-3xl text-slate-300"></i>
                        <span>Belum ada unit peralatan pada kategori ini.</span>
                    </div>
                @else
                    <div class="divide-y divide-slate-100">
                        @foreach($peralatans as $peralatan)
                            <div wire:key="tab-eq-{{ $peralatan->id }}" class="p-3 sm:p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 hover:bg-slate-50/60 transition-colors">          
                                <a href="{{ route('peralatan.show', $peralatan) }}" wire:navigate class="flex items-center gap-3 flex-1 min-w-0 group">
                                    <div class="w-11 h-11 sm:w-10 sm:h-10 rounded-xl bg-slate-100 shrink-0 overflow-hidden border border-slate-200">
                                        @if($peralatan->foto)
                                            <img src="{{ Storage::url($peralatan->foto) }}" alt="Foto alat" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center bg-slate-50 text-slate-400">
                                                <i class="ti ti-box text-lg"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="min-w-0 flex gap-6">
                                        <p class="font-semibold sm:font-medium text-slate-800 text-sm group-hover:text-primary-600 transition-colors">
                                            <i class="text-base text-slate-400 me-1 ti ti-grid-3x3"></i>
                                            {{ $peralatan->nomor_seri }}
                                        </p>
                                        <p class="text-slate-800">
                                            <i class="text-base text-slate-400 me-1 ti ti-circuit-resistor"></i>
                                            {{ $peralatan->kapasitas ? number_format($peralatan->kapasitas, fmod($peralatan->kapasitas, 1) == 0 ? 0 : 1, ',', '.') : 'Kapasitas: —' }} {{ $peralatan->satuan ?? '' }}
                                        </p>
                                        <p class="text-slate-800">
                                            <i class="text-base text-slate-400 me-1 ti ti-map-pin"></i>
                                            {{ $peralatan->lokasi ?? 'Lokasi -' }}
                                        </p>    
                                        <x-badge-status :status="$peralatan->status" />
                                    </div>
                                </a>
                                <div class="flex items-center justify-between sm:justify-end gap-3 shrink-0 pt-2 sm:pt-0 border-t border-dashed border-slate-100 sm:border-0">
                                    <div class="flex items-center gap-1 bg-slate-100 sm:bg-transparent rounded-lg p-0.5 sm:p-0">
                                        @can('view', $peralatan)
                                        <a href="{{ route('peralatan.show', $peralatan) }}" wire:navigate class="btn-ghost p-2 sm:p-1.5 text-slate-600 rounded-md">
                                            <i class="ti ti-eye text-base"></i>
                                        </a>
                                        @endcan
                                        @can('update', $peralatan)
                                        <a href="{{ route('peralatan.edit', $peralatan) }}" wire:navigate class="btn-ghost p-2 sm:p-1.5 text-slate-600 rounded-md">
                                            <i class="ti ti-edit text-base"></i>
                                        </a>
                                        @endcan
                                        @can('delete', $peralatan)
                                        <button class="btn-ghost p-2 sm:p-1.5 text-red-500 hover:bg-red-50 rounded-md"
                                            x-on:click="
                                                Swal.fire({
                                                    title: 'Hapus Peralatan?',
                                                    text: 'Data peralatan ini akan dihapus permanen.',
                                                    icon: 'warning',
                                                    showCancelButton: true,
                                                    confirmButtonColor: '#dc2626',
                                                    cancelButtonColor: '#6b7280',
                                                    confirmButtonText: 'Ya, Hapus!',
                                                    cancelButtonText: 'Batal'
                                                }).then((result) => {
                                                    if (result.isConfirmed) { 
                                                        $wire.delete({{ $peralatan->id }})
                                                    }
                                                });
                                            ">
                                            <i class="ti ti-trash text-base"></i>
                                        </button>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>