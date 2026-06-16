<?php

namespace App\Livewire\Pemeliharaan;

use App\Models\Pemeliharaan;
use App\Models\Peralatan;
use Livewire\Component;

class Form extends Component
{
    public ?Pemeliharaan $pemeliharaan = null;

    public ?string $nomor_pemeliharaan = null;
    public ?int $peralatan_id = null;
    public ?string $nama_petugas = null;
    public ?string $jenis_pemeliharaan = null;
    public ?string $tanggal_pemeliharaan = null;
    public int|float|null $biaya = null;
    public ?string $deskripsi = null;

    public function mount(?Pemeliharaan $pemeliharaan = null)
    {
        $this->pemeliharaan = $pemeliharaan;

        if ($pemeliharaan && $pemeliharaan->exists) {
            $this->nomor_pemeliharaan = $pemeliharaan->nomor_pemeliharaan;
            $this->peralatan_id = $pemeliharaan->peralatan_id;
            $this->nama_petugas = $pemeliharaan->nama_petugas;
            $this->jenis_pemeliharaan = $pemeliharaan->jenis_pemeliharaan;
            $this->tanggal_pemeliharaan = $pemeliharaan->tanggal_pemeliharaan?->format('Y-m-y');
            $this->biaya = $pemeliharaan->biaya;
            $this->deskripsi = $pemeliharaan->deskripsi;
        } else {
            $this->tanggal_pemeliharaan = now()->format('Y-m-d');
            $this->nomor_pemeliharaan = nomorPemeliharaan();
        }
    }

    protected function rules(): array
    {
        return [
            'nomor_pemeliharaan'   => 'required|string|max:255|unique:pemeliharaan,nomor_pemeliharaan,' . ($this->pemeliharaan?->id ?? 'NULL'),
            'peralatan_id'         => 'required|exists:peralatan,id',
            'nama_petugas'         => 'required|string|max:255',
            'jenis_pemeliharaan'   => 'required|in:' . implode(',', Pemeliharaan::JENIS_PEMELIHARAAN),
            'tanggal_pemeliharaan' => 'required|date',
            'biaya'                => 'required|numeric|min:0',
            'deskripsi'            => 'nullable|string',
        ];
    }

    public function save()
    {
        if ($this->pemeliharaan && $this->pemeliharaan->exists) {
            $this->authorize('update', $this->pemeliharaan);
        } else {
            $this->authorize('create', Pemeliharaan::class);
        }

        $validated = $this->validate();

        if (!$this->pemeliharaan || !$this->pemeliharaan->exists) {
            $validated['created_by'] = auth()->id();
            Pemeliharaan::create($validated);
            session()->flash('success', 'Catatan maintenance berhasil ditambahkan.');
        } else {
            $validated['updated_by'] = auth()->id();
            $this->pemeliharaan->update($validated);
            session()->flash('success', 'Catatan maintenance berhasil diperbarui.');
        }

        return redirect()->route('pemeliharaan.index');
    }

    public function render()
    {
        $peralatans = Peralatan::orderBy('nomor_seri')->get();

        return view('livewire.pemeliharaan.form', compact('peralatans'));
    }
}