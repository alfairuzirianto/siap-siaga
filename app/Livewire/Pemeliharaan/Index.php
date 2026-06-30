<?php

namespace App\Livewire\Pemeliharaan;

use App\Models\Pemeliharaan;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Maintenance | SiapSiaga')]
class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterJenis = '';

    protected $updatesQueryString = ['search', 'filterJenis'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function delete(Pemeliharaan $pemeliharaan)
    {
        $pemeliharaan->delete();
        session()->flash('success', 'Data pemeliharaan berhasil dihapus.');
    }

    public function render()
    {
        $pemeliharaans = Pemeliharaan::with(['peralatan.jenis', 'creator'])
            ->when($this->search, fn ($query) =>
                $query->where('nomor_pemeliharaan', 'like', '%' . $this->search . '%')
                  ->orWhere('nama_petugas', 'like', '%' . $this->search . '%')
                  ->orWhereHas('peralatan', fn ($q) =>
                      $q->where('nomor_seri', 'like', '%' . $this->search . '%')
                  )
            )
            ->when($this->filterJenis, fn ($query) =>
                $query->where('jenis_pemeliharaan', $this->filterJenis)
            )
            ->latest('tanggal_pemeliharaan')
            ->paginate(10);

        $totalBiaya = Pemeliharaan::sum('biaya');
        $countPreventif = Pemeliharaan::where('jenis_pemeliharaan', Pemeliharaan::JENIS_PREVENTIF)->count();
        $countKorektif = Pemeliharaan::where('jenis_pemeliharaan', Pemeliharaan::JENIS_KOREKTIF)->count();

        return view('livewire.pemeliharaan.index', compact(
            'pemeliharaans', 'totalBiaya', 'countPreventif', 'countKorektif'
        ));
    }
}