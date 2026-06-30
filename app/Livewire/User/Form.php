<?php

namespace App\Livewire\User;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Form extends Component
{
    public ?User $user = null;

    public string $nama_lengkap = '';
    public string $username = '';
    public string $email = '';
    public ?string $nip = null;
    public ?string $jabatan = null;
    public ?string $unit = null;
    public ?string $password = null;
    public bool $is_active = true;

    public function mount(?User $user = null): void
    {
        $this->user = $user;

        if ($user && $user->exists) {
            $this->nama_lengkap = $user->nama_lengkap;
            $this->username = $user->username;
            $this->email = $user->email;
            $this->nip = $user->nip;
            $this->jabatan = $user->jabatan;
            $this->unit = $user->unit;
            $this->is_active = $user->is_active;
        }
    }

    protected function rules(): array
    {
        return [
            'nama_lengkap' => 'required|string|max:255',
            'username'     => 'required|string|max:50|alpha_dash|unique:users,username,' . ($this->user?->id ?? 'NULL'),
            'email'        => 'required|string|email|max:255|unique:users,email,' . ($this->user?->id ?? 'NULL'),
            'nip'          => 'nullable|string|max:50',
            'jabatan'      => 'nullable|string|max:100',
            'unit'         => 'nullable|string|max:100',
            'password'     => ($this->user && $this->user->exists) ? 'nullable|string|min:6' : 'required|string|min:6',
            'is_active'    => 'required|boolean',
        ];
    }

    public function save()
    {
        $validated = $this->validate();

        if ($this->user && $this->user->exists) {
            if (empty($validated['password'])) {
                unset($validated['password']);
            } else {
                $validated['password'] = Hash::make($validated['password']);
            }

            $this->user->update($validated);
            session()->flash('success', 'Informasi user berhasil diperbarui.');
        } else {
            $validated['password'] = Hash::make($validated['password']);
            
            User::create($validated);
            session()->flash('success', 'User baru berhasil didaftarkan.');
        }

        return $this->redirect(route('users.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.user.form', [
            'title' => $this->user && $this->user->exists ? 'Edit Pengguna' : 'Registrasi Pengguna Baru'
        ]);
    }
}