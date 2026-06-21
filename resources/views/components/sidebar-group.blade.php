@props(['label' => '', 'collapsed' => false])

<div class="pt-2 first:pt-0">
    @if($label)
    <p x-show="!desktopCollapsed || mobileOpen"
       x-transition:enter="transition-opacity duration-150 delay-100"
       x-transition:enter-start="opacity-0"
       x-transition:enter-end="opacity-100"
       class="px-3 mb-1 text-[10px] font-semibold uppercase tracking-widest text-white/30 truncate">
        {{ $label }}
    </p>
    <div x-show="desktopCollapsed" class="border-t border-white/10 my-2 mx-2"></div>
    @endif

    <div class="space-y-0.5">
        {{ $slot }}
    </div>
</div>