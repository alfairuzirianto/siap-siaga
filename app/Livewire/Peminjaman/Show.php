<?php

namespace App\Livewire\Peminjaman;

use App\Models\Peminjaman;
use App\Models\BeritaAcara;
use App\Services\PeminjamanService;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Title('Detail Peminjaman | SiapSiaga')]
class Show extends Component
{
    use WithFileUploads;

    public Peminjaman $peminjaman;
    
    public bool $isApprovalModalOpen = false;
    public string $keterangan = '';
    public string $targetStatus = '';

    public array $dokumentasiItems = [];
    public $tempFotos = []; 

    public function mount(Peminjaman $peminjaman): void
    {
        $this->peminjaman = $peminjaman->load([
            'pengguna', 
            'approverPinjam', 
            'approverKembali', 
            'details.peralatan.jenis', 
            'beritaAcara.dokumentasi'
        ]);

        $isAdmin = ($this->peminjaman->status === Peminjaman::PINJAM_DISETUJUI && auth()->user()->isAdmin());
        $isPengguna  = ($this->peminjaman->status === Peminjaman::DIPINJAM && auth()->id() === $this->peminjaman->pengguna_id);

        if ($isAdmin || $isPengguna) {
            foreach ($this->peminjaman->details as $index => $detail) {
                $this->dokumentasiItems[$index] = [
                    'peralatan_id' => $detail->peralatan_id,
                    'nomor_seri'   => $detail->peralatan?->nomor_seri,
                    'nama_jenis'   => $detail->peralatan?->jenis?->nama_jenis,
                    'keterangan'   => $this->peminjaman->status === Peminjaman::DIPINJAM 
                        ? 'Kondisi alat dikembalikan dalam keadaan normal.'
                        : 'Serah Terima ' . $detail->peralatan?->jenis?->nama_jenis,
                    'foto_paths'   => [],
                ];
                $this->tempFotos[$index] = [];
            }
        }
    }

    public function openModal(string $status)
    {
        if (!auth()->user()->isSupervisor()) abort(403);
        
        $this->targetStatus = $status;
        $this->keterangan = '';
        $this->isApprovalModalOpen = true;
    }

    public function validasi(PeminjamanService $service)
    {
        if (!auth()->user()->isSupervisor()) abort(403);

        $service->validasi($this->peminjaman, $this->targetStatus, $this->keterangan);
        
        $this->isApprovalModalOpen = false;
        session()->flash('success', 'Pengajuan selesai divalidasi.');
        return redirect()->route('validasi');
    }

    public function updatedTempFotos($value, $index): void
    {
        $this->validate(["tempFotos.{$index}.*" => 'image|max:2048']);

        foreach ($this->tempFotos[$index] as $file) {
            $this->dokumentasiItems[$index]['foto_paths'][] = $file->store('berita_acara_dokumentasi', 'public');
        }
        $this->tempFotos[$index] = []; 
    }

    public function removeFoto(int $itemIndex, int $fotoIndex): void
    {
        unset($this->dokumentasiItems[$itemIndex]['foto_paths'][$fotoIndex]);
        $this->dokumentasiItems[$itemIndex]['foto_paths'] = array_values($this->dokumentasiItems[$itemIndex]['foto_paths']);
    }

    public function ajukanPengembalian(PeminjamanService $service)
    {
        if (auth()->id() !== $this->peminjaman->pengguna_id) abort(403);

        $this->validate([
            'dokumentasiItems.*.keterangan' => 'required|string|max:255',
            'dokumentasiItems.*.foto_paths' => 'required|array|min:1',
        ], [
            'dokumentasiItems.*.foto_paths.required' => 'Wajib melampirkan dokumentasi pengembalian.'
        ]);

        $service->ajukanPengembalian($this->peminjaman, $this->dokumentasiItems);

        session()->flash('success', 'Pengajuan pengembalian berhasil dikirim ke Supervisor.');
        return redirect()->route('peminjaman.index');
    }

    public function generateBAPinjam(PeminjamanService $service)
    {
        if (!auth()->user()->isAdmin()) abort(403);

        $this->validate([
            'dokumentasiItems.*.keterangan' => 'required|string|max:255',
            'dokumentasiItems.*.foto_paths' => 'required|array|min:1',
        ], [
            'dokumentasiItems.*.foto_paths.required' => 'Wajib melampirkan dokumentasi serah terima.'
        ]);

        $service->generateBA($this->peminjaman, $this->dokumentasiItems, BeritaAcara::BA_PEMINJAMAN);

        session()->flash('success', 'Berita Acara Peminjaman berhasil diterbitkan.');
    }

    public function generateBAKembali(PeminjamanService $service)
    {
        if (!auth()->user()->isAdmin()) abort(403);

        $this->validate([
            'dokumentasiItems.*.keterangan' => 'required|string|max:255',
            'dokumentasiItems.*.foto_paths' => 'required|array|min:1',
        ], [
            'dokumentasiItems.*.foto_paths.required' => 'Wajib melampirkan dokumentasi pengembalian.'
        ]);

        $service->generateBA($this->peminjaman, [], BeritaAcara::BA_PENGEMBALIAN);

        session()->flash('success', 'Berita Acara Pengembalian berhasil diterbitkan.');
    }

    public function render()
    {
        return view('livewire.peminjaman.show');
    }
}