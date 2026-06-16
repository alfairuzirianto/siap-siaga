<?php

namespace App\Livewire\ActivityLog;

use App\Models\ActivityLog;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Log Aktivitas | SiapSiaga')]
class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterAksi = '';

    protected $updatesQueryString = ['search', 'filterAksi'];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $logs = ActivityLog::with('user')
            ->when($this->search, fn ($query) =>
                $query->where(fn ($q) =>
                    $q->where('nama_table', 'like', '%' . $this->search . '%')
                    ->orWhere('record_id', 'like', '%' . $this->search . '%')
                    ->orWhereHas('user', fn ($subQuery) =>
                        $subQuery->where('nama_lengkap', 'like', '%' . $this->search . '%')
                    )
                )
            )
            ->when($this->filterAksi, fn ($query) =>
                $query->where('aksi', $this->filterAksi)
            )
        ->latest()
        ->paginate(15);

        return view('livewire.activity-log.index', compact('logs'));
    }
}
