<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#1e3a5f">

    <title>{{ $title ?? config('app.name', 'SiapSiaga') }}</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('assets/logo/favicon.ico') }}">

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=dm-sans:400,500,600,700|inter:400,500,600&display=swap" rel="stylesheet">

    {{-- Tabler Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

    {{-- Sweet Alert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="h-full bg-bg font-body antialiased" 
      x-data="{ desktopCollapsed: false, mobileOpen: false, moreOpen: false }" 
      x-cloak>

    {{-- Mobile overlay --}}
    <div x-show="mobileOpen"
        x-transition:enter="transition-opacity duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="mobileOpen = false"
        class="fixed inset-0 z-20 bg-black/40 md:hidden">
    </div>

    {{-- SIDEBAR --}}
    <aside :class="[
            'fixed inset-y-0 left-0 z-30 flex flex-col transition-all duration-300',
            'bg-primary-900',
            desktopCollapsed ? 'md:w-16' : 'md:w-64',
            mobileOpen ? 'w-64 translate-x-0' : 'w-64 -translate-x-full md:translate-x-0'
        ]">

        {{-- Logo --}}
        <div class="flex items-center h-14 px-4 shrink-0 border-b border-white/10"
            :class="desktopCollapsed ? 'md:justify-center md:px-0' : ''">
            <div class="flex items-center gap-3 min-w-0">
                <div class="w-6 h-6 rounded-lg flex items-center justify-center shrink-0">
                    <img src="{{ asset('assets/logo/pln-square.png') }}" alt="Logo PLN">
                </div>
                <div x-show="!desktopCollapsed || mobileOpen"
                    x-transition:enter="transition-opacity duration-150 delay-100"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="transition-opacity duration-100"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="min-w-0">
                    <p class="text-white font-semibold text-sm leading-tight font-display truncate">SIAGA PLN</p>
                    <p class="text-white/40 text-[10px] leading-tight truncate">Peralatan Siaga</p>
                </div>
            </div>
        </div>

        {{-- Navigasi --}}
        <nav class="flex-1 overflow-y-auto py-3 px-2 flex flex-col gap-1">
            {{-- Utama --}}
            <x-sidebar-group label="Utama">
                @if (auth()->user()->hasRole(App\Models\User::ROLE_ADMIN, App\Models\User::ROLE_SUPERVISOR))
                <x-sidebar-item route="dashboard" icon="ti-dashboard" label="Dashboard" />
                @endif
                    
                @can('viewAny', App\Models\Peralatan::class)
                <x-sidebar-item route="peralatan.index" icon="ti-box" label="Peralatan" />
                @endcan

                @can('viewAny', App\Models\Peminjaman::class)
                <x-sidebar-item route="dashboard" icon="ti-file-description" label="Peminjaman" />
                @endcan
            </x-sidebar-group>

            {{-- Operasional --}}
            @can('viewAny', App\Models\Pemeliharaan::class)
            <x-sidebar-group label="Operasional">
                <x-sidebar-item route="dashboard" icon="ti-tool"            label="Maintenance" />
            </x-sidebar-group>
            @endcan

            {{-- Supervisor --}}
            @if(auth()->user()->isSupervisor())
            <x-sidebar-group label="Supervisor">
                <x-sidebar-item route="dashboard" icon="ti-shield-check"   label="Approval Pinjam" />
                <x-sidebar-item route="dashboard" icon="ti-package-import" label="Validasi Kembali" />
                <x-sidebar-item route="dashboard"    icon="ti-chart-bar"      label="Laporan" />
            </x-sidebar-group>
            @endif

            {{-- Administrasi --}}
            @if(auth()->user()->isAdmin())
            <x-sidebar-group label="Administrasi">
                <x-sidebar-item route="dashboard" icon="ti-users" label="Kelola User" />
                <x-sidebar-item route="dashboard" icon="ti-history" label="Log Aktivitas" />
            </x-sidebar-group>
            @endif

        </nav>

        {{-- Footer sidebar --}}
        <div class="shrink-0 border-t border-white/10 p-3">
            <div class="flex items-center gap-3"
                :class="desktopCollapsed ? 'md:justify-center' : ''">
                <div class="w-8 h-8 rounded-full bg-primary-600 flex items-center justify-center shrink-0">
                    <span class="text-white text-xs font-semibold">
                        {{ strtoupper(substr(auth()->user()->nama_lengkap, 0, 2)) }}
                    </span>
                </div>
                <div x-show="!desktopCollapsed || mobileOpen" class="min-w-0 flex-1">
                    <p class="text-white text-xs font-medium truncate">{{ auth()->user()->nama_lengkap }}</p>
                    <p class="text-white/40 text-[10px] truncate">{{ auth()->user()->role }}</p>
                </div>
                <a x-show="!desktopCollapsed || mobileOpen"
                   href="{{ route('profile.edit') }}"
                   class="text-white/40 hover:text-white transition-colors shrink-0"
                   title="Profil">
                    <i class="ti ti-settings text-base"></i>
                </a>
            </div>
        </div>
    </aside>

    {{-- KONTEN UTAMA --}}
    <div :class="[
            'flex flex-col min-h-screen transition-all duration-300',
            desktopCollapsed ? 'md:ml-16' : 'md:ml-64'
        ]">

        {{-- TOPBAR --}}
        <header class="sticky top-0 z-10 h-14 bg-white border-b border-slate-200
                    flex items-center gap-3 px-4 shrink-0">

            {{-- Toggle mobile --}}
            <button @click="mobileOpen = !mobileOpen"
                    class="md:hidden btn-ghost p-2 -ml-2 rounded-lg"
                    aria-label="Buka navigasi">
                <i class="ti ti-menu-2 text-xl text-slate-600"></i>
            </button>

            {{-- Toggle desktop sidebar --}}
            <button @click="desktopCollapsed = !desktopCollapsed"
                    class="hidden md:flex btn-ghost p-2 -ml-2 rounded-lg"
                    aria-label="Toggle sidebar">
                <i class="ti ti-layout-sidebar text-xl text-slate-600"></i>
            </button>

            <div class="flex-1 min-w-0"></div>

            <div class="flex items-center gap-1 shrink-0">
                {{-- Avatar dropdown --}}
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open"
                            class="w-8 h-8 rounded-full bg-primary-600 flex items-center
                                justify-center hover:bg-primary-700 transition-colors">
                        <span class="text-white text-xs font-semibold">
                            {{ strtoupper(substr(auth()->user()->nama_lengkap, 0, 2)) }}
                        </span>
                    </button>
                    <div x-show="open"
                        @click.outside="open = false"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        class="absolute right-0 mt-2 w-48 bg-white rounded-xl
                                border border-slate-200 shadow-lg py-1 z-50">
                        <div class="px-3 py-2 border-b border-slate-100">
                            <p class="text-sm font-medium text-slate-900 truncate">{{ auth()->user()->nama_lengkap }}</p>
                            <p class="text-xs text-slate-500 truncate">{{ auth()->user()->email }}</p>
                        </div>
                        <a href="{{ route('profile.edit') }}" wire:navigate
                           class="flex items-center gap-2 px-3 py-2 text-sm text-slate-700
                                   hover:bg-slate-50 transition-colors">
                            <i class="ti ti-user text-slate-400"></i> Profil Saya
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="w-full flex items-center gap-2 px-3 py-2 text-sm
                                        text-red-600 hover:bg-red-50 transition-colors">
                                <i class="ti ti-logout text-red-400"></i> Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        {{-- KONTEN HALAMAN --}}
        <main class="flex-1 p-4 md:p-6 pb-bottomnav md:pb-6 overflow-auto">
            <div>
                {{-- Flash messages --}}
                @if(session('success'))
                <div x-data="{ show: true }"
                    x-show="show"
                    x-init="setTimeout(() => show = false, 4000)"
                    x-transition:leave="transition-opacity duration-300"
                    x-transition:leave-end="opacity-0"
                    class="mb-4 flex items-center gap-3 bg-green-50 border border-green-200
                            text-green-800 text-sm rounded-xl px-4 py-3">
                    <i class="ti ti-circle-check text-green-500 text-lg shrink-0"></i>
                    <span>{{ session('success') }}</span>
                    <button @click="show = false" class="ml-auto text-green-500 hover:text-green-700">
                        <i class="ti ti-x text-base"></i>
                    </button>
                </div>
                @endif

                @if(session('error'))
                <div x-data="{ show: true }"
                    x-show="show"
                    x-init="setTimeout(() => show = false, 5000)"
                    x-transition:leave="transition-opacity duration-300"
                    x-transition:leave-end="opacity-0"
                    class="mb-4 flex items-center gap-3 bg-red-50 border border-red-200
                            text-red-800 text-sm rounded-xl px-4 py-3">
                    <i class="ti ti-alert-circle text-red-500 text-lg shrink-0"></i>
                    <span>{{ session('error') }}</span>
                    <button @click="show = false" class="ml-auto text-red-500 hover:text-red-700">
                        <i class="ti ti-x text-base"></i>
                    </button>
                </div>
                @endif

                {{ $slot }}
            </div>
        </main>
    </div>

    {{-- BOTTOM NAVIGATION (mobile) --}}
    <nav class="md:hidden fixed bottom-0 left-0 right-0 z-10 h-[60px]
                bg-white border-t border-slate-200 flex items-stretch">

        <a href="{{ route('dashboard') }}" wire:navigate
           class="{{ request()->routeIs('dashboard') ? 'bottom-nav-item-active' : 'bottom-nav-item' }}">
            <i class="ti ti-dashboard"></i>
            <span>Dashboard</span>
        </a>

        @if(auth()->user()->isAdmin())
        <a href="{{ route('peralatan.index') }}" wire:navigate
           class="{{ request()->routeIs('peralatan.*') ? 'bottom-nav-item-active' : 'bottom-nav-item' }}">
            <i class="ti ti-box"></i>
            <span>Peralatan</span>
        </a>

        <a href="#" wire:navigate
           class="flex flex-col items-center justify-center flex-1 -mt-4">
            <div class="w-12 h-12 rounded-full bg-primary-600 flex items-center
                        justify-center shadow-lg">
                <i class="ti ti-plus text-white text-2xl"></i>
            </div>
        </a>
        @elseif(auth()->user()->isSupervisor())
        <a href="#" wire:navigate
           class="{{ request()->routeIs('approvals.*') ? 'bottom-nav-item-active' : 'bottom-nav-item' }}">
            <i class="ti ti-shield-check"></i>
            <span>Approval</span>
        </a>
        <a href="#" wire:navigate
           class="{{ request()->routeIs('reports.*') ? 'bottom-nav-item-active' : 'bottom-nav-item' }}">
            <i class="ti ti-chart-bar"></i>
            <span>Laporan</span>
        </a>
        @endif

        <button @click="moreOpen = !moreOpen"
                :class="moreOpen ? 'bottom-nav-item-active' : 'bottom-nav-item'">
            <i class="ti ti-dots"></i>
            <span>Lainnya</span>
        </button>
    </nav>

    {{-- More menu panel --}}
    <div x-show="moreOpen"
        @click.outside="moreOpen = false"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-4"
        class="md:hidden fixed bottom-[60px] left-0 right-0 z-20 bg-white
                border-t border-slate-200 rounded-t-2xl shadow-xl p-4 grid grid-cols-4 gap-3">

        <a href="{{ route('dashboard') }}" wire:navigate
           class="flex flex-col items-center gap-1.5 p-3 rounded-xl hover:bg-slate-50">
            <div class="w-10 h-10 rounded-xl bg-orange-100 flex items-center justify-center">
                <i class="ti ti-tool text-orange-600 text-xl"></i>
            </div>
            <span class="text-[10px] text-slate-600 font-medium">Maintenance</span>
        </a>

        @if(auth()->user()->isAdmin())
        <a href="{{ route('dashboard') }}" wire:navigate
           class="flex flex-col items-center gap-1.5 p-3 rounded-xl hover:bg-slate-50">
            <div class="w-10 h-10 rounded-xl bg-purple-100 flex items-center justify-center">
                <i class="ti ti-users text-purple-600 text-xl"></i>
            </div>
            <span class="text-[10px] text-slate-600 font-medium">Kelola User</span>
        </a>
        @endif

        <a href="{{ route('profile.edit') }}" wire:navigate
           class="flex flex-col items-center gap-1.5 p-3 rounded-xl hover:bg-slate-50">
            <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center">
                <i class="ti ti-user text-slate-600 text-xl"></i>
            </div>
            <span class="text-[10px] text-slate-600 font-medium">Profil</span>
        </a>

    </div>

    @livewireScripts
</body>
</html>
