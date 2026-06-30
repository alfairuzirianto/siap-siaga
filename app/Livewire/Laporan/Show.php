<?php

namespace App\Livewire\Laporan;

use App\Models\Peralatan;
use App\Models\Pemeliharaan;
use App\Models\Peminjaman;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;

#[Title('Rincian Analisis Laporan | SiapSiaga')]
class Show extends Component
{
    use WithPagination;

    public string $jenis;

    public function mount(string $jenis)
    {
        if (!in_array($jenis, ['peralatan', 'pemeliharaan', 'peminjaman'])) abort(404);
        $this->jenis = $jenis;
    }

    public function render()
    {
        $records = match ($this->jenis) {
            'peralatan' => Peralatan::with(['jenis', 'creator'])->latest()->paginate(15),
            'pemeliharaan' => Pemeliharaan::with(['peralatan.jenis', 'creator'])->latest()->paginate(15),
            'peminjaman' => Peminjaman::latest()->paginate(15),
        };

        return view('livewire.laporan.show', compact('records'));
    }
}