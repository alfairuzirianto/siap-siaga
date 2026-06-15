@props(['status', 'type' => 'alat'])

@php
$config = [
    // Status Peralatan
    'Tersedia'     => ['class' => 'bg-green-100 text-green-700 ring-green-200', 'label' => 'Tersedia'],
    'Dipinjam'     => ['class' => 'bg-yellow-100 text-yellow-700 ring-yellow-200', 'label' => 'Dipinjam'],
    'Maintenance'  => ['class' => 'bg-orange-100 text-orange-700 ring-orange-200', 'label' => 'Maintenance'],
    'Rusak'        => ['class' => 'bg-red-100 text-red-700 ring-red-200', 'label' => 'Rusak'],
    
    // Status Pengajuan
    'Diajukan'     => ['class' => 'bg-blue-50 text-blue-700 ring-blue-600/20', 'label' => 'Menunggu Approval'],
    'Dibatalkan'   => ['class' => 'bg-slate-100 text-slate-600 ring-slate-600/10', 'label' => 'Dibatalkan'],
    'Disetujui'    => ['class' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20', 'label' => 'Disetujui'],
    'Dipinjam'     => ['class' => 'bg-amber-50 text-amber-700 ring-amber-600/20', 'label' => 'Dipinjam'],
    'Ditolak'      => ['class' => 'bg-rose-50 text-rose-700 ring-rose-600/20', 'label' => 'Ditolak'],
    'Dikembalikan' => ['class' => 'bg-green-50 text-green-700 ring-green-600/20', 'label' => 'Selesai'],
];

$current = $config[$status] ?? ['class' => 'bg-gray-100 text-gray-600 ring-gray-200', 'label' => $status];
@endphp

<span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ring-1 ring-inset {{ $current['class'] }}">
    {{ $current['label'] }}
</span>