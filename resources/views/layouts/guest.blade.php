<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} — Acesso</title>
        <link rel="icon" type="image/png" href="{{ asset('images/EscudoFLU.png') }}">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Oswald:wght@500;600;700&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <script>
            if (localStorage.getItem('tema') === 'claro') {
                document.documentElement.classList.remove('dark');
            } else {
                document.documentElement.classList.add('dark');
            }
        </script>
    </head>
    <body class="font-sans antialiased bg-[#F5F3F0] dark:bg-[#16151A] text-[#16151A] dark:text-white transition-colors">
        <div class="fixed inset-0 -z-10 overflow-hidden pointer-events-none">
            <div
                id="shield-bg"
                class="absolute -inset-1/2 w-[200%] h-[200%] rotate-[25deg] opacity-[0.05] dark:opacity-[0.07] dark:invert"
                style="background-repeat:repeat; background-size:140px 160px; background-image:url('data:image/svg+xml;utf8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22140%22%20height%3D%22160%22%20viewBox%3D%220%200%20140%20160%22%3E%3Cpath%20d%3D%22M70%2010%20L110%2024%20L110%2070%20C110%20100%2094%20120%2070%20138%20C46%20120%2030%20100%2030%2070%20L30%2024%20Z%22%20fill%3D%22none%22%20stroke%3D%22%23000000%22%20stroke-width%3D%223%22%2F%3E%3Ctext%20x%3D%2270%22%20y%3D%2280%22%20font-family%3D%22Arial%2C%20sans-serif%22%20font-size%3D%2222%22%20font-weight%3D%22700%22%20fill%3D%22%23000000%22%20text-anchor%3D%22middle%22%3EFFC%3C%2Ftext%3E%3C%2Fsvg%3E');"
            ></div>
        </div>

        <div class="fixed top-4 right-4 flex items-center gap-2">
            <a href="{{ route('locale.switch', app()->getLocale() === 'pt' ? 'en' : 'pt') }}"
                class="w-9 h-9 shrink-0 rounded-full flex items-center justify-center border border-black/10 dark:border-white/10 hover:bg-black/5 dark:hover:bg-white/5 transition-colors bg-white dark:bg-[#1E1D24] text-xs font-semibold">
                {{ app()->getLocale() === 'pt' ? 'EN' : 'PT' }}
            </a>

            <button
                type="button"
                class="theme-toggle w-9 h-9 shrink-0 rounded-full flex items-center justify-center border border-black/10 dark:border-white/10 hover:bg-black/5 dark:hover:bg-white/5 transition-colors bg-white dark:bg-[#1E1D24]"
                aria-label="Alternar tema claro/escuro"
            >
                <svg class="icon-sun hidden w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="4"/>
                    <path stroke-linecap="round" d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/>
                </svg>
                <svg class="icon-moon w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 1020.354 15.354z"/>
                </svg>
            </button>
        </div>

        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 px-4">
            <a href="/" class="flex items-center gap-3 mb-2">
                <div class="w-12 h-12 shrink-0 rounded-xl bg-[#16151A] dark:bg-white flex items-center justify-center p-2">
                    <img src="{{ asset('images/EscudoFLU.png') }}" alt="Fluminense F.C." class="w-full h-full object-contain">
                </div>
                <div class="leading-tight text-left">
                    <p class="font-display font-semibold tracking-wide text-base">FLUMINENSE F.C.</p>
                    <p class="text-xs text-black/60 dark:text-white/60">{{ __('Área do administrador') }}</p>
                </div>
            </a>

            <div class="w-full sm:max-w-md mt-6 px-6 py-6 bg-white dark:bg-[#1E1D24] border border-black/10 dark:border-white/10 shadow-sm overflow-hidden sm:rounded-xl">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>