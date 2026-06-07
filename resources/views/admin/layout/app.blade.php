<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin — @yield('title', 'Tableau de bord')</title>
    <link rel="icon" type="image/svg" href="{{ asset('5.svg') }}">

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:wght@300;400;500&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">

    {{-- Boxicons --}}
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    {{-- Tailwind CDN (remplacer par build en prod) --}}
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        :root {
            --indigo:       #534AB7;
            --indigo-dark:  #3C3489;
            --indigo-light: #EEEDFE;
            --indigo-mid:   #AFA9EC;
            --green:        #3B6D11;
            --green-light:  #EAF3DE;
            --red:          #A32D2D;
            --red-light:    #FCEBEB;
            --amber:        #854F0B;
            --amber-light:  #FAEEDA;
            --sidebar-w:    240px;
            --header-h:     60px;
            --radius:       12px;
            --radius-sm:    8px;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'DM Sans', system-ui, sans-serif;
            background: #F4F3FB;
            color: #1a1a2e;
            min-height: 100vh;
        }

        /* ── SIDEBAR ── */
        .sidebar {
            position: fixed;
            top: 0; left: 0; bottom: 0;
            width: var(--sidebar-w);
            background: #0f0e1a;
            display: flex;
            flex-direction: column;
            z-index: 150; /* Augmenté pour passer au-dessus du header sur mobile */
            overflow: hidden;
            transition: transform .3s ease; /* Transition pour l'animation d'ouverture */
        }

        .sidebar-logo {
            height: var(--header-h);
            display: flex;
            align-items: center;
            justify-content: space-between; /* Changé pour caler le bouton fermer à droite */
            padding: 0 1.25rem;
            border-bottom: 1px solid rgba(255,255,255,.06);
            flex-shrink: 0;
        }

        .sidebar-logo-content {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar-logo-icon {
            width: 32px; height: 32px;
            background: var(--indigo);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 16px; color: #fff; font-family: 'Syne', sans-serif; font-weight: 800;
        }

        .sidebar-logo-text {
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            font-size: 15px;
            color: #fff;
            letter-spacing: -.01em;
        }

        .sidebar-logo-text span { color: var(--indigo-mid); }

        .sidebar-nav {
            flex: 1;
            padding: 1rem 0.75rem;
            display: flex;
            flex-direction: column;
            gap: 2px;
            overflow-y: auto;
        }

        .nav-section-label {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: .1em;
            text-transform: uppercase;
            color: rgba(255,255,255,.25);
            padding: 0.75rem 0.5rem 0.4rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px;
            border-radius: var(--radius-sm);
            font-size: 13.5px;
            font-weight: 500;
            color: rgba(255,255,255,.55);
            text-decoration: none;
            transition: background .15s, color .15s;
        }

        .nav-link i { font-size: 18px; flex-shrink: 0; }
        .nav-link:hover { background: rgba(255,255,255,.06); color: rgba(255,255,255,.9); }
        .nav-link.active { background: var(--indigo); color: #fff; }

        .sidebar-footer {
            padding: 1rem 0.75rem;
            border-top: 1px solid rgba(255,255,255,.06);
        }

        /* ── HEADER ── */
        .main-header {
            position: fixed;
            top: 0;
            left: var(--sidebar-w);
            right: 0;
            height: var(--header-h);
            background: #fff;
            border-bottom: 1px solid #ebebeb;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.5rem;
            z-index: 40;
            transition: left .3s ease;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .header-title {
            font-family: 'Syne', sans-serif;
            font-size: 15px;
            font-weight: 700;
            color: #0f0e1a;
        }

        /* Boutons Burger / Fermer */
        .burger-btn, .sidebar-close-btn {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #0f0e1a;
            display: none; /* Masqué par défaut sur desktop */
            align-items: center;
            justify-content: center;
        }
        .sidebar-close-btn {
            color: rgba(255,255,255,0.6);
        }
        .sidebar-close-btn:hover {
            color: #fff;
        }

        .header-right { display: flex; align-items: center; gap: 12px; }

        .avatar {
            width: 34px; height: 34px;
            border-radius: 50%;
            background: var(--indigo-light);
            color: var(--indigo);
            display: flex; align-items: center; justify-content: center;
            font-size: 13px; font-weight: 700; font-family: 'Syne', sans-serif;
        }

        /* ── CONTENT ── */
        .main-content {
            margin-left: var(--sidebar-w);
            margin-top: var(--header-h);
            padding: 2rem 1.75rem;
            min-height: calc(100vh - var(--header-h));
            transition: margin-left .3s ease;
        }

        /* Overlay Mobile arrière plan */
        .sidebar-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 14, 26, 0.4);
            backdrop-filter: blur(10px);
            z-index: 100;
            display: none;
        }

        /* ── RESPONSIVE ADAPTATIONS (Écrans inférieurs à 1024px) ── */
        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%); /* On cache la sidebar à gauche */
            }
            .sidebar.open {
                transform: translateX(0); /* On l'affiche quand la classe .open est présente */
            }
            .main-header {
                left: 0; /* Le header prend toute la largeur */
            }
            .main-content {
                margin-left: 0; /* Le contenu prend toute la largeur */
                padding: 1.5rem 1rem;
            }
            .burger-btn, .sidebar-close-btn {
                display: flex; /* On affiche les boutons de contrôle du menu */
            }
            .sidebar-overlay.open {
                display: block; /* On affiche l'arrière plan sombre quand le menu est ouvert */
            }
        }

        /* Vos styles existants préservés ci-dessous... */
        .breadcrumb { display: flex; align-items: center; gap: 6px; font-size: 12px; color: #888; margin-bottom: 1.5rem; }
        .breadcrumb a { color: var(--indigo); text-decoration: none; font-weight: 500; }
        .breadcrumb a:hover { text-decoration: underline; }
        .btn { display: inline-flex; align-items: center; gap: 7px; padding: 9px 18px; border-radius: var(--radius-sm); font-size: 13.5px; font-weight: 600; cursor: pointer; border: none; text-decoration: none; transition: all .15s; font-family: 'DM Sans', sans-serif; }
        .btn i { font-size: 18px; }
        .btn-primary   { background: var(--indigo); color: #fff; }
        .btn-primary:hover  { background: var(--indigo-dark); transform: translateY(-1px); }
        .btn-secondary { background: #fff; color: #333; border: 1px solid #ddd; }
        .btn-secondary:hover { border-color: var(--indigo-mid); background: var(--indigo-light); }
        .btn-danger    { background: var(--red-light); color: var(--red); border: 1px solid #F7C1C1; }
        .btn-danger:hover   { background: #F7C1C1; }
        .btn-warning   { background: var(--amber-light); color: var(--amber); border: 1px solid #FAC775; }
        .btn-warning:hover  { background: #FAC775; }
        .btn-sm { padding: 6px 12px; font-size: 12.5px; }
        .btn-icon { padding: 8px; }
        .card { background: #fff; border: 1px solid #ebebeb; border-radius: var(--radius); padding: 1.5rem; }
        .form-group { display: flex; flex-direction: column; gap: 6px; }
        .form-label { font-size: 12px; font-weight: 700; color: #555; letter-spacing: .04em; text-transform: uppercase; }
        .form-control { padding: 10px 14px; border: 1.5px solid #e5e5e5; border-radius: var(--radius-sm); font-size: 14px; font-family: 'DM Sans', sans-serif; color: #1a1a2e; background: #fafafa; transition: border-color .2s, box-shadow .2s; width: 100%; }
        .form-control:focus { outline: none; border-color: var(--indigo); box-shadow: 0 0 0 4px var(--indigo-light); background: #fff; }
        textarea.form-control { resize: vertical; min-height: 90px; }
        select.form-control { cursor: pointer; }
        .form-hint { font-size: 12px; color: #999; }
        .form-error { font-size: 12px; color: var(--red); }
        .table-wrap { overflow-x: auto; border-radius: var(--radius); border: 1px solid #ebebeb; }
        table.admin-table { width: 100%; border-collapse: collapse; }
        .admin-table thead { background: #fafafa; }
        .admin-table th { padding: 11px 16px; text-align: left; font-size: 11px; font-weight: 700; letter-spacing: .07em; text-transform: uppercase; color: #777; border-bottom: 1px solid #ebebeb; }
        .admin-table td { padding: 13px 16px; border-bottom: 1px solid #f5f5f5; font-size: 14px; background: #fff; vertical-align: middle; }
        .admin-table tr:last-child td { border-bottom: none; }
        .admin-table tr:hover td { background: #fdfcff; }
        .badge { display: inline-flex; align-items: center; padding: 3px 10px; border-radius: 100px; font-size: 11px; font-weight: 700; }
        .badge-indigo  { background: var(--indigo-light); color: var(--indigo); }
        .badge-green   { background: var(--green-light);  color: var(--green);  }
        .badge-red     { background: var(--red-light);    color: var(--red);    }
        .badge-amber   { background: var(--amber-light);  color: var(--amber);  }
        .alert { display: flex; align-items: flex-start; gap: 10px; padding: 12px 16px; border-radius: var(--radius-sm); font-size: 13.5px; font-weight: 500; margin-bottom: 1.25rem; }
        .alert-success { background: var(--green-light);  color: var(--green);  border-left: 3px solid var(--green);  }
        .alert-error   { background: var(--red-light);    color: var(--red);    border-left: 3px solid var(--red);    }
        .alert i { font-size: 18px; flex-shrink: 0; }
        .icon-preview-box { width: 52px; height: 52px; border-radius: var(--radius-sm); background: var(--indigo-light); border: 2px dashed var(--indigo-mid); display: flex; align-items: center; justify-content: center; font-size: 22px; color: var(--indigo); font-family: 'Syne', sans-serif; font-weight: 800; overflow: hidden; }
        .drop-zone { border: 2px dashed #ddd; border-radius: var(--radius); padding: 2rem; text-align: center; cursor: pointer; transition: all .2s; color: #888; }
        .drop-zone:hover, .drop-zone.drag-over { border-color: var(--indigo); background: var(--indigo-light); color: var(--indigo); }
        .stat-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 1rem; margin-bottom: 2rem; }
        .stat-card { background: #fff; border: 1px solid #ebebeb; border-radius: var(--radius); padding: 1.2rem 1.4rem; }
        .stat-card-val { font-family: 'Syne', sans-serif; font-size: 28px; font-weight: 800; color: var(--indigo); }
        .stat-card-lbl { font-size: 12px; font-weight: 600; color: #888; margin-top: 2px; text-transform: uppercase; letter-spacing: .05em; }
        .stat-card-icon { width: 38px; height: 38px; border-radius: 10px; background: var(--indigo-light); color: var(--indigo); display: flex; align-items: center; justify-content: center; font-size: 20px; margin-bottom: .75rem; }
        .modal-overlay { position: fixed; inset: 0; background: rgba(15,14,26,.5); display: flex; align-items: center; justify-content: center; z-index: 200; opacity: 0; pointer-events: none; transition: opacity .2s; }
        .modal-overlay.open { opacity: 1; pointer-events: auto; }
        .modal-box { background: #fff; border-radius: var(--radius); padding: 1.75rem; width: min(520px, 95vw); box-shadow: 0 20px 60px rgba(0,0,0,.15); transform: translateY(10px); transition: transform .2s; }
        .modal-overlay.open .modal-box { transform: translateY(0); }
        .modal-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem; }
        .modal-title { font-family: 'Syne', sans-serif; font-size: 17px; font-weight: 800; }
        .modal-close { background: none; border: none; font-size: 22px; color: #aaa; cursor: pointer; line-height: 1; }
        .modal-close:hover { color: #333; }
        .modal-footer { display: flex; justify-content: flex-end; gap: 8px; margin-top: 1.25rem; padding-top: 1.25rem; border-top: 1px solid #f0f0f0; }
    </style>
</head>
<body>

{{-- Voile noir d'arrière-plan sur mobile --}}
<div class="sidebar-overlay" id="sidebarOverlay"></div>

{{-- ═══════════════ SIDEBAR ═══════════════ --}}
<aside class="sidebar" id="sidebar">
    <div class="sidebar-logo">
        <div class="sidebar-logo-content">
            <img src="{{ asset('4.svg') }}" alt="Logo" class='w-10'>
        </div>
        <button class="sidebar-close-btn" id="sidebarCloseBtn">
            <i class='bx bx-x'></i>
        </button>
    </div>

    <nav class="sidebar-nav">
        <span class="nav-section-label">Principal</span>

        <a href="{{ route('admin.applications.index') }}"
           class="nav-link {{ request()->routeIs('admin.applications.*') ? 'active' : '' }}">
            <i class='bx bx-grid-alt'></i> Applications
        </a>

        <a href="{{ route('admin.categories.index') }}"
           class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
            <i class='bx bx-category'></i> Catégories
        </a>

        <a href="{{ route('admin.icones.index') }}"
           class="nav-link {{ request()->routeIs('admin.icones.*') ? 'active' : '' }}">
            <i class='bx bx-image'></i> Icônes
        </a>

        <span class="nav-section-label" style="margin-top:.5rem">Accès rapide</span>

        <a href="{{ route('services.applications') }}" target="_blank" class="nav-link">
            <i class='bx bx-link-external'></i> Voir le site
        </a>
    </nav>

    <div class="sidebar-footer">
        <form method="POST" action="">
            @csrf
            <button type="submit" class="nav-link" style="width:100%;background:none;border:none;cursor:pointer;text-align:left;">
                <i class='bx bx-log-out'></i> Déconnexion
            </button>
        </form>
    </div>
</aside>

{{-- ═══════════════ HEADER ═══════════════ --}}
<header class="main-header">
    <div class="header-left">
        <button class="burger-btn" id="burgerBtn">
            <i class='bx bx-menu'></i>
        </button>
        <div class="header-title">@yield('page-title', 'Administration')</div>
    </div>
    <div class="header-right">
        <div class="avatar">{{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 2)) }}</div>
    </div>
</header>

{{-- ═══════════════ CONTENT ═══════════════ --}}
<main class="main-content">

    {{-- Alerts globales --}}
    @if(session('success'))
        <div class="alert alert-success">
            <i class='bx bx-check-circle'></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            <i class='bx bx-error-circle'></i>
            {{ session('error') }}
        </div>
    @endif

    @yield('content')
</main>

{{-- ═══════════════ JAVASCRIPT BURGER ═══════════════ --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const burgerBtn = document.getElementById('burgerBtn');
        const sidebarCloseBtn = document.getElementById('sidebarCloseBtn');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        // Fonction pour ouvrir le menu
        function openMenu() {
            sidebar.classList.add('open');
            sidebarOverlay.classList.add('open');
            document.body.style.overflow = 'hidden'; // Empêche le scroll en arrière plan
        }

        // Fonction pour fermer le menu
        function closeMenu() {
            sidebar.classList.remove('open');
            sidebarOverlay.classList.remove('open');
            document.body.style.overflow = ''; // Rétablit le scroll
        }

        // Événements
        burgerBtn.addEventListener('click', openMenu);
        sidebarCloseBtn.addEventListener('click', closeMenu);
        sidebarOverlay.addEventListener('click', closeMenu);
    });
</script>

@stack('scripts')
</body>
</html>