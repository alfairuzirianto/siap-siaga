@props([
    'title',
    'subtitle' => null,
    'breadcrumbs' => [],
])

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div class="min-w-0">
        @if(count($breadcrumbs) > 0)
        <nav class="flex items-center gap-2 mb-2 flex-wrap text-[13px] md:text-sm">
            <a wire:navigate href="{{ route('dashboard') }}" class="text-slate-400 hover:text-slate-600 transition-colors flex items-center">
                <i class="ti ti-home text-base"></i>
            </a>
            @foreach($breadcrumbs as $crumb)
            <i class="ti ti-chevron-right text-slate-300 text-xs mt-0.5"></i>
            @if(isset($crumb['url']))
                <a wire:navigate href="{{ $crumb['url'] }}" class="text-slate-400 hover:text-slate-600 font-medium transition-colors truncate">
                    {{ $crumb['label'] }}
                </a>
            @else
                <span class="text-slate-600 font-semibold truncate">{{ $crumb['label'] }}</span>
            @endif
            @endforeach
        </nav>
        @endif

        <h1 class="page-title text-2xl font-bold text-slate-800 truncate">
            @if (isset($title)) {{ $title }} @else {{ $slot }} @endif
        </h1>

        @if($subtitle)
        <p class="text-sm text-muted mt-0.5">{{ $subtitle }}</p>
        @endif
    </div>

    @if($slot->isNotEmpty())
    <div class="flex items-center gap-2 shrink-0 self-end sm:self-center">
        {{ $slot }}
    </div>
    @endif
</div>