<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Authentification')</title>

    <link rel="icon" type="image/svg" href="{{ asset('5.svg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;1,400&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --ink:        #0D0C1A;
            --indigo:     #534AB7;
            --indigo-d:   #3C3489;
            --indigo-l:   #EEEDFE;
            --indigo-m:   #AFA9EC;
            --surface:    #F6F5FF;
            --border:     #E4E2F8;
            --text:       #1a1830;
            --muted:      #7971B8;
            --green:      #3B6D11;
            --green-l:    #EAF3DE;
            --red:        #A32D2D;
            --red-l:      #FCEBEB;
            --radius:     14px;
            --radius-sm:  8px;
        }

        body {
            font-family: 'DM Sans', system-ui, sans-serif;
            background: var(--surface);
            min-height: 100vh;
            display: flex;
        }

        /* ── SPLIT LAYOUT ── */
        .auth-wrap {
            display: grid;
            grid-template-columns: 1fr 1fr;
            min-height: 100vh;
            width: 100%;
        }

        /* ── PANNEAU GAUCHE (décoratif) ── */
        .auth-panel {
            background: var(--ink);
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 2.5rem;
        }

        /* Grille de points en fond */
        .auth-panel::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                radial-gradient(circle, rgba(83,74,183,.35) 1px, transparent 1px);
            background-size: 28px 28px;
            animation: bgShift 20s linear infinite;
        }

        @keyframes bgShift {
            0%   { background-position: 0 0; }
            100% { background-position: 28px 28px; }
        }

        /* Orbes lumineux */
        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(70px);
            opacity: .5;
            animation: float 8s ease-in-out infinite;
        }
        .orb-1 { width: 320px; height: 320px; background: #534AB7; top: -80px; left: -80px; animation-delay: 0s; }
        .orb-2 { width: 200px; height: 200px; background: #7B5EA7; bottom: 80px; right: -60px; animation-delay: -3s; }
        .orb-3 { width: 150px; height: 150px; background: #2E8B8B; bottom: -40px; left: 40px; animation-delay: -5s; }

        @keyframes float {
            0%, 100% { transform: translateY(0) scale(1); }
            50%       { transform: translateY(-20px) scale(1.05); }
        }

        .panel-logo {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .panel-logo-icon {
            width: 38px; height: 38px;
            background: var(--indigo);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-family: 'Syne', sans-serif; font-weight: 800;
            font-size: 18px; color: #fff;
            box-shadow: 0 0 0 4px rgba(83,74,183,.3);
        }

        .panel-logo-text {
            font-family: 'Syne', sans-serif;
            font-size: 18px; font-weight: 800;
            color: #fff;
        }

        .panel-logo-text span { color: var(--indigo-m); }

        .panel-body {
            position: relative;
            z-index: 1;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 2rem;
        }

        .panel-title {
            font-family: 'Syne', sans-serif;
            font-size: clamp(28px, 3vw, 44px);
            font-weight: 800;
            color: #fff;
            line-height: 1.15;
        }

        .panel-title em { color: var(--indigo-m); font-style: normal; }

        .panel-desc {
            font-size: 15px;
            color: rgba(255,255,255,.55);
            line-height: 1.7;
            max-width: 360px;
        }

        .panel-features {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .feat-item {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 14px;
            color: rgba(255,255,255,.75);
        }

        .feat-dot {
            width: 8px; height: 8px;
            border-radius: 50%;
            background: var(--indigo-m);
            flex-shrink: 0;
        }

        .panel-footer {
            position: relative; z-index: 1;
            font-size: 12px;
            color: rgba(255,255,255,.25);
        }

        /* ── PANNEAU DROIT (formulaire) ── */
        .auth-form-wrap {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem 3rem;
            background: #fff;
            position: relative;
        }

        .auth-form-inner {
            width: 100%;
            max-width: 420px;
            animation: slideUp .5s cubic-bezier(.22,1,.36,1) both;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .auth-heading {
            font-family: 'Syne', sans-serif;
            font-size: 26px; font-weight: 800;
            color: var(--ink);
            margin-bottom: .4rem;
        }

        .auth-subheading {
            font-size: 14px;
            color: var(--muted);
            margin-bottom: 2rem;
        }

        .auth-subheading a {
            color: var(--indigo);
            font-weight: 600;
            text-decoration: none;
        }
        .auth-subheading a:hover { text-decoration: underline; }

        /* ── INPUTS ── */
        .form-group { display: flex; flex-direction: column; gap: 6px; margin-bottom: 1rem; }

        .form-label {
            font-size: 12px; font-weight: 700;
            color: #555;
            letter-spacing: .05em; text-transform: uppercase;
        }

        .input-wrap { position: relative; }

        .input-icon {
            position: absolute; left: 13px; top: 50%; transform: translateY(-50%);
            color: #bbb; font-size: 18px;
            pointer-events: none;
            transition: color .2s;
        }

        .form-input {
            width: 100%;
            padding: 11px 14px 11px 40px;
            border: 1.5px solid var(--border);
            border-radius: var(--radius-sm);
            font-size: 14px;
            font-family: 'DM Sans', sans-serif;
            color: var(--text);
            background: var(--surface);
            transition: border-color .2s, box-shadow .2s, background .2s;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--indigo);
            box-shadow: 0 0 0 4px var(--indigo-l);
            background: #fff;
        }

        .form-input:focus + .input-icon,
        .input-wrap:focus-within .input-icon { color: var(--indigo); }

        .form-input.is-error { border-color: var(--red); background: var(--red-l); }
        .form-input.is-error:focus { box-shadow: 0 0 0 4px rgba(163,45,45,.12); }

        .form-error {
            font-size: 12px; color: var(--red);
            display: flex; align-items: center; gap: 4px;
        }

        /* Toggle password visibility */
        .input-action {
            position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
            background: none; border: none;
            color: #bbb; cursor: pointer; font-size: 18px;
            transition: color .2s;
            line-height: 1;
        }
        .input-action:hover { color: var(--indigo); }

        /* ── SUBMIT BTN ── */
        .btn-submit {
            width: 100%;
            padding: 12px;
            background: var(--indigo);
            color: #fff;
            border: none;
            border-radius: var(--radius-sm);
            font-size: 15px; font-weight: 700;
            font-family: 'Syne', sans-serif;
            cursor: pointer;
            transition: background .2s, transform .15s, box-shadow .2s;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            margin-top: .25rem;
            letter-spacing: .01em;
        }

        .btn-submit:hover {
            background: var(--indigo-d);
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(83,74,183,.35);
        }

        .btn-submit:active { transform: translateY(0); }

        /* ── DIVIDER ── */
        .divider {
            display: flex; align-items: center; gap: 12px;
            color: #ccc; font-size: 12px;
            margin: 1.25rem 0;
        }
        .divider::before, .divider::after {
            content: ''; flex: 1;
            height: 1px; background: var(--border);
        }

        /* ── REMEMBER / FORGOT ── */
        .auth-extras {
            display: flex; align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }

        .checkbox-label {
            display: flex; align-items: center; gap: 8px;
            font-size: 13px; color: #555; cursor: pointer;
        }

        .checkbox-label input[type=checkbox] {
            width: 16px; height: 16px;
            accent-color: var(--indigo);
            cursor: pointer;
        }

        .link-muted {
            font-size: 13px; color: var(--muted);
            text-decoration: none; font-weight: 500;
        }
        .link-muted:hover { color: var(--indigo); text-decoration: underline; }

        /* ── STRENGTH METER ── */
        .strength-wrap { margin-top: 6px; }
        .strength-bars {
            display: flex; gap: 4px; margin-bottom: 4px;
        }
        .strength-bar {
            flex: 1; height: 3px; border-radius: 100px;
            background: #eee;
            transition: background .3s;
        }
        .strength-label { font-size: 11px; color: #aaa; }

        /* ── ALERT FLASH ── */
        .flash-alert {
            display: flex; align-items: flex-start; gap: 9px;
            padding: 11px 14px; border-radius: var(--radius-sm);
            font-size: 13.5px; font-weight: 500;
            margin-bottom: 1.25rem;
            animation: slideUp .4s both;
        }
        .flash-success { background: var(--green-l); color: var(--green); border-left: 3px solid var(--green); }
        .flash-error   { background: var(--red-l);   color: var(--red);   border-left: 3px solid var(--red);   }
        .flash-alert i { font-size: 17px; flex-shrink: 0; margin-top: 1px; }

        /* ── RESPONSIVE ── */
        @media (max-width: 860px) {
            .auth-wrap { grid-template-columns: 1fr; }
            .auth-panel { display: none; }
            .auth-form-wrap { padding: 2rem 1.5rem; }
        }
    </style>
</head>
<body>

<div class="auth-wrap">

    {{-- ══ PANNEAU GAUCHE ══ --}}
    <div class="auth-panel">
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
        <div class="orb orb-3"></div>

        <div class="panel-logo">
            <div class="panel-logo-icon">A</div>
        </div>

        <div class="panel-body">
            @yield('panel-content')
        </div>

        <div class="panel-footer">
            &copy; {{ date('Y') }} AppAdmin. Tous droits réservés.
        </div>
    </div>

    {{-- ══ PANNEAU DROIT (formulaire) ══ --}}
    <div class="auth-form-wrap">
        <div class="auth-form-inner">

            {{-- Flash messages --}}
            @if(session('success'))
                <div class="flash-alert flash-success">
                    <i class='bx bx-check-circle'></i>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="flash-alert flash-error">
                    <i class='bx bx-error-circle'></i>
                    {{ session('error') }}
                </div>
            @endif

            @yield('form-content')
        </div>
    </div>
</div>

@stack('scripts')
</body>
</html>