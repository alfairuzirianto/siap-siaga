@props([
    'label',
    'value',
    'icon',
    'color'  => 'blue',
    'href'   => null,
    'trend'  => null,
])

@php
$colors = [
    'blue'   => ['bg' => 'bg-blue-50',   'icon' => 'text-blue-600',   'ring' => 'ring-blue-100'],
    'green'  => ['bg' => 'bg-green-50',  'icon' => 'text-green-600',  'ring' => 'ring-green-100'],
    'yellow' => ['bg' => 'bg-yellow-50', 'icon' => 'text-yellow-600', 'ring' => 'ring-yellow-100'],
    'orange' => ['bg' => 'bg-orange-50', 'icon' => 'text-orange-600', 'ring' => 'ring-orange-100'],
    'red'    => ['bg' => 'bg-red-50',    'icon' => 'text-red-600',    'ring' => 'ring-red-100'],
    'purple' => ['bg' => 'bg-purple-50', 'icon' => 'text-purple-600', 'ring' => 'ring-purple-100'],
];
$c = $colors[$color] ?? $colors['blue'];

$tag = $href ? 'a' : 'div';
@endphp

<{{ $tag }}
    @if($href) href="{{ $href }}" wire:navigate @endif
    class="card p-5 flex items-center gap-4 {{ $href ? 'hover:shadow-md transition-shadow cursor-pointer' : '' }}">

    <div class="w-12 h-12 rounded-xl {{ $c['bg'] }} ring-1 {{ $c['ring'] }}
                flex items-center justify-center shrink-0">
        <i class="ti {{ $icon }} {{ $c['icon'] }} text-2xl"></i>
    </div>

    <div class="min-w-0 flex-1">
        <p class="text-xs font-medium text-muted uppercase tracking-wide truncate">{{ $label }}</p>
        <p class="text-2xl font-bold text-slate-900 font-display leading-tight mt-0.5">{{ $value }}</p>
        @if($trend)
        <p class="text-xs mt-0.5 {{ str_starts_with($trend, '+') ? 'text-green-600' : 'text-red-500' }}">
            {{ $trend }} dari bulan lalu
        </p>
        @endif
    </div>

    @if($href)
    <i class="ti ti-chevron-right text-slate-300 shrink-0"></i>
    @endif
</{{ $tag }}>