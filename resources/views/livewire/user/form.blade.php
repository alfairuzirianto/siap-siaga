<div>
    <x-page-header 
        title="{{ $user ? 'Edit User' : 'Registrasi User' }}"
        subtitle="{{ $user ? 'Perbaru informasi user' : 'Tambahkan user baru' }}"
        :breadcrumbs="[
            ['label' => 'Kelola User', 'url' => route('users.index')],
            ['label' => $user ? $user->username : 'Registrasi User' ]]
        "/>

    <div class="max-w-3xl mx-auto">
        <div class="card bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <form wire:submit.prevent="save" class="p-6 space-y-6" x-data="{ showPass: false }">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Nama Lengkap --}}
                    <div class="space-y-1.5">
                        <label class="form-label text-slate-700 font-medium text-sm">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="nama_lengkap" class="form-input rounded-xl border-slate-200 focus:border-primary-500 text-sm">
                        @error('nama_lengkap') <p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- NIP --}}
                    <div class="space-y-1.5">
                        <label class="form-label text-slate-700 font-medium text-sm">Nomor Induk Pegawai</label>
                        <input type="text" wire:model="nip" class="form-input rounded-xl border-slate-200 focus:border-primary-500 text-sm" placeholder="Contoh: 9514231PLN">
                        @error('nip') <p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Username --}}
                    <div class="space-y-1.5">
                        <label class="form-label text-slate-700 font-medium text-sm">Username <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="username" class="form-input rounded-xl border-slate-200 focus:border-primary-500 text-sm" placeholder="Contoh: ahmad_pln">
                        @error('username') <p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Email --}}
                    <div class="space-y-1.5">
                        <label class="form-label text-slate-700 font-medium text-sm">Alamat Email <span class="text-red-500">*</span></label>
                        <input type="email" wire:model="email" class="form-input rounded-xl border-slate-200 focus:border-primary-500 text-sm" placeholder="nama@pln.co.id">
                        @error('email') <p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Jabatan --}}
                    <div class="space-y-1.5">
                        <label class="form-label text-slate-700 font-medium text-sm">Jabatan</label>
                        <input type="text" wire:model="jabatan" class="form-input rounded-xl border-slate-200 focus:border-primary-500 text-sm" placeholder="Contoh: Supervisor Logistik">
                        @error('jabatan') <p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Role --}}
                    <div class="space-y-1.5">
                        <label class="form-label text-slate-700 font-medium text-sm">
                            Role <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <select wire:model="role" 
                                    class="form-input w-full rounded-xl border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-700 appearance-none focus:border-primary-500 focus:ring-primary-500 transition-all pr-10">
                                <option value="">-- Pilih Role --</option>
                                @foreach(App\Models\User::ROLES as $roleItem)
                                    <option value="{{ $roleItem }}">{{ $roleItem }}</option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-slate-400 text-sm">
                                <i class="ti ti-chevron-down"></i>
                            </div>
                        </div>
                        @error('role') <p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Unit Kerja --}}
                <div class="space-y-1.5">
                    <label class="form-label text-slate-700 font-medium text-sm">Unit Kerja</label>
                    <input type="text" wire:model="unit" class="form-input rounded-xl border-slate-200 focus:border-primary-500 text-sm" placeholder="Contoh: PT PLN (Persero) UP3 Palembang">
                    @error('unit') <p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Input Password dengan fitur Show/Hide Terintegrasi --}}
                <div class="space-y-1.5 border-t border-slate-100 pt-4">
                    <label class="form-label text-slate-700 font-medium text-sm">Kata Sandi Akses</label>
                    <div class="relative rounded-xl shadow-sm max-w-md">
                        <input :type="showPass ? 'text' : 'password'" wire:model="password" class="form-input rounded-xl border-slate-200 focus:border-primary-500 text-sm pr-10">
                        <button type="button" @click="showPass = !showPass" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-600 focus:outline-none">
                            <i class="ti text-lg" :class="showPass ? 'ti-eye-off' : 'ti-eye'"></i>
                        </button>
                    </div>
                    <p class="text-[11px] text-slate-400 mt-1">
                        {{ $user && $user->exists ? 'Kosongkan jika tidak berencana mengubah sandi.' : 'Kosongkan jika ingin password default disamakan dengan NIP / Username.' }}
                    </p>
                    @error('password') <p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <a href="{{ route('users.index') }}" wire:navigate 
                        class="btn-ghost">
                        Batal
                    </a>
                    <button type="submit" wire:loading.attr="disabled" wire:target="save"
                            class="inline-flex items-center gap-2 px-5 py-2 text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 shadow-sm rounded-xl transition-all active:scale-[0.98]">
                        <svg wire:loading wire:target="save" class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>
                            {{ $user ? 'Simpan' : 'Daftarkan' }}
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>