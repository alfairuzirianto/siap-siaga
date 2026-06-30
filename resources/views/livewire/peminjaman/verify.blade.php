{{-- Di dalam resources/views/peminjaman/verify-index.blade.php --}}
<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verifikasi Dokumen — SiapSiaga</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=dm-sans:400,500,600,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    @vite(['resources/css/app.css'])
</head>
<body class="h-full bg-slate-50 font-body antialiased">
    <div class="min-h-screen flex flex-col items-center justify-center p-4">
        <div class="w-full max-w-lg">
            
            <div class="flex items-center justify-center gap-3 mb-8">
                <img src="{{ asset('assets/logo/pln-square.png') }}" class="h-12">
                <div>
                    <p class="font-semibold text-primary-800">SiapSiaga</p>
                    <p class="text-xs text-slate-400">Verifikasi Dokumen Digital</p>
                </div>
            </div>

            @if($ba)
                @if($ba->is_valid)
                    <div class="bg-white rounded-2xl border border-slate-200 p-6 text-center mb-5 shadow-sm">
                        <div class="w-16 h-16 rounded-full bg-green-100 flex items-center justify-center mx-auto mb-4">
                            <i class="ti ti-circle-check text-green-600 text-4xl"></i>
                        </div>
                        <h1 class="text-xl font-bold text-green-700 mb-1">Dokumen Terverifikasi</h1>
                        <p class="text-sm text-slate-500">Dokumen ini <strong>Asli</strong> dan diterbitkan resmi oleh sistem SiapSiaga.</p>
                    </div>
                @else
                    <div class="bg-white rounded-2xl border border-red-200 p-6 text-center mb-5 shadow-sm">
                        <div class="w-16 h-16 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-4">
                            <i class="ti ti-x text-red-600 text-4xl"></i>
                        </div>
                        <h1 class="text-xl font-bold text-red-700 mb-1">Dokumen Tidak Valid</h1>
                        <p class="text-sm text-slate-500">Dokumen draf ini belum mendapatkan pengesahan resmi.</p>
                    </div>
                @endif

                {{-- Detail Berita Acara --}}
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden mb-4 shadow-sm">
                    <div class="px-5 py-3.5 border-b border-slate-100 bg-slate-50 flex items-center gap-2 font-bold text-slate-800 text-sm">
                        <i class="ti ti-file-text text-primary-600"></i> Informasi Dokumen
                    </div>
                    <div class="divide-y divide-slate-100 text-sm">
                        <div class="flex px-5 py-3"><span class="text-slate-400 w-32 shrink-0">Nomor BA</span><span class="font-semibold text-slate-800">{{ $ba->nomor_ba }}</span></div>
                        <div class="flex px-5 py-3"><span class="text-slate-400 w-32 shrink-0">Jenis Dokumen</span><span class="text-slate-700">BA {{ $ba->jenis_ba }} Barang</span></div>
                        <div class="flex px-5 py-3"><span class="text-slate-400 w-32 shrink-0">Tanggal Terbit</span><span class="text-slate-700">{{ $ba->created_at?->isoFormat('D MMMM Y, HH:mm') }} WIB</span></div>
                    </div>
                </div>

                {{-- Detail Transaksi Peminjaman --}}
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden mb-4 shadow-sm">
                    <div class="px-5 py-3.5 border-b border-slate-100 bg-slate-50 flex items-center gap-2 font-bold text-slate-800 text-sm">
                        <i class="ti ti-file-description text-primary-600"></i> Data Peminjaman
                    </div>
                    <div class="divide-y divide-slate-100 text-sm">
                        <div class="flex px-5 py-3"><span class="text-slate-400 w-32 shrink-0">No. Nota</span><span class="font-semibold">{{ $ba->peminjaman?->nomor_peminjaman }}</span></div>
                        <div class="flex px-5 py-3"><span class="text-slate-400 w-32 shrink-0">Pengguna</span><span class="text-slate-700">{{ $ba->peminjaman?->nama_pengguna }}</span></div>
                        <div class="flex px-5 py-3"><span class="text-slate-400 w-32 shrink-0">Keperluan</span><span class="text-slate-700">{{ $ba->peminjaman?->tujuan_keperluan }}</span></div>
                    </div>
                </div>
            @else
                <div class="bg-white rounded-2xl border border-slate-200 p-6 text-center shadow-sm">
                    <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-4">
                        <i class="ti ti-file-off text-slate-400 text-4xl"></i>
                    </div>
                    <h1 class="text-xl font-bold text-slate-700 mb-1">Dokumen Tidak Ditemukan</h1>
                    <p class="text-sm text-slate-500">Token QR Code tidak terdaftar atau berkas palsu.</p>
                </div>
            @endif
        </div>
    </div>
</body>
</html>