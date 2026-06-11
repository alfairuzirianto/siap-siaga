<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#1e3a5f">

    <title>{{ $title ?? config('app.name', 'SiapSiaga') }}</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('assets/logo/favicon.ico') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=dm-sans:400,500,600,700|inter:400,500,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
</head>
<body class="h-full bg-bg font-body antialiased">

<div class="min-h-screen flex flex-col lg:flex-row">

    <!-- Sisi Kiri: Hanya Ikon & Nama Sistem (Desktop) -->
    <div class="hidden lg:flex lg:w-1/2 xl:w-2/5 flex-col justify-between bg-primary-900 relative overflow-hidden p-12">

        <div class="absolute inset-0 opacity-5">
            <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
                        <path d="M 40 0 L 0 0 0 40" fill="none" stroke="white" stroke-width="1"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#grid)" />
            </svg>
        </div>

        <!-- Spacer atas agar posisi logo seimbang -->
        <div></div>

        <!-- Konten Utama: Centered Logo & Nama -->
        <div class="relative z-10 flex flex-col items-center justify-center text-center">
            <div class="w-24 h-24 mb-6 flex items-center justify-center">
                <img src="{{ asset('assets/logo/pln-square.png') }}" alt="Logo PLN" class="w-full h-auto object-contain">
            </div>
            <div>
                <p class="text-white font-bold text-3xl font-display tracking-wide uppercase">SIAGA PLN</p>
                <p class="text-white/60 text-sm mt-1">Sistem Informasi Peralatan Siaga</p>
            </div>
        </div>

        <!-- Footer Hak Cipta -->
        <div class="relative z-10 text-center">
            <p class="text-white/30 text-xs">© {{ date('Y') }} PT PLN (Persero). Internal use only.</p>
        </div>
    </div>

    <!-- Sisi Kanan: Form Login -->
    <div class="flex-1 flex flex-col items-center justify-center px-6 py-12 sm:px-10 lg:px-12 xl:px-16 bg-bg">

        <!-- Tampilan Atas Mobile (Responsif) -->
        <div class="lg:hidden flex items-center gap-4 mb-8 justify-center sm:justify-start">
            <div class="w-10 h-10 flex items-center justify-center shrink-0">
                <img src="{{ asset('assets/logo/pln-square.png') }}" alt="Logo PLN" class="w-full h-auto object-contain">
            </div>
            <div>
                <p class="text-slate-900 font-bold text-lg font-display leading-tight uppercase">SIAGA PLN</p>
                <p class="text-slate-500 text-xs">Sistem Informasi Peralatan Siaga</p>
            </div>
        </div>

        <div class="w-full max-w-md mx-auto">
            {{ $slot }}
        </div>
    </div>

</div>

@livewireScripts
</body>
</html>
