@props([
    'icon'    => 'ti-inbox',
    'title'   => 'Data belum tersedia',
    'message' => null,
    'action'  => null,
])

<div class="flex flex-col items-center justify-center py-16 text-center">
    <div class="w-16 h-16 rounded-2xl bg-slate-100 flex items-center justify-center mb-4">
        <i class="ti {{ $icon }} text-slate-400 text-3xl"></i>
    </div>
    <h3 class="text-sm font-semibold text-slate-700 mb-1">{{ $title }}</h3>
    @if($message)
    <p class="text-sm text-muted max-w-xs">{{ $message }}</p>
    @endif
    @if($action)
    <a href="{{ $action['href'] }}" class="btn-primary mt-5">
        <i class="ti ti-plus"></i> {{ $action['label'] }}
    </a>
    @endif
    {{ $slot }}
</div>