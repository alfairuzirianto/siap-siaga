<?php

namespace App\Livewire\Peminjaman;

use App\Models\Peralatan;
use App\Models\Peminjaman;
use App\Services\PeminjamanService;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Buat Pengajuan Peminjaman | SiapSiaga')]
class Form extends Component
{
    public string $tujuan_keperluan = '';
    public string $tgl_rencana_pinjam = '';
    public string $tgl_rencana_kembali = '';
    public array $selectedPeralatan = [];

    public function mount(): void
    {
        $this->authorize('create', Peminjaman::class);

        $this->tgl_rencana_pinjam = now()->format('Y-m-d');
        $this->tgl_rencana_kembali = now()->addDays(3)->format('Y-m-d');
    }

    public function toggleAlat(int $alatId): void
    {
        if (in_array($alatId, $this->selectedPeralatan)) {
            $this->selectedPeralatan = array_diff($this->selectedPeralatan, [$alatId]);
        } else {
            $this->selectedPeralatan[] = $alatId;
        }
        $this->selectedPeralatan = array_values($this->selectedPeralatan);
    }

    public function save(PeminjamanService $service)
    {
        $this->validate([
            'tujuan_keperluan'    => 'required|string|max:255',
            'tgl_rencana_pinjam'  => 'required|date|after_or_equal:today',
            'tgl_rencana_kembali' => 'required|date|after_or_equal:tgl_rencana_pinjam',
            'selectedPeralatan'   => 'required|array|min:1',
            'selectedPeralatan.*' => 'exists:peralatan,id',
        ], [
            'selectedPeralatan.required' => 'Wajib memilih minimal 1 peralatan siaga untuk diajukan.',
        ]);

        $service->pinjam([
            'tujuan_keperluan'    => $this->tujuan_keperluan,
            'tgl_rencana_pinjam'  => $this->tgl_rencana_pinjam,
            'tgl_rencana_kembali' => $this->tgl_rencana_kembali,
        ], $this->selectedPeralatan);

        session()->flash('success', 'Peminjaman berhasil diajukan.');
        return redirect()->route('peminjaman.index');
    }

    public function render()
    {
        $peralatans = Peralatan::with('jenis')
            ->where('status', Peralatan::STATUS_TERSEDIA)
            ->get();

        return view('livewire.peminjaman.form', compact('peralatans'));
    }
}