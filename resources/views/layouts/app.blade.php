<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'SiapSiaga') }}</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('assets/logo/favicon.ico') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=dm-sans:400,500,600,700|inter:400,500,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="h-full bg-bg font-body antialiased" 
      x-data="{ desktopCollapsed: false, mobileOpen: false, moreOpen: false }" 
      x-cloak>

    {{-- Mobile Overlay --}}
    <div x-show="mobileOpen"
         x-transition:enter="transition-opacity duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="mobileOpen = false"
         class="fixed inset-0 z-40 bg-black/40 md:hidden">
    </div>

    {{-- Sidebar --}}
    <aside :class="{
               'md:w-16': desktopCollapsed,
               'md:w-64': !desktopCollapsed,
               'translate-x-0': mobileOpen,
               '-translate-x-full md:translate-x-0': !mobileOpen
           }"
           class="fixed inset-y-0 left-0 z-50 flex flex-col bg-primary-900 transition-all duration-300 w-64 md:flex">

        {{-- Header Logo--}}
        <div class="flex items-center justify-between h-14 px-4 shrink-0 border-b border-white/10"
             :class="desktopCollapsed ? 'md:justify-center md:px-0' : ''">
            <div class="flex items-center gap-3 min-w-0">
                <div class="w-6 h-6 rounded-lg flex items-center justify-center shrink-0">
                    <img src="{{ asset('assets/logo/pln-square.png') }}" alt="Logo PLN">
                </div>
                <div x-show="!desktopCollapsed || mobileOpen"
                     x-transition:enter="transition-opacity duration-150 delay-100"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     class="min-w-0">
                    <p class="text-white font-semibold text-sm leading-tight font-display truncate">SiapSiaga</p>
                    <p class="text-white/40 text-[10px] leading-tight truncate">Peralatan Siaga</p>
                </div>
            </div>
        </div>

        {{-- Menu --}}
        <nav class="flex-1 overflow-y-auto py-3 px-2 flex flex-col gap-1">
            <x-sidebar-group label="Utama">
                    <x-sidebar-item route="dashboard" icon="ti-dashboard" label="Dashboard" />
                    <x-sidebar-item route="peralatan.index" icon="ti-box" label="Peralatan" />
                    <x-sidebar-item route="peminjaman.index" icon="ti-file-description" label="Peminjaman" />
                    <x-sidebar-item route="pemeliharaan.index" icon="ti-tool" label="Maintenance" />
                    <x-sidebar-item route="laporan.index" icon="ti-chart-bar" label="Laporan" />
            </x-sidebar-group>

            <x-sidebar-group label="Administrasi">
                <x-sidebar-item route="users.index" icon="ti-users" label="Kelola Pengguna" />
                <x-sidebar-item route="activity-logs.index" icon="ti-history" label="Log Aktivitas" />
            </x-sidebar-group>
        </nav>

        {{-- Footer --}}
        <div class="shrink-0 border-t border-white/10 p-3">
            <div class="flex items-center gap-3" :class="desktopCollapsed ? 'md:justify-center' : ''">
                <div class="w-8 h-8 rounded-full bg-primary-600 flex items-center justify-center shrink-0">
                    <span class="text-white text-xs font-semibold">
                        {{ strtoupper(substr(auth()->user()->nama_lengkap, 0, 2)) }}
                    </span>
                </div>
                <div x-show="!desktopCollapsed || mobileOpen" class="min-w-0 flex-1">
                    <p class="text-white text-xs font-medium truncate">{{ auth()->user()->nama_lengkap }}</p>
                    <p class="text-white/40 text-[10px] truncate">Admin</p>
                </div>
            </div>
        </div>
    </aside>

    {{-- Hero --}}
    <div :class="desktopCollapsed ? 'md:ml-16' : 'md:ml-64'"
         class="flex flex-col min-h-screen transition-all duration-300">

        {{-- Topbar --}}
        <header class="sticky top-0 z-30 h-14 bg-white border-b border-slate-200 flex items-center justify-between px-4 shrink-0">
            <div class="flex items-center gap-2">
                <button @click="mobileOpen = !mobileOpen" class="md:hidden p-2 rounded-lg hover:bg-slate-50">
                    <i class="ti ti-menu-2 text-xl text-slate-600"></i>
                </button>
                <button @click="desktopCollapsed = !desktopCollapsed" class="hidden md:flex p-2 rounded-lg hover:bg-slate-50">
                    <i class="ti text-xl text-slate-600" :class="desktopCollapsed ? 'ti-layout-sidebar-left-expand' : 'ti-layout-sidebar-left-collapse'"></i>
                </button>
            </div>

            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="w-8 h-8 rounded-full bg-primary-600 flex items-center justify-center hover:bg-primary-700 transition-colors">
                    <span class="text-white text-xs font-semibold">{{ strtoupper(substr(auth()->user()->nama_lengkap, 0, 2)) }}</span>
                </button>
                <div x-show="open" @click.outside="open = false" x-transition class="absolute right-0 mt-2 w-48 bg-white rounded-xl border border-slate-200 shadow-lg py-1 z-50">
                    <div class="px-3 py-2 border-b border-slate-100">
                        <p class="text-sm font-medium text-slate-900 truncate">{{ auth()->user()->nama_lengkap }}</p>
                        <p class="text-xs text-slate-500 truncate">Admin</p>
                    </div>
                    <a href="{{ route('profile') }}" wire:navigate
                       class="flex items-center gap-2 px-3 py-2 text-sm text-slate-700 
                            hover:bg-slate-50 transtion-colors">
                        <i class="ti ti-user text-slate-400"></i>
                        Profil Saya
                    </a>
                    <livewire:auth.logout />
                </div>
            </div>
        </header>

        <main class="flex-1 p-4 md:p-6 pb-24 md:pb-6 overflow-auto">
            {{-- Flash messages --}}
            <div x-data="{ 
                showSuccess: {{ session('success') ? 'true' : 'false' }},
                showError: {{ session('error') ? 'true' : 'false' }}
            }"
            x-init="
                if (showSuccess) setTimeout(() => showSuccess = false, 4000);
                if (showError) setTimeout(() => showError = false, 5000);
            ">
                
                {{-- ALERT SUCCESS --}}
                <div x-show="showSuccess"
                    x-transition:leave="transition-opacity duration-300"
                    x-transition:leave-end="opacity-0"
                    class="mb-4 flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 text-sm rounded-xl px-4 py-3">
                    <i class="ti ti-circle-check text-green-500 text-lg shrink-0"></i>
                    <span>{{ session('success') }}</span>
                    <button @click="showSuccess = false" class="ml-auto text-green-500 hover:text-green-700">
                        <i class="ti ti-x text-base"></i>
                    </button>
                </div>

                {{-- ALERT ERROR --}}
                <div x-show="showError"
                    x-transition:leave="transition-opacity duration-300"
                    x-transition:leave-end="opacity-0"
                    class="mb-4 flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 text-sm rounded-xl px-4 py-3">
                    <i class="ti ti-alert-circle text-red-500 text-lg shrink-0"></i>
                    <span>{{ session('error') }}</span>
                    <button @click="showError = false" class="ml-auto text-red-500 hover:text-red-700">
                        <i class="ti ti-x text-base"></i>
                    </button>
                </div>
            </div>
            {{ $slot }}
        </main>
    </div>

    {{-- Bottom Nav (mobile) --}}
    <nav class="md:hidden fixed bottom-0 left-0 right-0 z-40 h-16 bg-white border-t border-slate-200 flex items-stretch justify-around px-2 shadow-lg">
        <a href="{{ route('dashboard') }}" wire:navigate class="flex flex-col items-center justify-center flex-1 text-xs gap-1 {{ request()->routeIs('dashboard') ? 'text-primary-600 font-semibold' : 'text-slate-400' }}">
            <i class="ti ti-dashboard text-xl"></i><span>Dashboard</span>
        </a>
        <a href="{{ route('peralatan.index') }}" wire:navigate class="flex flex-col items-center justify-center flex-1 text-xs gap-1 {{ request()->routeIs('peralatan.*') ? 'text-primary-600 font-semibold' : 'text-slate-400' }}">
            <i class="ti ti-box text-xl"></i><span>Peralatan</span>
        </a>
        <a href="{{ route('peminjaman.index') }}" wire:navigate class="flex flex-col items-center justify-center flex-1 text-xs gap-1 {{ request()->routeIs('peminjaman.*') ? 'text-primary-600 font-semibold' : 'text-slate-400' }}">
            <i class="ti ti-file-description text-xl"></i><span>Peminjaman</span>
        </a>
        <a href="{{ route('laporan.index') }}" wire:navigate class="flex flex-col items-center justify-center flex-1 text-xs gap-1 {{ request()->routeIs('laporan.*') ? 'text-primary-600 font-semibold' : 'text-slate-400' }}">
            <i class="ti ti-chart-bar text-xl"></i><span>Laporan</span>
        </a>
        <button @click="moreOpen = !moreOpen" class="flex flex-col items-center justify-center flex-1 text-xs gap-1 text-slate-400">
            <i class="ti ti-dots text-xl"></i><span>Lainnya</span>
        </button>
    </nav>
    
    {{-- Menu Lainnya (mobile) --}}
    <div x-show="moreOpen"
        @click.outside="moreOpen = false"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-4"
        class="md:hidden fixed left-1/2 -translate-x-1/2 bottom-[80px] z-20 bg-white
            border border-slate-200 rounded-2xl shadow-xl p-4 grid grid-cols-3 gap-3">

        <a href="{{ route('pemeliharaan.index') }}" wire:navigate
           class="flex flex-col items-center gap-1.5 p-3 rounded-xl hover:bg-slate-50">
            <div class="w-10 h-10 rounded-xl bg-orange-100 flex items-center justify-center">
                <i class="ti ti-tool text-orange-600 text-xl"></i>
            </div>
            <span class="text-[10px] text-slate-600 font-medium text-center">Maintenance</span>
        </a>
        <a href="{{ route('users.index') }}" wire:navigate
           class="flex flex-col items-center gap-1.5 p-3 rounded-xl hover:bg-slate-50">
            <div class="w-10 h-10 rounded-xl bg-purple-100 flex items-center justify-center">
                <i class="ti ti-users text-purple-600 text-xl"></i>
            </div>
            <span class="text-[10px] text-slate-600 font-medium text-center">Kelola Pengguna</span>
        </a>
        <a href="{{ route('activity-logs.index') }}" wire:navigate
           class="flex flex-col items-center gap-1.5 p-3 rounded-xl hover:bg-slate-50">
            <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center">
                <i class="ti ti-history text-slate-600 text-xl"></i>
            </div>
            <span class="text-[10px] text-slate-600 font-medium text-center">Log Aktivitas</span>
        </a>
    </div>

    @livewireScripts
</body>
</html>