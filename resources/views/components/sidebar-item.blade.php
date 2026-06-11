@props(['route', 'icon', 'label'])

@php
    $baseRoute = str_replace('.index', '', $route);
    $isActive = request()->routeIs($route) || request()->routeIs($baseRoute . '.*');
@endphp

<a href="{{ route($route) }}"
   wire:navigate
   class="{{ $isActive ? 'nav-item-active' : 'nav-item' }}"
   title="{{ $label }}">
    <i class="ti {{ $icon }} text-xl shrink-0"></i>
    <span x-show="!desktopCollapsed || mobileOpen"
          class="truncate text-sm">{{ $label }}</span>
    @if($isActive)
    <span x-show="!desktopCollapsed || mobileOpen"
          class="ml-auto w-1.5 h-1.5 rounded-full bg-primary-300 shrink-0"></span>
    @endif
</a>