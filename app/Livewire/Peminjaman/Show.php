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
    public array $dokumentasiItems = [];
    public $tempFotos = []; 

    public function mount(Peminjaman $peminjaman): void
    {
        $this->peminjaman = $peminjaman->load([
            'details.peralatan.jenis', 
            'beritaAcara.dokumentasi'
        ]);

        $hasBAPinjam = $this->peminjaman->beritaAcara
            ->where('jenis_ba', BeritaAcara::BA_PEMINJAMAN)
            ->isNotEmpty();

        if ($this->peminjaman->status === Peminjaman::DIPINJAM && !$hasBAPinjam) {
            $groupedDetails = $this->peminjaman->details->groupBy('peralatan.peralatan_jenis_id');

            foreach ($groupedDetails as $jenisId => $details) {
                $samplePeralatan = $details->first()->peralatan;
                
                $daftarNomorSeri = $details->map(fn($d) => $d->peralatan?->nomor_seri)->implode(', ');

                $this->dokumentasiItems[$jenisId] = [
                    'peralatan_jenis_id' => $jenisId,
                    'nama_jenis'         => $samplePeralatan?->jenis?->nama_jenis,
                    'nomor_seri'         => $daftarNomorSeri,
                    'keterangan'         => 'Serah Terima ' . $samplePeralatan?->jenis?->nama_jenis,
                    'foto_paths'         => [],
                ];
                $this->tempFotos[$jenisId] = [];
            }
        }
    }

    public function updatedTempFotos($value, $index): void
    {
        $this->validate([
            "tempFotos.{$index}.*" => 'image|max:2048'
        ], [
            "tempFotos.{$index}.*.max" => 'Ukuran gambar tidak boleh melebihi 2MB.',
            "tempFotos.{$index}.*.image" => 'Berkas harus berupa gambar (PNG, JPG, JPEG).'
        ]);

        foreach ($this->tempFotos[$index] as $file) {
            $this->dokumentasiItems[$index]['foto_paths'][] = $file->store('berita_acara_dokumentasi', 'public');
        }
        
        $this->tempFotos[$index] = []; 
    }

    public function removeFoto(int $itemIndex, int $fotoIndex): void
    {
        if (isset($this->dokumentasiItems[$itemIndex]['foto_paths'][$fotoIndex])) {
            unset($this->dokumentasiItems[$itemIndex]['foto_paths'][$fotoIndex]);
            
            $this->dokumentasiItems[$itemIndex]['foto_paths'] = array_values($this->dokumentasiItems[$itemIndex]['foto_paths']);
        }
    }

    /**
     * Pembuatan BA Peminjaman
     */
    public function generateBAPinjam(PeminjamanService $service)
    {
        $this->validate([
            'dokumentasiItems.*.keterangan' => 'required|string|max:255',
            'dokumentasiItems.*.foto_paths' => 'required|array|min:1',
        ], [
            'dokumentasiItems.*.foto_paths.required' => 'Wajib melampirkan dokumentasi serah terima.'
        ]);

        $service->generateBAPinjam($this->peminjaman, $this->dokumentasiItems);

        session()->flash('success', 'Berita Acara Peminjaman berhasil diterbitkan.');
        return $this->redirect(route('peminjaman.show', $this->peminjaman->id), navigate: true);
    }

    /**
     * Menangani penyelesaian pengembalian alat langsung (Otomatis buat BA Kembali)
     */
    public function selesaikanPeminjaman(PeminjamanService $service)
    {
        $service->selesaikanDanKembalikan($this->peminjaman);

        session()->flash('success', 'Peminjaman telah selesai dikembalikan dan BA Pengembalian berhasil diterbitkan.');
        return $this->redirect(route('peminjaman.show', $this->peminjaman->id), navigate: true);
    }

    public function render()
    {
        return view('livewire.peminjaman.show');
    }
}