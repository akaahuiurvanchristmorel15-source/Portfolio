@extends('layout.auth')

@section('title', 'Connexion')

{{-- ══ CONTENU PANNEAU GAUCHE ══ --}}
@section('panel-content')
    <div>
        <p class="panel-title">
            Bon retour<br>parmi nous
        </p>
        <p class="panel-desc">
            Connectez-vous pour accéder à votre espace et découvrir
            toutes les applications disponibles.
        </p>
    </div>

    <div class="panel-features">
        <div class="feat-item"><div class="feat-dot"></div> Accès à toutes les applications</div>
        <div class="feat-item"><div class="feat-dot"></div> Filtrage par catégorie</div>
        <div class="feat-item"><div class="feat-dot"></div> Interface rapide et moderne</div>
        <div class="feat-item"><div class="feat-dot"></div> Tableau de bord administrateur</div>
    </div>
@endsection

{{-- ══ FORMULAIRE ══ --}}
@section('form-content')

    <h1 class="auth-heading">Connexion</h1>
    <p class="auth-subheading">
        Pas encore de compte ?
        <a href="{{ route('register') }}">Créer un compte</a>
    </p>

    {{-- Erreurs globales --}}
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

    <form method="POST" action="{{ route('login') }}" novalidate>
        @csrf

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
                    autofocus
                >
                <i class='bx bx-envelope input-icon'></i>
            </div>
            @error('email')
                <span class="form-error">
                    <i class='bx bx-error-circle'></i> {{ $message }}
                </span>
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
                    autocomplete="current-password"
                >
                <i class='bx bx-lock input-icon'></i>
                <button type="button" class="input-action" onclick="togglePassword('password', this)" tabindex="-1">
                    <i class='bx bx-hide'></i>
                </button>
            </div>
            @error('password')
                <span class="form-error">
                    <i class='bx bx-error-circle'></i> {{ $message }}
                </span>
            @enderror
        </div>

        {{-- Remember + Forgot --}}
        <div class="auth-extras">
            <label class="checkbox-label">
                <input type="checkbox" name="remember" id="remember"
                       {{ old('remember') ? 'checked' : '' }}>
                Se souvenir de moi
            </label>

            {{-- Décommenter si vous avez le mot de passe oublié --}}
            {{-- <a href="{{ route('password.request') }}" class="link-muted">Mot de passe oublié ?</a> --}}
            <span class="link-muted" style="font-style:italic;font-size:12px">Session sécurisée</span>
        </div>

        <button type="submit" class="btn-submit">
            <i class='bx bx-log-in'></i> Se connecter
        </button>

    </form>

@endsection

@push('scripts')
<script>
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
</script>
@endpush