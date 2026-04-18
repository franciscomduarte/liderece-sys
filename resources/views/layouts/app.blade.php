<!DOCTYPE html>
<html lang="pt-BR" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'SGC') }} — @yield('title', 'Sistema de Gestão de Competências')</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet">

    <!-- Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js" defer></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="h-full bg-[#f6fafe] font-['Inter']">

    <div class="flex h-full">
        <!-- Sidebar (desktop) -->
        @include('layouts.components.sidebar')

        <!-- Conteúdo principal -->
        <div class="flex-1 flex flex-col min-h-full ml-0 md:ml-64">
            @include('layouts.components.topbar')

            <main class="flex-1 p-4 md:p-8 pb-24 md:pb-8">
                {{ $slot }}
            </main>
        </div>

        <!-- Bottom Nav (mobile) -->
        @include('layouts.components.bottom-nav')
    </div>

    @livewireScripts

    <!-- Toast container -->
    <div
        x-data="{ toasts: [] }"
        @toast.window="toasts.push($event.detail); setTimeout(() => toasts.shift(), 4000)"
        class="fixed top-4 right-4 z-50 flex flex-col gap-2"
    >
        <template x-for="(toast, i) in toasts" :key="i">
            <div
                x-show="true"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-x-4"
                x-transition:enter-end="opacity-100 translate-x-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                :class="{
                    'bg-[#006947] text-white': toast.type === 'success',
                    'bg-[#ba1a1a] text-white': toast.type === 'error',
                    'bg-[#0058be] text-white': toast.type === 'info',
                    'bg-amber-500 text-white': toast.type === 'warning',
                }"
                class="flex items-center gap-3 px-4 py-3 rounded-xl shadow-lg min-w-64 max-w-sm"
            >
                <span class="material-symbols-outlined text-lg" x-text="
                    toast.type === 'success' ? 'check_circle' :
                    toast.type === 'error' ? 'error' :
                    toast.type === 'warning' ? 'warning' : 'info'
                "></span>
                <span class="text-sm font-semibold" x-text="toast.message"></span>
            </div>
        </template>
    </div>
</body>
</html>
