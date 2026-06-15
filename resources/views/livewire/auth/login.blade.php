<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] #[Title('Masuk — SIAGA PLN')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-slate-900 font-display">Selamat datang</h2>
        <p class="text-sm text-muted mt-1">Masuk ke akun SIAGA PLN Anda</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="login" class="space-y-5">
        <!-- Input Username -->
        <div>
            <label for="username" class="form-label">Username</label>
            <input id="username"
                   type="text"
                   wire:model="form.username"
                   value="{{ old('username') }}"
                   class="form-input @error('form.username') border-red-400 focus:ring-red-500 @enderror"
                   placeholder="Masukkan username Anda"
                   required autofocus autocomplete="username">
            @error('username')
            <p class="form-error">{{ $message }}</p>
            @enderror
        </div>
        <!-- Input Password -->
        <div>
            <div class="flex items-center justify-between mb-1">
                <label for="password" class="form-label mb-0">Password</label>
                @if(Route::has('password.request'))
                <a href="{{ route('password.request') }}"
                   class="text-xs text-primary-600 hover:text-primary-700 transition-colors">
                    Lupa password?
                </a>
                @endif
            </div>
            <div class="relative" x-data="{ show: false }">
                <input id="password"
                       :type="show ? 'text' : 'password'"
                       wire:model="form.password"
                       class="form-input pr-10 @error('form.password') border-red-400 focus:ring-red-500 @enderror"
                       placeholder="••••••••"
                   required autocomplete="current-password">
                <button type="button"
                        @click="show = !show"
                        class="absolute inset-y-0 right-3 flex items-center text-slate-400 hover:text-slate-600">
                    <i :class="show ? 'ti ti-eye-off' : 'ti ti-eye'" class="text-base"></i>
                </button>
            </div>
            @error('password')
            <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <!-- Ingat Saya -->
        <div class="flex items-center gap-2">
            <input id="remember_me"
                   type="checkbox"
                   name="remember"
                   class="w-4 h-4 rounded border-slate-300 text-primary-600
                          focus:ring-primary-500 cursor-pointer">
            <label for="remember_me" class="text-sm text-slate-600 cursor-pointer select-none">
                Ingat saya
            </label>
        </div>

        <!-- Tombol Masuk -->
        <button type="submit" class="btn-primary w-full justify-center py-2.5 mt-2">
            <i class="ti ti-login"></i>
            Masuk
        </button>
    </form>

    {{-- Copyright --}}
    <p class="mt-8 text-center text-xs text-slate-400">
        © {{ date('Y') }} PT PLN (Persero) · Internal use only
    </p>
</div>
