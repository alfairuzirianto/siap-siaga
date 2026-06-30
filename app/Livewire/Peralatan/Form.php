<?php

namespace App\Livewire\Peralatan;

use App\Models\Peralatan;
use App\Models\PeralatanJenis;
use Livewire\Component;
use Livewire\WithFileUploads;

class Form extends Component
{
    use WithFileUploads;

    public ?Peralatan $peralatan = null;

    public ?string $nomor_seri = null;
    public ?int $peralatan_jenis_id = null;
    public ?string $status = null;
    public int|float|null $kapasitas = null;
    public ?string $satuan = null;
    public ?string $lokasi = null;
    public $foto;
    public ?string $existingFoto = null;

    public function mount(?Peralatan $peralatan = null)
    {
        $this->peralatan = $peralatan;

        if ($peralatan) {
            $this->nomor_seri = $peralatan->nomor_seri;
            $this->peralatan_jenis_id = $peralatan->peralatan_jenis_id;
            $this->status = $peralatan->status;
            $this->kapasitas = $peralatan->kapasitas;
            $this->satuan = $peralatan->satuan;
            $this->lokasi = $peralatan->lokasi;
            $this->existingFoto = $peralatan->foto;
        }
    }

    protected function rules(): array
    {
        return [
            'nomor_seri'           => 'required|string|max:255|unique:peralatan,nomor_seri' . ($this->peralatan ? ',' . $this->peralatan->id : ''),
            'peralatan_jenis_id'   => 'required|exists:peralatan_jenis,id',
            'kapasitas'            => 'nullable|numeric|min:0',
            'satuan'               => 'nullable|string|max:50',
            'lokasi'               => 'required|string|max:255',
            'foto'                 => 'nullable|image|max:2048',
        ];
    }

    public function save()
    {
        $validated = $this->validate();

        if ($this->foto) {
            $validated['foto'] = $this->foto->store('peralatan', 'public');
        } else {
            unset($validated['foto']);
        }

        if (!$this->peralatan) {
            $validated['created_by'] = auth()->id();
            $validated['status'] = Peralatan::STATUS_TERSEDIA;
            Peralatan::create($validated);
            session()->flash('success', 'Peralatan berhasil ditambahkan.');
        } else {
            $validated['updated_by'] = auth()->id();
            $this->peralatan->update($validated);
            session()->flash('success', 'Peralatan berhasil diperbarui.');
        }

        return $this->redirect(route('peralatan.index'), navigate: true);
    }

    public function render()
    {
        $jenis = PeralatanJenis::get();
        $title = $this->peralatan ? 'Edit Peralatan' : 'Tambah Peralatan';

        return view('livewire.peralatan.form', compact('jenis', 'title'));
    }
}
