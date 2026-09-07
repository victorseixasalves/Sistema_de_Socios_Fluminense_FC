<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} — Painel</title>
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

        <div class="min-h-screen">
            @include('layouts.navigation')

            @isset($header)
                <header class="bg-white dark:bg-[#1E1D24] border-b border-black/10 dark:border-white/10">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>