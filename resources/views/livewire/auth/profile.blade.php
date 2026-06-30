<?php

use Livewire\Volt\Component;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Models\User;

new class extends Component {
    public string $nama_lengkap = '';
    public string $email = '';
    public string $nip = '';
    public string $jabatan = '';
    public string $unit = '';
    public string $username = '';

    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function mount(): void
    {
        $user = auth()->user();

        $this->nama_lengkap = $user->nama_lengkap;
        $this->email = $user->email;
        $this->nip = $user->nip ?? '';
        $this->jabatan = $user->jabatan ?? '';
        $this->unit = $user->unit ?? '';
        $this->username = $user->username;
    }

    public function updateProfile(): void
    {
        $user = auth()->user();

        $validated = $this->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email'        => 'required|string|lowercase|email|max:255|unique:users,email,' . $user->id,
            'nip'          => 'nullable|string|max:50',
            'jabatan'      => 'nullable|string|max:100',
            'unit'         => 'nullable|string|max:100',
        ]);

        $user->update($validated);

        session()->flash('success', 'Informasi profil Anda berhasil diperbarui.');
    }

    public function updatePassword(): void
    {
        $user = auth()->user();

        $validated = $this->validate([
            'current_password' => 'required|current_password',
            'password'         => 'required|confirmed|min:8',
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        $this->reset(['current_password', 'password', 'password_confirmation']);

        session()->flash('success', 'Kata sandi akun Anda berhasil diubah.');
    }

    public function with(): array
    {
        return [
            'user' => auth()->user(),
        ];
    }
}; ?>

<div>
    <x-page-header
        title="Pengaturan Profil"
        subtitle="Kelola informasi data diri, unit kerja, serta kredensial keamanan akun Anda"
        :breadcrumbs="[['label' => 'Profil Saya']]"
    />

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
        
        {{-- ==========================================
             KOLOM KIRI: KARTU RINGKASAN PROFIL
             ========================================== --}}
        <div class="lg:col-span-1">
            <div class="card bg-white rounded-2xl border border-slate-200 shadow-sm p-6 text-center">
                <div class="w-20 h-20 rounded-full bg-primary-600 flex items-center justify-center mx-auto shadow-md ring-4 ring-primary-50">
                    <span class="text-white text-2xl font-bold">
                        {{ strtoupper(substr($nama_lengkap, 0, 2)) }}
                    </span>
                </div>

                <h3 class="text-base font-bold text-slate-800 mt-4 truncate">{{ $nama_lengkap }}</h3>
                <p class="text-xs text-slate-400 mt-0.5">{{ $username }}</p>
                
                <div class="mt-3">
                    <span class="inline-flex items-center rounded-full bg-blue-50 px-3 py-0.5 text-xs font-semibold text-blue-700 ring-1 ring-inset ring-blue-600/20">
                        Admin
                    </span>
                </div>

                <hr class="border-slate-100 my-4">

                <div class="space-y-2.5 text-left text-xs text-slate-600">
                    <div class="flex justify-between">
                        <span class="text-slate-400">NIP:</span>
                        <span class="font-medium text-slate-700">{{ $nip ?: '—' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Unit Kerja:</span>
                        <span class="font-medium text-slate-700 truncate max-w-[160px]" title="{{ $unit }}">{{ $unit ?: '—' }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ==========================================
             KOLOM KANAN: FORM UPDATE DATA & KEAMANAN
             ========================================== --}}
        <div class="lg:col-span-2 space-y-6">
            
            <div class="card bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                    <h4 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Informasi Akun</h4>
                </div>

                <form wire:submit.prevent="updateProfile" class="p-6 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="form-label text-slate-700 font-medium text-sm">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="nama_lengkap" class="form-input rounded-xl border-slate-200 focus:border-primary-500 text-sm">
                            @error('nama_lengkap') <p class="form-error font-medium mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-1.5">
                            <label class="form-label text-slate-700 font-medium text-sm">Alamat Email <span class="text-red-500">*</span></label>
                            <input type="email" wire:model="email" class="form-input rounded-xl border-slate-200 focus:border-primary-500 text-sm">
                            @error('email') <p class="form-error font-medium mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-1.5">
                            <label class="form-label text-slate-700 font-medium text-sm">Nomor Induk Pegawai</label>
                            <input type="text" wire:model="nip" class="form-input rounded-xl border-slate-200 focus:border-primary-500 text-sm" placeholder="Contoh: 9416234Z">
                            @error('nip') <p class="form-error font-medium mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-1.5">
                            <label class="form-label text-slate-700 font-medium text-sm">Jabatan</label>
                            <input type="text" wire:model="jabatan" class="form-input rounded-xl border-slate-200 focus:border-primary-500 text-sm" placeholder="Contoh: Team Leader">
                            @error('jabatan') <p class="form-error font-medium mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-1">
                        <div class="space-y-1.5 md:col-span-2">
                            <label class="form-label text-slate-700 font-medium text-sm">Unit Kerja</label>
                            <input type="text" wire:model="unit" class="form-input rounded-xl border-slate-200 focus:border-primary-500 text-sm" placeholder="Contoh: PLN UP3 Palembang">
                            @error('unit') <p class="form-error font-medium mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="flex items-center justify-end pt-3 border-t border-slate-100">
                        <button type="submit" wire:loading.attr="disabled" wire:target="updateProfile"
                                class="inline-flex items-center gap-2 px-5 py-2 text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 shadow-sm rounded-xl transition-all active:scale-[0.98]">
                            <svg wire:loading wire:target="updateProfile" class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span>Simpan</span>
                        </button>
                    </div>
                </form>
            </div>

            <div class="card bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                    <h4 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Keamanan Akun</h4>
                </div>

                <form wire:submit.prevent="updatePassword" class="p-6 space-y-4" x-data="{ showCurrent: false, showNew: false }">
                    
                    {{-- Password Saat Ini --}}
                    <div class="space-y-1.5">
                        <label class="form-label text-slate-700 font-medium text-sm">Kata Sandi Saat Ini</label>
                        <div class="relative rounded-xl shadow-sm">
                            <input :type="showCurrent ? 'text' : 'password'" wire:model="current_password" 
                                   class="form-input rounded-xl border-slate-200 focus:border-primary-500 text-sm pr-10 w-full">
                            <button type="button" @click="showCurrent = !showCurrent" 
                                    class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-600 focus:outline-none">
                                <i class="ti text-lg" :class="showCurrent ? 'ti-eye-off' : 'ti-eye'"></i>
                            </button>
                        </div>
                        @error('current_password') <p class="form-error font-medium mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Password Baru --}}
                        <div class="space-y-1.5">
                            <label class="form-label text-slate-700 font-medium text-sm">Kata Sandi Baru</label>
                            <div class="relative rounded-xl shadow-sm">
                                <input :type="showNew ? 'text' : 'password'" wire:model="password" 
                                       class="form-input rounded-xl border-slate-200 focus:border-primary-500 text-sm pr-10 w-full">
                                <button type="button" @click="showNew = !showNew" 
                                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-600 focus:outline-none">
                                    <i class="ti text-lg" :class="showNew ? 'ti-eye-off' : 'ti-eye'"></i>
                                </button>
                            </div>
                            @error('password') <p class="form-error font-medium mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Konfirmasi Password --}}
                        <div class="space-y-1.5">
                            <label class="form-label text-slate-700 font-medium text-sm">Konfirmasi Kata Sandi Baru</label>
                            <div class="relative rounded-xl shadow-sm">
                                <input :type="showNew ? 'text' : 'password'" wire:model="password_confirmation" 
                                       class="form-input rounded-xl border-slate-200 focus:border-primary-500 text-sm pr-10 w-full">
                            </div>
                            @error('password_confirmation') <p class="form-error font-medium mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="flex items-center justify-end pt-3 border-t border-slate-100">
                        <button type="submit" wire:loading.attr="disabled" wire:target="updatePassword"
                                class="inline-flex items-center gap-2 px-5 py-2 text-sm font-medium text-white bg-slate-800 hover:bg-slate-900 shadow-sm rounded-xl transition-all active:scale-[0.98]">
                            <svg wire:loading wire:target="updatePassword" class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span>Ubah Kata Sandi</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>