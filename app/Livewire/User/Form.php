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
    public string $role = 'Pengguna';
    public ?string $nip = null;
    public ?string $jabatan = null;
    public ?string $unit = null;
    public string $password = '';
    public bool $is_active = true;

    public function mount(?User $user = null): void
    {
        $this->user = $user;

        if ($user && $user->exists) {
            $this->nama_lengkap = $user->nama_lengkap;
            $this->username = $user->username;
            $this->email = $user->email;
            $this->role = $user->role;
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
            'role'         => 'required|in:' . implode(',', User::ROLES),
            'nip'          => 'nullable|string|max:50',
            'jabatan'      => 'nullable|string|max:100',
            'unit'         => 'nullable|string|max:100',
            'password'     => 'required|min:6',
            'is_active'    => 'required|boolean',
        ];
    }

    public function save()
    {
        $validated = $this->validate();

        if (!$this->user || !$this->user->exists) {
            User::create($validated);
            session()->flash('success', 'User baru berhasil didaftarkan.');
        } else {
            if (auth()->id() === $this->user->id) {
                $validated['is_active'] = true;
            }

            if ($this->password) {
                $validated['password'] = Hash::make($this->password);
            } else {
                unset($validated['password']);
            }

            $this->user->update($validated);
            session()->flash('success', 'Data user berhasil diperbarui.');
        }

        return redirect()->route('users.index');
    }

    public function render()
    {
        return view('livewire.user.form', [
            'title' => $this->user && $this->user->exists ? 'Edit Pengguna' : 'Registrasi Pengguna Baru'
        ]);
    }
}