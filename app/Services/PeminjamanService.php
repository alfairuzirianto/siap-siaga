<?php

namespace App\Services;

use App\Models\Peminjaman;
use App\Models\PeminjamanDetail;
use App\Models\BeritaAcara;
use App\Models\BeritaAcaraDokumentasi;
use App\Models\Peralatan;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PeminjamanService
{
    /**
     * Pengajuan Peminjaman
     */
    public function pinjam(array $data, array $peralatanIds): Peminjaman
    {
        return DB::transaction(function () use ($data, $peralatanIds) {
            $data['nomor_peminjaman'] = nomorPeminjaman();
            $isSelesai = ($data['status'] === Peminjaman::SELESAI);

            $peminjaman = Peminjaman::create($data);

            foreach ($peralatanIds as $id) {
                PeminjamanDetail::create([
                    'peminjaman_id' => $peminjaman->id,
                    'peralatan_id' => $id,
                ]);

                if ($data['status'] === Peminjaman::DIPINJAM) {
                    Peralatan::where('id', $id)->update([
                        'status' => $isSelesai ? Peralatan::STATUS_TERSEDIA : Peralatan::STATUS_DIPINJAM,
                    ]);
                }
            }

            if ($isSelesai) {
                // BA Peminjaman
                BeritaAcara::create([
                    'peminjaman_id' => $peminjaman->id,
                    'nomor_ba'      => nomorBA(BeritaAcara::BA_PEMINJAMAN),
                    'jenis_ba'      => BeritaAcara::BA_PEMINJAMAN,
                    'token'         => Str::uuid(),
                    'is_valid'      => true,
                ]);

                // BA Pengembalian
                BeritaAcara::create([
                    'peminjaman_id' => $peminjaman->id,
                    'nomor_ba'      => nomorBA(BeritaAcara::BA_PENGEMBALIAN),
                    'jenis_ba'      => BeritaAcara::BA_PENGEMBALIAN,
                    'token'         => Str::uuid(),
                    'is_valid'      => true,
                ]);
            }

            $this->logActivity(
                ActivityLog::AKSI_CREATE,
                'peminjaman',
                $peminjaman->id,
                null,
                $peminjaman->toArray()
            );

            return $peminjaman;
        });
    }

    /**
     * Terbitkan BA Peminjaman
     */
    public function generateBAPinjam(Peminjaman $peminjaman, array $dokumentasiItems): BeritaAcara
    {
        return DB::transaction(function () use ($peminjaman, $dokumentasiItems) {
            $beritaAcara = BeritaAcara::create([
                'peminjaman_id' => $peminjaman->id,
                'nomor_ba'      => nomorBA(BeritaAcara::BA_PEMINJAMAN),
                'jenis_ba'      => BeritaAcara::BA_PEMINJAMAN,
                'token'         => Str::uuid(),
                'is_valid'      => true,
            ]);

            foreach ($dokumentasiItems as $item) {
                BeritaAcaraDokumentasi::create([
                    'berita_acara_id' => $beritaAcara->id,
                    'keterangan'      => $item['keterangan'],
                    'foto'            => $item['foto_paths'],
                ]);
            }

            $this->logActivity(
                ActivityLog::AKSI_GENERATE,
                'berita_acara',
                $beritaAcara->id,
                null,
                $beritaAcara->toArray()
            );

            return $beritaAcara;
        });
    }

    /**
     * Menyelesaikan Peminjaman & Otomatis Terbitkan BA Pengembalian
     */
    public function selesaikanDanKembalikan(Peminjaman $peminjaman): BeritaAcara
    {
        return DB::transaction(function () use ($peminjaman) {
            $oldPeminjaman = $peminjaman->toArray();

            $peminjaman->update(['status' => Peminjaman::SELESAI]);

            foreach ($peminjaman->details as $detail) {
                $detail->peralatan->update([
                    'status' => Peralatan::STATUS_TERSEDIA
                ]);
            }

            $beritaAcara = BeritaAcara::create([
                'peminjaman_id' => $peminjaman->id,
                'nomor_ba'      => nomorBA(BeritaAcara::BA_PENGEMBALIAN),
                'jenis_ba'      => BeritaAcara::BA_PENGEMBALIAN,
                'token'         => Str::uuid(),
                'is_valid'      => true,
            ]);

            $this->logActivity(
                ActivityLog::AKSI_GENERATE,
                'berita_acara',
                $beritaAcara->id,
                null,
                $beritaAcara->toArray()
            );
            
            $this->logActivity(
                ActivityLog::AKSI_UPDATE,
                'peminjaman',
                $peminjaman->id,
                $oldPeminjaman,
                $peminjaman->toArray()
            );

            return $beritaAcara;
        });
    }

    /**
     * Menghapus Berita Acara
     */
    public function removeBA(BeritaAcara $ba): void
    {
        DB::transaction(function () use ($ba) {
            $peminjaman = $ba->peminjaman;

            if ($ba->jenis_ba === BeritaAcara::BA_PEMINJAMAN) {
                $ba->update(['nomor_ba' => 'DRAFT-' . Str::upper(Str::random(6)), 'is_valid' => false]);

                $peminjaman->update(['status' => Peminjaman::PINJAM_DISETUJUI]);
                foreach ($peminjaman->details as $detail) {
                    $detail->peralatan->update(['status' => Peralatan::STATUS_TERSEDIA]);
                }
            } else {
                $ba->update(['is_valid' => false]);

                $peminjaman->update(['status' => Peminjaman::KEMBALI_DISETUJUI, 'tgl_realisasi_kembali' => null]);
                foreach ($peminjaman->details as $detail) {
                    $detail->peralatan->update(['status' => Peralatan::STATUS_DIPINJAM]);
                }
            }
        });
    }
    
    private function logActivity(string $aksi, string $table, int $recordId, ?array $old, ?array $new): void
    {
        ActivityLog::create([
            'user_id' => auth()->id(),
            'aksi' => $aksi,
            'nama_table' => $table,
            'record_id' => $recordId,
            'old_value' => $old,
            'new_value' => $new,
        ]);
    }
}
