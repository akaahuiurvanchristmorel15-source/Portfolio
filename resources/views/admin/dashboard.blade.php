@extends('admin.layout.app')

@section('title', 'Tableau de bord')
@section('page-title', 'Tableau de bord')

@section('content')

{{-- Styles réactifs locaux pour le Dashboard --}}
<style>
    .dashboard-container {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.5rem;
        align-items: start;
    }

    /* Passage en 2 colonnes sur les écrans d'ordinateurs (Desktop) */
    @media (min-width: 992px) {
        .dashboard-container {
            grid-template-columns: 2fr 1fr;
        }
    }

    /* Sécurité pour le défilement du tableau sur mobile */
    .table-wrap {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
</style>

<div class="breadcrumb">
    <i class='bx bx-home-alt'></i>
    <span style="color:#1a1a2e;font-weight:600">Tableau de bord</span>
</div>

{{-- Salutation --}}
<div style="margin-bottom:2rem">
    <h1 style="font-family:'Syne',sans-serif;font-size: calc(20px + 0.5vw);font-weight:800;line-height:1.2">
        Bonjour, {{ Auth::user()->name ?? 'Administrateur' }}
    </h1>
    <p style="font-size:14px;color:#888;margin-top:4px">
        {{ now()->isoFormat('dddd D MMMM YYYY') }} · Voici un aperçu de vos données.
    </p>
</div>

{{-- Stats --}}
<div class="stat-grid" style="grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1rem;margin-bottom:2rem">
    <div class="stat-card">
        <div class="stat-card-icon"><i class='bx bx-grid-alt'></i></div>
        <div class="stat-card-val">{{ $totalApps }}</div>
        <div class="stat-card-lbl">Applications</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon" style="background:#EAF3DE;color:#3B6D11"><i class='bx bx-check-circle'></i></div>
        <div class="stat-card-val" style="color:#3B6D11">{{ $appsActives }}</div>
        <div class="stat-card-lbl">Applications actives</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon" style="background:#FAEEDA;color:#854F0B"><i class='bx bx-category'></i></div>
        <div class="stat-card-val" style="color:#854F0B">{{ $totalCategories }}</div>
        <div class="stat-card-lbl">Catégories</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon" style="background:#E6F1FB;color:#185FA5"><i class='bx bx-image'></i></div>
        <div class="stat-card-val" style="color:#185FA5">{{ $totalIcones }}</div>
        <div class="stat-card-lbl">Icônes</div>
    </div>
</div>

{{-- Conteneur Principal Responsive --}}
<div class="dashboard-container">

    {{-- Applications récentes --}}
    <div>
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem">
            <h2 style="font-family:'Syne',sans-serif;font-size:16px;font-weight:800">
                Applications récentes
            </h2>
            <a href="{{ route('admin.applications.index') }}"
               style="font-size:13px;color:var(--indigo);font-weight:600;text-decoration:none">
                Voir tout <i class='bx bx-right-arrow-alt' style="vertical-align:middle"></i>
            </a>
        </div>

        <div class="table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width:44px"></th>
                        <th>Nom</th>
                        <th>Catégorie</th>
                        <th>Statut</th>
                        <th>Créée le</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentApps as $app)
                        <tr>
                            <td>
                                <div class="icon-preview-box" style="width:34px;height:34px;font-size:15px">
                                    @if($app->icone_url)
                                        <img src="{{ $app->icone_url }}" alt="{{ $app->nom }}"
                                             style="width:24px;height:24px;object-fit:contain">
                                    @else
                                        {{ $app->initiale }}
                                    @endif
                                </div>
                            </td>
                            <td style="font-weight:600;font-size:13.5px;white-space:nowrap">{{ $app->nom }}</td>
                            <td><span class="badge badge-indigo">{{ $app->category->nom ?? '—' }}</span></td>
                            <td>
                                @if($app->actif)
                                    <span class="badge badge-green">Actif</span>
                                @else
                                    <span class="badge badge-red">Inactif</span>
                                @endif
                            </td>
                            <td style="font-size:12px;color:#aaa;font-family:'DM Mono',monospace;white-space:nowrap">
                                {{ $app->created_at->format('d/m/Y') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align:center;padding:2rem;color:#aaa;font-size:13px">
                                Aucune application.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Catégories + Accès rapides --}}
    <div style="display:flex;flex-direction:column;gap:1.25rem">

        {{-- Répartition par catégorie --}}
        <div class="card">
            <h2 style="font-family:'Syne',sans-serif;font-size:15px;font-weight:800;margin-bottom:1rem">
                Répartition par catégorie
            </h2>
            @forelse($categories as $cat)
                <div style="margin-bottom:.75rem">
                    <div style="display:flex;justify-content:space-between;font-size:13px;
                                font-weight:500;margin-bottom:4px">
                        <span>{{ $cat->nom }}</span>
                        <span style="color:#aaa">{{ $cat->applications_count }}</span>
                    </div>
                    <div style="height:5px;background:#f0f0f0;border-radius:100px;overflow:hidden">
                        @php
                            $pct = $totalApps > 0 ? ($cat->applications_count / $totalApps) * 100 : 0;
                        @endphp
                        <div style="height:100%;background:var(--indigo);border-radius:100px;
                                    width:{{ $pct }}%;transition:width .8s ease"></div>
                    </div>
                </div>
            @empty
                <p style="font-size:13px;color:#aaa">Aucune catégorie.</p>
            @endforelse
        </div>

        {{-- Actions rapides --}}
        <div class="card">
            <h2 style="font-family:'Syne',sans-serif;font-size:15px;font-weight:800;margin-bottom:1rem">
                Actions rapides
            </h2>
            <div style="display:flex;flex-direction:column;gap:8px">
                <a href="{{ route('admin.applications.create') }}" class="btn btn-primary" style="justify-content:center">
                    <i class='bx bx-plus'></i> Nouvelle application
                </a>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary" style="justify-content:center">
                    <i class='bx bx-category'></i> Gérer les catégories
                </a>
                <a href="{{ route('admin.icones.index') }}" class="btn btn-secondary" style="justify-content:center">
                    <i class='bx bx-image'></i> Gérer les icônes
                </a>
                <a href="{{ route('services.applications') }}" target="_blank"
                   class="btn btn-secondary" style="justify-content:center">
                    <i class='bx bx-link-external'></i> Voir le site public
                </a>
            </div>
        </div>
    </div>
</div>

@endsection