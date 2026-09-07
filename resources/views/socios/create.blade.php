<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ __('Sócio-Torcedor') }} | Fluminense FC</title>
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
<body class="font-sans bg-[#F5F3F0] dark:bg-[#16151A] text-[#16151A] dark:text-white transition-colors overflow-x-hidden">

    <!-- Padrão de fundo -->
    <div class="fixed inset-0 -z-10 overflow-hidden pointer-events-none">
        <div
            id="shield-bg"
            class="absolute -inset-1/2 w-[200%] h-[200%] rotate-[25deg] opacity-[0.05] dark:opacity-[0.07] dark:invert"
            style="background-repeat:repeat; background-size:140px 160px; background-image:url('data:image/svg+xml;utf8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22140%22%20height%3D%22160%22%20viewBox%3D%220%200%20140%20160%22%3E%3Cpath%20d%3D%22M70%2010%20L110%2024%20L110%2070%20C110%20100%2094%20120%2070%20138%20C46%20120%2030%20100%2030%2070%20L30%2024%20Z%22%20fill%3D%22none%22%20stroke%3D%22%23000000%22%20stroke-width%3D%223%22%2F%3E%3Ctext%20x%3D%2270%22%20y%3D%2280%22%20font-family%3D%22Arial%2C%20sans-serif%22%20font-size%3D%2222%22%20font-weight%3D%22700%22%20fill%3D%22%23000000%22%20text-anchor%3D%22middle%22%3EFFC%3C%2Ftext%3E%3C%2Fsvg%3E');"
        ></div>
    </div>

    <!-- Header -->
    <header class="sticky top-0 z-10 backdrop-blur bg-[#F5F3F0]/90 dark:bg-[#16151A]/90 border-b border-black/5 dark:border-white/5">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 shrink-0 rounded-lg bg-[#16151A] dark:bg-white flex items-center justify-center p-1.5">
                    <img src="{{ asset('images/EscudoFLU.png') }}" alt="Fluminense F.C." class="w-full h-full object-contain">
                </div>
                <div class="leading-tight">
                    <p class="font-display font-semibold tracking-wide text-sm sm:text-base">FLUMINENSE F.C.</p>
                    <p class="text-xs text-black/60 dark:text-white/60">{{ __('Programa de Sócio-Torcedor') }}</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('login') }}"
                    class="admin-login-btn hidden sm:inline-flex items-center text-sm font-medium border rounded-full px-4 py-1.5 transition-colors duration-200 text-[#16151A] border-black/30 dark:text-white dark:border-white/50">
                    {{ __('Acesso Admin') }}
                </a>

                <a href="{{ route('login') }}"
                    class="sm:hidden w-9 h-9 shrink-0 rounded-full flex items-center justify-center border border-black/10 dark:border-white/10"
                    aria-label="{{ __('Acesso Admin') }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="5" y="11" width="14" height="9" rx="2"/>
                        <path d="M8 11V8a4 4 0 018 0v3"/>
                    </svg>
                </a>

                <a href="{{ route('locale.switch', app()->getLocale() === 'pt' ? 'en' : 'pt') }}"
                    class="w-9 h-9 shrink-0 rounded-full flex items-center justify-center border border-black/10 dark:border-white/10 hover:bg-black/5 dark:hover:bg-white/5 transition-colors text-xs font-semibold">
                    {{ app()->getLocale() === 'pt' ? 'EN' : 'PT' }}
                </a>

                <button
                    type="button"
                    class="theme-toggle w-9 h-9 shrink-0 rounded-full flex items-center justify-center border border-black/10 dark:border-white/10 hover:bg-black/5 dark:hover:bg-white/5 transition-colors"
                    aria-label="{{ __('Alternar tema claro/escuro') }}"
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
        </div>
    </header>

    <main class="max-w-5xl mx-auto px-4 sm:px-6">

        @if (session('success'))
            <div class="mt-6 bg-[#1B7A43]/10 border border-[#1B7A43]/30 text-[#1B7A43] dark:text-green-300 px-4 py-3 rounded-lg text-sm text-center">
                {{ __(session('success')) }}
            </div>
        @endif

        <!-- Hero -->
        <section class="pt-14 sm:pt-20 pb-12 sm:pb-16 text-center animate-[fadeUp_0.6s_ease-out]">
            <h1 class="font-display font-semibold text-4xl sm:text-5xl md:text-6xl leading-[1.05] tracking-tight">
                {{ __('Vista a camisa.') }}<br class="hidden sm:block">
                <span class="text-[#6D1B36] dark:text-[#e0567f]">{{ __('Faça parte do') }}</span> {{ __('Fluminense F.C.') }}
            </h1>
            <p class="mt-5 text-black/70 dark:text-white/70 max-w-md mx-auto text-[15px] sm:text-base leading-relaxed">
                {{ __('Sócio-torcedor não assiste de fora: prioridade em ingressos, desconto na loja e acesso antecipado aos grandes jogos no Maracanã.') }}
            </p>
        </section>

        <!-- Cards dos Planos -->
        <section class="pb-14 sm:pb-20">
            <h2 class="font-display font-medium text-lg sm:text-xl mb-5 text-center">{{ __('Escolha seu plano') }}</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

                @foreach ($planos as $plano)
                    <div class="relative rounded-xl p-5 bg-white dark:bg-[#1E1D24] border transition-all duration-200 hover:-translate-y-1.5 hover:shadow-xl hover:shadow-black/10 dark:hover:shadow-black/40
                        {{ $plano->destaque ? 'border-2 border-[#6D1B36]' : 'border-black/10 dark:border-white/10' }}">

                        @if ($plano->destaque)
                            <span class="absolute -top-3 left-5 bg-[#6D1B36] text-white text-xs font-medium px-2.5 py-1 rounded-full">
                                {{ __('Mais popular') }}
                            </span>
                        @endif

                        <h3 class="font-display font-medium mt-1
                            {{ $plano->destaque ? 'text-[#6D1B36] dark:text-[#e0567f]' : 'text-[#1B7A43]' }}">
                            {{ __($plano->nome) }}
                        </h3>

                        @if ($plano->beneficios_lista)
                            <ul class="mt-3 space-y-1.5 text-sm text-black/65 dark:text-white/65">
                                @foreach ($plano->beneficios_lista as $beneficio)
                                    <li>{{ __($beneficio) }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                @endforeach

            </div>
        </section>

        <!-- Formulário -->
        <section class="pb-16 sm:pb-24">
            <div class="max-w-xl mx-auto rounded-2xl p-6 sm:p-8 bg-white dark:bg-[#1E1D24] border border-black/10 dark:border-white/10">
                <h2 class="font-display font-medium text-xl mb-1">{{ __('Quero ser sócio') }}</h2>
                <p class="text-sm text-black/60 dark:text-white/60 mb-6">{{ __('Preencha seus dados e entraremos em contato para confirmar.') }}</p>

                <form method="POST" action="{{ route('socios.store') }}" class="space-y-4">
                    @csrf
                    <!-- Honeypot anti-spam: campo invisível para humanos, mas que bots costumam preencher -->
                    <input type="text" name="site_web" value="" style="position:absolute; left:-9999px;" tabindex="-1" autocomplete="off">

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="nome" class="block text-sm font-medium mb-1.5">{{ __('Nome completo') }}</label>
                            <input type="text" name="nome" id="nome" value="{{ old('nome') }}"
                                class="w-full rounded-lg bg-[#F5F3F0] dark:bg-[#1E1D24] border border-black/15 dark:border-white/15 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1B7A43]">
                            @error('nome') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium mb-1.5">{{ __('Email') }}</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}"
                                class="w-full rounded-lg bg-[#F5F3F0] dark:bg-[#1E1D24] border border-black/15 dark:border-white/15 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1B7A43]">
                            @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label for="tipo" class="block text-sm font-medium mb-1.5">{{ __('Plano') }}</label>
                        <select name="tipo" id="tipo"
                            class="w-full rounded-lg bg-[#F5F3F0] dark:bg-[#1E1D24] border border-black/15 dark:border-white/15 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1B7A43]">
                            <option value="">{{ __('Selecione um plano') }}</option>
                            @foreach ($planos as $plano)
                                <option value="{{ $plano->nome }}" {{ old('tipo') == $plano->nome ? 'selected' : '' }}>
                                    {{ __($plano->nome) }}
                                </option>
                            @endforeach
                        </select>
                        @error('tipo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="data" class="block text-sm font-medium mb-1.5">{{ __('Data de início desejada') }}</label>
                            <input type="date" name="data" id="data" value="{{ old('data') }}"
                                class="w-full rounded-lg bg-[#F5F3F0] dark:bg-[#1E1D24] border border-black/15 dark:border-white/15 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1B7A43]">
                            @error('data') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="setor" class="block text-sm font-medium mb-1.5">{{ __('Setor preferido') }}</label>
                            <select name="setor" id="setor"
                                class="w-full rounded-lg bg-[#F5F3F0] dark:bg-[#1E1D24] border border-black/15 dark:border-white/15 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1B7A43]">
                                <option value="">{{ __('Selecione um setor') }}</option>
                                @foreach ($setores as $setor)
                                    <option value="{{ $setor->nome }}" {{ old('setor') == $setor->nome ? 'selected' : '' }}>
                                        {{ __($setor->nome) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('setor') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full bg-[#6D1B36] hover:bg-[#5a1629] transition-colors text-white font-medium text-sm py-3 rounded-lg mt-2">
                        {{ __('Enviar cadastro') }}
                    </button>
                </form>
            </div>
        </section>

    </main>

    <footer class="text-center text-black/40 dark:text-white/40 text-xs py-8 border-t border-black/5 dark:border-white/5">
        {{ __('Fluminense F.C. — Programa de Sócio-Torcedor (projeto de teste técnico)') }}
    </footer>

</body>
</html>