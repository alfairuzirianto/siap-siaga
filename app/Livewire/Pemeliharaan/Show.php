<?php

namespace App\Livewire\Pemeliharaan;

use App\Models\Pemeliharaan;
use Livewire\Component;

class Show extends Component
{
    public ?Pemeliharaan $pemeliharaan = null;

    public function mount(Pemeliharaan $pemeliharaan)
    {
        $this->authorize('view', $pemeliharaan);
        $this->pemeliharaan = $pemeliharaan->load(['peralatan.jenis', 'creator', 'updater']);
    }

    public function render()
    {
        return view('livewire.pemeliharaan.show');
    }
}