<?php

namespace App\Livewire\User;

use App\Models\User;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Kelola User | SiapSiaga')]
class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterRole = '';

    protected $updatesQueryString = ['search', 'filterRole'];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function toggleStatus(User $user): void
    {
        if (auth()->id() === $user->id) {
            session()->flash('error', 'Anda tidak dapat menonaktifkan akun Anda sendiri.');
            return;
        }

        $user->update(['is_active' => !$user->is_active]);
        session()->flash('success', 'Status keaktifan user berhasil diubah.');
    }

    public function render()
    {
        $users = User::when($this->search, fn ($query) =>
            $query->where(fn ($q) =>
                $q->where('nama_lengkap', 'like', '%' . $this->search . '%')
                  ->orWhere('nip', 'like', '%' . $this->search . '%')
                  ->orWhere('username', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
            )
            ->when($this->filterRole, fn ($q) =>
                $q->where('role', $this->filterRole)
            )
        )
        ->paginate(10);

        return view('livewire.user.index', compact('users'));
    }
}
