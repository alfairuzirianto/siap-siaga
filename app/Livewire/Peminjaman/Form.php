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
    public string $nama_pengguna = '';
    public string $nip = '';
    public string $unit = '';
    public string $jabatan = '';
    public string $tujuan_keperluan = '';
    public string $tgl_pinjam = '';
    public string $tgl_kembali = '';
    public string $status = '';
    public array $selectedPeralatan = [];

    public function mount(): void
    {
        $this->tgl_pinjam = now()->format('Y-m-d');
        $this->tgl_kembali = now()->addDays(3)->format('Y-m-d');
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
            'nama_pengguna'       => 'required|string|max:255',
            'nip'                 => 'required|string|max:255',
            'unit'                => 'required|string|max:255',
            'jabatan'             => 'required|string|max:255',
            'tujuan_keperluan'    => 'required|string|max:255',
            'tgl_pinjam'          => 'required|date|after_or_equal:today',
            'tgl_kembali'         => 'required|date|after_or_equal:tgl_pinjam',
            'status'              => 'required|in:' . implode(',', Peminjaman::STATUS_PEMINJAMAN),
            'selectedPeralatan'   => 'required|array|min:1',
            'selectedPeralatan.*' => 'exists:peralatan,id',
        ], [
            'selectedPeralatan.required' => 'Wajib memilih minimal 1 peralatan siaga untuk diajukan.',
        ]);

        $service->pinjam([
            'nama_pengguna'     => $this->nama_pengguna,
            'nip'               => $this->nip,
            'unit'              => $this->unit,
            'jabatan'           => $this->jabatan,
            'tujuan_keperluan'  => $this->tujuan_keperluan,
            'tgl_pinjam'        => $this->tgl_pinjam,
            'tgl_kembali'       => $this->tgl_kembali,
            'status'            => $this->status,
        ], $this->selectedPeralatan);

        session()->flash('success', 'Peminjaman berhasil diajukan.');
        return $this->redirect(route('peminjaman.index'), navigate: true);
    }

    public function render()
    {
        $peralatans = Peralatan::with('jenis')
            ->where('status', Peralatan::STATUS_TERSEDIA)
            ->get();

        $statuses = Peminjaman::STATUS_PEMINJAMAN;

        return view('livewire.peminjaman.form', compact('peralatans', 'statuses'));
    }
}