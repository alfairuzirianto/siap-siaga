@props(['status', 'type' => 'alat'])

@php
use App\Models\Peminjaman;
use App\Models\Peralatan;

$config = [
    // Status Peralatan
    Peralatan::STATUS_TERSEDIA  => ['class' => 'bg-green-100 text-green-700 ring-green-200', 'label' => 'Tersedia'],
    Peralatan::STATUS_DIPINJAM  => ['class' => 'bg-yellow-100 text-yellow-700 ring-yellow-200', 'label' => 'Dipinjam'],
    'Maintenance'               => ['class' => 'bg-orange-100 text-orange-700 ring-orange-200', 'label' => 'Maintenance'],
    'Rusak'                     => ['class' => 'bg-red-100 text-red-700 ring-red-200', 'label' => 'Rusak'],
    
    // Status Peminjaman
    Peminjaman::DIPINJAM          => ['class' => 'bg-amber-50 text-amber-700 ring-amber-600/20', 'label' => 'Sedang Dipinjam'],
    Peminjaman::SELESAI           => ['class' => 'bg-green-100 text-green-700 ring-green-200', 'label' => 'Selesai'],
];

$current = $config[$status] ?? ['class' => 'bg-gray-100 text-gray-600 ring-gray-200', 'label' => $status];
@endphp

<span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ring-1 ring-inset {{ $current['class'] }}">
    {{ $current['label'] }}
</span>