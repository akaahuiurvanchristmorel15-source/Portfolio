@extends('layout.auth')

@section('title', 'Créer un compte')

{{-- ══ PANNEAU GAUCHE ══ --}}
@section('panel-content')
    <div>
        <p class="panel-title">
            Rejoignez<br>la <em>plateforme</em>
        </p>
        <p class="panel-desc">
            Créez votre compte en quelques secondes et accédez
            immédiatement à toutes nos applications.
        </p>
    </div>

    <div class="panel-features">
        <div class="feat-item"><div class="feat-dot"></div> Inscription gratuite et instantanée</div>
        <div class="feat-item"><div class="feat-dot"></div> Accès à toutes les catégories</div>
        <div class="feat-item"><div class="feat-dot"></div> Données sécurisées et chiffrées</div>
        <div class="feat-item"><div class="feat-dot"></div> Interface pensée pour la productivité</div>
    </div>
@endsection

{{-- ══ FORMULAIRE ══ --}}
@section('form-content')

    <h1 class="auth-heading">Créer un compte</h1>
    <p class="auth-subheading">
        Déjà inscrit ?
        <a href="{{ route('login') }}">Se connecter</a>
    </p>

    @if($errors->any())
        <div class="flash-alert flash-error">
            <i class='bx bx-error-circle'></i>
            <div>
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}" novalidate>
        @csrf

        {{-- Nom --}}
        <div class="form-group">
            <label class="form-label" for="name">Nom complet</label>
            <div class="input-wrap">
                <input
                    type="text"
                    id="name"
                    name="name"
                    class="form-input @error('name') is-error @enderror"
                    value="{{ old('name') }}"
                    placeholder="Jean Dupont"
                    autocomplete="name"
                    autofocus
                >
                <i class='bx bx-user input-icon'></i>
            </div>
            @error('name')
                <span class="form-error"><i class='bx bx-error-circle'></i> {{ $message }}</span>
            @enderror
        </div>

        {{-- Email --}}
        <div class="form-group">
            <label class="form-label" for="email">Adresse email</label>
            <div class="input-wrap">
                <input
                    type="email"
                    id="email"
                    name="email"
                    class="form-input @error('email') is-error @enderror"
                    value="{{ old('email') }}"
                    placeholder="vous@exemple.com"
                    autocomplete="email"
                >
                <i class='bx bx-envelope input-icon'></i>
            </div>
            @error('email')
                <span class="form-error"><i class='bx bx-error-circle'></i> {{ $message }}</span>
            @enderror
        </div>

        {{-- Mot de passe --}}
        <div class="form-group">
            <label class="form-label" for="password">Mot de passe</label>
            <div class="input-wrap">
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="form-input @error('password') is-error @enderror"
                    placeholder="••••••••"
                    autocomplete="new-password"
                    oninput="updateStrength(this.value)"
                >
                <i class='bx bx-lock input-icon'></i>
                <button type="button" class="input-action" onclick="togglePassword('password', this)" tabindex="-1">
                    <i class='bx bx-hide'></i>
                </button>
            </div>

            {{-- Indicateur de force --}}
            <div class="strength-wrap" id="strengthWrap" style="display:none">
                <div class="strength-bars">
                    <div class="strength-bar" id="sb1"></div>
                    <div class="strength-bar" id="sb2"></div>
                    <div class="strength-bar" id="sb3"></div>
                    <div class="strength-bar" id="sb4"></div>
                </div>
                <span class="strength-label" id="strengthLabel">Trop court</span>
            </div>

            @error('password')
                <span class="form-error"><i class='bx bx-error-circle'></i> {{ $message }}</span>
            @enderror
        </div>

        {{-- Confirmation --}}
        <div class="form-group" style="margin-bottom:1.5rem">
            <label class="form-label" for="password_confirmation">Confirmer le mot de passe</label>
            <div class="input-wrap">
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    class="form-input"
                    placeholder="••••••••"
                    autocomplete="new-password"
                >
                <i class='bx bx-lock-open input-icon'></i>
                <button type="button" class="input-action"
                        onclick="togglePassword('password_confirmation', this)" tabindex="-1">
                    <i class='bx bx-hide'></i>
                </button>
            </div>
        </div>

        {{-- CGU (optionnel) --}}
        <div style="margin-bottom:1.25rem">
            <label class="checkbox-label" style="align-items:flex-start">
                <input type="checkbox" name="cgu" id="cgu" required
                       style="margin-top:2px;width:16px;height:16px;accent-color:var(--indigo);flex-shrink:0">
                <span style="font-size:13px;color:#555;line-height:1.5">
                    J'accepte les
                    <a href="#" style="color:var(--indigo);font-weight:600;text-decoration:none">
                        conditions d'utilisation
                    </a>
                    et la
                    <a href="#" style="color:var(--indigo);font-weight:600;text-decoration:none">
                        politique de confidentialité
                    </a>.
                </span>
            </label>
        </div>

        <button type="submit" class="btn-submit">
            <i class='bx bx-user-plus'></i> Créer mon compte
        </button>

    </form>

@endsection

@push('scripts')
<script>
    /* ── Toggle visibilité mot de passe ── */
    function togglePassword(fieldId, btn) {
        const input = document.getElementById(fieldId);
        const icon  = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'bx bx-show';
        } else {
            input.type = 'password';
            icon.className = 'bx bx-hide';
        }
    }

    /* ── Indicateur de force du mot de passe ── */
    const bars   = ['sb1','sb2','sb3','sb4'].map(id => document.getElementById(id));
    const label  = document.getElementById('strengthLabel');
    const wrap   = document.getElementById('strengthWrap');

    const LEVELS = [
        { color: '#A32D2D', label: 'Très faible' },
        { color: '#C97C2B', label: 'Faible'      },
        { color: '#B5A91B', label: 'Moyen'        },
        { color: '#3B6D11', label: 'Fort'         },
    ];

    function getScore(pwd) {
        let s = 0;
        if (pwd.length >= 8)               s++;
        if (/[A-Z]/.test(pwd))             s++;
        if (/[0-9]/.test(pwd))             s++;
        if (/[^A-Za-z0-9]/.test(pwd))      s++;
        return s;
    }

    function updateStrength(val) {
        if (!val) { wrap.style.display = 'none'; return; }
        wrap.style.display = 'block';

        const score = Math.min(getScore(val), 4);
        const lvl   = LEVELS[Math.max(score - 1, 0)];

        bars.forEach((b, i) => {
            b.style.background = i < score ? lvl.color : '#eee';
        });

        label.textContent  = lvl.label;
        label.style.color  = lvl.color;
    }
</script>
@endpush