<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Mon Application')</title>
    <link rel="icon" type="image/svg" href="{{ asset('5.svg') }}">

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .glass {
            background: rgba(0, 0, 0, 0.42);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.1);
        }

        body {
            background: linear-gradient(to right, #000000, #242424);
        }

        /* ── BURGER BUTTON ──────────────────────────── */
        #burger-btn {
            display: none;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 5px;
            width: 42px;
            height: 42px;
            cursor: pointer;
            border: none;
            background: transparent;
            padding: 0;
            flex-shrink: 0;
        }

        .burger-line {
            display: block;
            width: 22px;
            height: 2px;
            background: #ffffff;
            border-radius: 2px;
            transition: transform .3s ease, opacity .3s ease, width .3s ease;
            transform-origin: center;
        }

        /* État ouvert : croix */
        #burger-btn.open .burger-line:nth-child(1) {
            transform: translateY(7px) rotate(45deg);
        }
        #burger-btn.open .burger-line:nth-child(2) {
            opacity: 0;
            width: 0;
        }
        #burger-btn.open .burger-line:nth-child(3) {
            transform: translateY(-7px) rotate(-45deg);
        }

        /* ── MOBILE MENU DRAWER ─────────────────────── */
        #mobile-menu {
            display: none;
            flex-direction: column;
            gap: 6px;
            overflow: hidden;
            max-height: 0;
            opacity: 0;
            transition: max-height .35s ease, opacity .3s ease;
            z-index: 50;
        }

        #mobile-menu.open {
            max-height: 400px; /* Augmenté légèrement pour accueillir le bouton déconnexion */
            opacity: 1;
        }

        .mobile-nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 16px;
            border-radius: 10px;
            color: #ffffff;
            font-size: 15px;
            font-weight: 500;
            text-decoration: none;
            transition: background .2s, color .2s;
        }

        .mobile-nav-link:hover {
            background: rgba(83, 74, 183, .1);
        }

        .mobile-nav-link.active {
            background: #4d43b9;
            color: #fff;
        }

        .mobile-nav-link i {
            font-size: 20px;
        }

        .mobile-divider {
            height: 1px;
            background: rgba(255, 255, 255, 0.1);
            margin: 4px 0;
        }

        /* ── RESPONSIVE ─────────────────────────────── */
        @media (max-width: 640px) {
            #desktop-nav { display: none !important; }
            #burger-btn  { display: flex !important; }
            #mobile-menu { display: flex !important; }
        }
    </style>
</head>
<body class="text-gray-900 font-sans antialiased min-h-screen flex flex-col">

    {{-- ── HEADER ──────────────────────────────────── --}}
    <header class="fixed h-20 top-0 left-0 right-0 mb-2 text-sm z-50">
        <div class="glass h-20 px-4 py-3 flex items-center justify-between">

            {{-- Logo --}}
            <a href="{{ route('welcome') }}">
                <img src="{{ asset('4.svg') }}" class="w-12 h-12 object-contain" alt="Logo">
            </a>

            {{-- Nav desktop --}}
            <nav id="desktop-nav" class="flex items-center gap-3">
                <div class="bg-white p-2 flex items-center gap-1 rounded-[10px]">
                    <a href="{{ route('welcome') }}"
                       class="flex items-center p-2 gap-1 rounded-xl text-gray-800 hover:bg-gray-800/20 transition duration-300 {{ request()->routeIs('welcome') ? 'bg-[#4d43b9] text-white' : '' }}">
                        <i class='bx bx-user' style="font-size:18px"></i> Profile
                    </a>

                    {{-- Si l'utilisateur n'est pas connecté --}}
                    @guest
                        <a href="{{ route('login') }}"
                           class="flex items-center p-2 gap-1 rounded-xl text-gray-800 hover:bg-gray-800/20 transition duration-300 {{ request()->routeIs('login') ? 'bg-[#4d43b9] text-white' : '' }}">
                            <i class='bx bx-log-in' style="font-size:18px"></i> Connexion
                        </a>
                    @endguest

                    {{-- Si l'utilisateur est connecté --}}
                    @auth
                        <a href="{{ route('services.applications') }}"
                           class="flex items-center p-2 gap-1 rounded-xl text-gray-800 hover:bg-gray-800/20 transition duration-300 {{ request()->routeIs('services.*') ? 'bg-[#4d43b9] text-white' : '' }}">
                            <i class='bx bx-folder' style="font-size:18px"></i> Pour vous
                        </a>
                        
                        {{-- Formulaire de déconnexion obligatoire (POST) --}}
                        <form action="{{ route('logout') }}" method="POST" class="inline m-0 p-0">
                            @csrf
                            <button type="submit" title="Déconnexion" class="flex items-center p-2 rounded-xl text-red-600 hover:bg-red-50 transition duration-300 cursor-pointer">
                                <i class='bx bx-log-out' style="font-size:18px"></i>
                            </button>
                        </form>
                    @endauth
                </div>
            </nav>

            {{-- Burger button (mobile only) --}}
            <button id="burger-btn" aria-label="Ouvrir le menu" aria-expanded="false" aria-controls="mobile-menu">
                <span class="burger-line"></span>
                <span class="burger-line"></span>
                <span class="burger-line"></span>
            </button>

        </div>

        {{-- ── MOBILE DRAWER ────────────────────────── --}}
        <div id="mobile-menu" class="glass fixed top-17 left-0 right-0 rounded-[10px] mt-2 px-2 py-3" role="navigation" aria-label="Menu mobile">
            <a href="{{ route('welcome') }}"
               class="mobile-nav-link {{ request()->routeIs('welcome') ? 'active' : '' }}">
                <i class='bx bx-user'></i> Profile
            </a>

            @guest
                <a href="{{ route('login') }}"
                   class="mobile-nav-link {{ request()->routeIs('login') ? 'active' : '' }}">
                    <i class='bx bx-log-in'></i> Connexion
                </a>
            @endguest

            @auth
                <a href="{{ route('services.applications') }}"
                   class="mobile-nav-link {{ request()->routeIs('services.*') ? 'active' : '' }}">
                    <i class='bx bx-folder'></i> Pour vous
                </a>
                <div class="mobile-divider"></div>
                <form action="{{ route('logout') }}" method="POST" class="w-full m-0 p-0">
                    @csrf
                    <button type="submit" class="w-full text-left mobile-nav-link text-red-400 hover:bg-red-900/20 cursor-pointer">
                        <i class='bx bx-log-out'></i> Déconnexion
                    </button>
                </form>
            @endauth
        </div>
    </header>

    {{-- ── MAIN ─────────────────────────────────────── --}}
    <main class="flex-1 mt-24 mb-4">
        @yield('content')
    </main>

    {{-- ── FOOTER ───────────────────────────────────── --}}
    <footer class="text-center p-4 text-gray-400 text-sm mt-auto glass mx-4 mb-4 rounded-xl">
        &copy; {{ date('Y') }}
    </footer>

    {{-- ── SCRIPT BURGER ───────────────────────────── --}}
    <script>
        (function () {
            const btn    = document.getElementById('burger-btn');
            const menu   = document.getElementById('mobile-menu');

            btn.addEventListener('click', function () {
                const isOpen = menu.classList.toggle('open');
                btn.classList.toggle('open', isOpen);
                btn.setAttribute('aria-expanded', isOpen);
            });

            document.addEventListener('click', function (e) {
                if (!btn.contains(e.target) && !menu.contains(e.target)) {
                    menu.classList.remove('open');
                    btn.classList.remove('open');
                    btn.setAttribute('aria-expanded', 'false');
                }
            });

            window.addEventListener('resize', function () {
                if (window.innerWidth > 640) {
                    menu.classList.remove('open');
                    btn.classList.remove('open');
                    btn.setAttribute('aria-expanded', 'false');
                }
            });
        })();
    </script>

</body>
</html>