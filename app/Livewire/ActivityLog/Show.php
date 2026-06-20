<?php

namespace App\Livewire\ActivityLog;

use App\Models\ActivityLog;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Rincian Log Aktivitas | SiapSiaga')]
class Show extends Component
{
    public ?ActivityLog $log = null;

    public function mount(ActivityLog $activityLog): void
    {
        if (!auth()->user()->isAdmin()) abort(403);
        $this->log = $activityLog->load('user');
    }

    public function render()
    {
        return view('livewire.activity-log.show');
    }
}
