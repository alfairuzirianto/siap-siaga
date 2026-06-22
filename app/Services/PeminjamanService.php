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
            $data['status'] = Peminjaman::PINJAM_DIAJUKAN;
            $data['pengguna_id'] = auth()->id();

            $peminjaman = Peminjaman::create($data);

            foreach ($peralatanIds as $id) {
                PeminjamanDetail::create([
                    'peminjaman_id' => $peminjaman->id,
                    'peralatan_id' => $id,
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
     * Pembatalan Pengajuan Peminjaman
     */
    public function batalPinjam(Peminjaman $peminjaman): void
    {
        DB::transaction(function () use ($peminjaman) {
            $oldValue = $peminjaman->toArray();
            $peminjaman->update(['status' => Peminjaman::PINJAM_DIBATALKAN]);

            $this->logActivity(
                ActivityLog::AKSI_UPDATE,
                'peminjaman',
                $peminjaman->id,
                $oldValue,
                $peminjaman->toArray()
            );
        });
    }

    /**
     * Pengajuan Pengembalian oleh Pengguna
     */
    public function ajukanPengembalian(Peminjaman $peminjaman, array $dokumentasiItems): void
    {
        DB::transaction(function () use ($peminjaman, $dokumentasiItems) {
            $oldValue = $peminjaman->toArray();

            $oldDraft = $peminjaman->beritaAcara()
                ->where('jenis_ba', BeritaAcara::BA_PENGEMBALIAN)
                ->where('is_valid', false)
                ->get();

            foreach ($oldDraft as $data) {
                $data->dokumentasi()->delete();
                $data->delete();
            }

            // 1. Update status peminjaman menjadi Diajukan Kembali
            $peminjaman->update(['status' => Peminjaman::KEMBALI_DIAJUKAN]);

            // 2. Simpan sementara data dokumentasi fisik penyerahan dari pengguna ke tabel BeritaAcaraDokumentasi.
            $baDraft = BeritaAcara::create([
                'peminjaman_id' => $peminjaman->id,
                'nomor_ba' => nomorBA(BeritaAcara::BA_PENGEMBALIAN),
                'jenis_ba' => BeritaAcara::BA_PENGEMBALIAN,
                'token' => Str::uuid(),
                'is_valid' => false,
            ]);

            foreach ($dokumentasiItems as $item) {
                BeritaAcaraDokumentasi::create([
                    'berita_acara_id' => $baDraft->id,
                    'keterangan' => $item['keterangan'],
                    'foto' => $item['foto_paths'],
                ]);
            }

            $this->logActivity(
                ActivityLog::AKSI_UPDATE,
                'peminjaman',
                $peminjaman->id,
                $oldValue,
                $peminjaman->toArray()
            );
        });
    }

    /**
     * Validasi Supervisor
     */
    public function validasi(Peminjaman $peminjaman, string $status, ?string $alasan = null): void
    {
        DB::transaction(function () use ($peminjaman, $status, $alasan) {
            $oldValue = $peminjaman->toArray();
            $updateData = ['status' => $status];

            if ($peminjaman->status === Peminjaman::PINJAM_DIAJUKAN) {
                $updateData['approver_pinjam'] = auth()->id();
                $updateData['keterangan_pinjam'] = $alasan;
                $updateData['peminjaman_approved_at'] = now();
                $aksi = ($status === Peminjaman::PINJAM_DISETUJUI) ? ActivityLog::AKSI_APPROVE : ActivityLog::AKSI_REJECT;
            } 
            else if ($peminjaman->status === Peminjaman::KEMBALI_DIAJUKAN) {
                $updateData['approver_kembali'] = auth()->id();
                $updateData['keterangan_kembali'] = $alasan;
                $updateData['pengembalian_approved_at'] = now();
                $aksi = ($status === Peminjaman::KEMBALI_DISETUJUI) ? ActivityLog::AKSI_APPROVE : ActivityLog::AKSI_REJECT;
            }

            $peminjaman->update($updateData);

            $this->logActivity(
                $aksi,
                'peminjaman',
                $peminjaman->id,
                $oldValue,
                $peminjaman->toArray());
        });
    }

    /**
     * Generate BA Peminjaman
     */
    public function generateBA(Peminjaman $peminjaman, array $dokumentasiItems, string $jenisBA): BeritaAcara
    {
        return DB::transaction(function () use ($peminjaman, $dokumentasiItems, $jenisBA) {
            $oldPeminjaman = $peminjaman->toArray();

            if ($jenisBA === BeritaAcara::BA_PENGEMBALIAN) {
                $beritaAcara = $peminjaman->beritaAcara()
                    ->where('jenis_ba', BeritaAcara::BA_PENGEMBALIAN)
                    ->where('is_valid', false)
                    ->first();

                $beritaAcara->update(['is_valid' => true]);
            } else {
                $beritaAcara = $peminjaman->beritaAcara()
                    ->where('jenis_ba', BeritaAcara::BA_PEMINJAMAN)
                    ->first();

                if ($beritaAcara) {
                    $beritaAcara->update(['nomor_ba' => nomorBA($jenisBA), 'is_valid' => true]);
                } else {
                    $beritaAcara = BeritaAcara::create([
                        'peminjaman_id' => $peminjaman->id,
                        'nomor_ba' => nomorBA($jenisBA),
                        'jenis_ba' => $jenisBA,
                        'token' => Str::uuid(),
                        'is_valid' => true,
                    ]);
        
                    foreach ($dokumentasiItems as $item) {
                        BeritaAcaraDokumentasi::create([
                            'berita_acara_id' => $beritaAcara->id,
                            'keterangan' => $item['keterangan'],
                            'foto' => $item['foto_paths'],
                        ]);
                    }
                }
            }

            if ($jenisBA === BeritaAcara::BA_PEMINJAMAN) {
                $peminjaman->update(['status' => Peminjaman::DIPINJAM]);
                foreach ($peminjaman->details as $detail) {
                    $detail->peralatan->update(['status' => Peralatan::STATUS_DIPINJAM]);
                }
            } else {
                $peminjaman->update(['status' => Peminjaman::SELESAI, 'tgl_realisasi_kembali' => now()]);
                foreach ($peminjaman->details as $detail) {
                    $detail->peralatan->update(['status' => Peralatan::STATUS_TERSEDIA]);
                }
            }

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
