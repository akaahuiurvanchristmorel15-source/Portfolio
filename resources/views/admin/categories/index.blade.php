@extends('admin.layout.app')

@section('title', 'Catégories')
@section('page-title', 'Catégories')

@section('content')

{{-- Styles de la page (Responsives & Sticky) --}}
<style>
    .admin-grid {
        display: grid;
        grid-template-columns: 1fr; /* Par défaut sur Mobile : 1 seule colonne */
        gap: 1.5rem;
        align-items: start;
    }

    /* Écrans PC et Tablettes paysage (> 1024px) */
    @media (min-width: 1024px) {
        .admin-grid {
            grid-template-columns: 1fr 380px; /* Grille à 2 colonnes */
        }
        .sticky-form {
            position: sticky;
            top: calc(var(--header-h) + 1.5rem);
        }
    }

    /* Gestion de l'affichage Desktop vs Mobile */
    @media (min-width: 769px) {
        .desktop-only { display: block; }
        .mobile-only { display: none !important; }
    }

    @media (max-width: 768px) {
        .desktop-only { display: none !important; }
        .mobile-only { display: block; }

        /* Style de la grille de cartes sur mobile */
        .mobile-cards-grid {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .category-mobile-card {
            background: #ffffff;
            border: 1px solid #edf2f7;
            border-radius: 12px;
            padding: 1.25rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
        }

        .card-mobile-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 0.75rem;
            margin-bottom: 0.75rem;
        }

        .card-mobile-title {
            font-weight: 700;
            font-size: 15px;
            color: #0f0e1a;
        }

        .card-mobile-desc {
            font-size: 13px;
            color: #666;
            line-height: 1.4;
            margin: 0 0 1rem 0;
            background: #f8fafc;
            padding: 0.5rem 0.75rem;
            border-radius: 6px;
        }

        .card-mobile-body {
            border-top: 1px solid #f1f5f9;
            padding-top: 0.5rem;
            margin-bottom: 1rem;
        }

        .card-mobile-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.4rem 0;
            font-size: 13px;
        }

        .card-mobile-label {
            color: #718096;
            font-weight: 500;
        }

        .card-mobile-actions {
            display: flex;
            gap: 0.5rem;
            border-top: 1px solid #f1f5f9;
            padding-top: 0.75rem;
        }
    }
</style>

<div class="breadcrumb">
    <i class='bx bx-home-alt'></i>
    <span>Admin</span>
    <i class='bx bx-chevron-right'></i>
    <span style="color:#1a1a2e;font-weight:600">Catégories</span>
</div>

<div class="admin-grid">

    {{-- ── COLONNE GAUCHE : LISTE DES CATÉGORIES ── --}}
    <div>
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem">
            <h1 style="font-family:'Syne',sans-serif;font-size:20px;font-weight:800">
                Toutes les catégories
                <span style="font-size:14px;font-weight:500;color:#aaa;margin-left:8px">
                    ({{ $categories->count() }})
                </span>
            </h1>
        </div>

        {{-- Vue Desktop : Tableau classique --}}
        <div class="table-wrap desktop-only">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Slug</th>
                        <th>Applications</th>
                        <th>Ordre</th>
                        <th style="width:110px">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $cat)
                        <tr>
                            <td>
                                <div style="font-weight:600;font-size:14px">{{ $cat->nom }}</div>
                                @if($cat->description)
                                    <div style="font-size:12px;color:#999;margin-top:2px">
                                        {{ Str::limit($cat->description, 50) }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                <code style="font-family:'DM Mono',monospace;font-size:12px;background:#f5f5f5;padding:2px 7px;border-radius:5px;color:#534AB7">
                                    {{ $cat->slug }}
                                </code>
                            </td>
                            <td>
                                <span class="badge badge-indigo">
                                    {{ $cat->applications_count }}
                                    {{ Str::plural('app', $cat->applications_count) }}
                                </span>
                            </td>
                            <td style="font-family:'DM Mono',monospace;font-size:13px;color:#aaa">
                                {{ $cat->ordre }}
                            </td>
                            <td>
                                <div style="display:flex;gap:5px">
                                    <a href="{{ route('admin.categories.edit', $cat) }}"
                                       class="btn btn-warning btn-sm btn-icon" title="Modifier">
                                        <i class='bx bx-edit'></i>
                                    </a>
                                    <button type="button"
                                            class="btn btn-danger btn-sm btn-icon"
                                            title="Supprimer"
                                            @if($cat->applications_count > 0)
                                                disabled
                                                title="Impossible : des applications y sont rattachées"
                                                style="opacity:.4;cursor:not-allowed"
                                            @else
                                                onclick="confirmDeleteCat({{ $cat->id }}, '{{ addslashes($cat->nom) }}')"
                                            @endif>
                                        <i class='bx bx-trash'></i>
                                    </button>
                                    
                                    {{-- Formulaire de suppression (Accessible globalement par le DOM) --}}
                                    <form id="del-cat-{{ $cat->id }}" method="POST" action="{{ route('admin.categories.destroy', $cat) }}" style="display:none">
                                        @csrf @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Vue Mobile : Liste sous forme de vraies cartes --}}
        <div class="mobile-only mobile-cards-grid">
            @forelse($categories as $cat)
                <div class="category-mobile-card">
                    <div class="card-mobile-header">
                        <div class="card-mobile-title">{{ $cat->nom }}</div>
                        <span class="badge badge-indigo" style="font-size: 11px;">
                            {{ $cat->applications_count }} {{ Str::plural('app', $cat->applications_count) }}
                        </span>
                    </div>

                    @if($cat->description)
                        <p class="card-mobile-desc">{{ Str::limit($cat->description, 80) }}</p>
                    @endif

                    <div class="card-mobile-body">
                        <div class="card-mobile-row">
                            <span class="card-mobile-label">Slug :</span>
                            <code style="font-family:'DM Mono',monospace;font-size:11px;background:#f5f5f5;padding:2px 6px;border-radius:4px;color:#534AB7">
                                {{ $cat->slug }}
                            </code>
                        </div>
                        <div class="card-mobile-row">
                            <span class="card-mobile-label">Ordre :</span>
                            <span style="font-family:'DM Mono',monospace;color:#4a5568;font-weight:600;">{{ $cat->ordre }}</span>
                        </div>
                    </div>

                    <div class="card-mobile-actions">
                        <a href="{{ route('admin.categories.edit', $cat) }}" class="btn btn-warning btn-sm" style="flex: 1; justify-content: center; display: inline-flex; gap: 4px; align-items: center;">
                            <i class='bx bx-edit'></i> Modifier
                        </a>
                        <button type="button" class="btn btn-danger btn-sm" style="flex: 1; justify-content: center; display: inline-flex; gap: 4px; align-items: center;"
                                @if($cat->applications_count > 0)
                                    disabled
                                    style="opacity:.4;cursor:not-allowed"
                                @else
                                    onclick="confirmDeleteCat({{ $cat->id }}, '{{ addslashes($cat->nom) }}')"
                                @endif>
                            <i class='bx bx-trash'></i> Supprimer
                        </button>
                    </div>
                </div>
            @empty
                <div style="text-align:center;padding:3rem;background:#fff;border-radius:12px;border:1px dashed #cbd5e1;color:#aaa">
                    <i class='bx bx-category' style="font-size:40px;display:block;margin-bottom:.5rem"></i>
                    Aucune catégorie créée.
                </div>
            @endforelse
        </div>
    </div>

    {{-- ── COLONNE DROITE : FORMULAIRE AJOUT RAPIDE ── --}}
    <div class="card sticky-form">
        <h2 style="font-family:'Syne',sans-serif;font-size:16px;font-weight:800;margin-bottom:1.2rem">
            <i class='bx bx-plus-circle' style="color:var(--indigo);vertical-align:middle"></i>
            Nouvelle catégorie
        </h2>

        <form method="POST" action="{{ route('admin.categories.store') }}" style="display:flex;flex-direction:column;gap:1rem">
            @csrf

            <div class="form-group">
                <label class="form-label" for="nom">Nom <span style="color:#A32D2D">*</span></label>
                <input type="text" id="nom" name="nom"
                       class="form-control @error('nom') border-red-400 @enderror"
                       value="{{ old('nom') }}"
                       placeholder="ex : Intelligence Artificielle">
                @error('nom') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="description">Description</label>
                <textarea id="description" name="description"
                          class="form-control"
                          style="min-height:70px"
                          placeholder="Description courte (optionnel)...">{{ old('description') }}</textarea>
            </div>

            <div class="form-group">
                <label class="form-label" for="ordre">Ordre</label>
                <input type="number" id="ordre" name="ordre" min="0"
                       class="form-control"
                       value="{{ old('ordre', 0) }}">
                <span class="form-hint">Détermine l'ordre dans les filtres du site.</span>
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center">
                <i class='bx bx-save'></i> Créer la catégorie
            </button>
        </form>
    </div>
</div>

{{-- Modal suppression catégorie --}}
<div class="modal-overlay" id="deleteCatModal">
    <div class="modal-box" style="max-width:420px; width: 90%; margin: auto;">
        <div class="modal-header">
            <span class="modal-title" style="color:#A32D2D">Supprimer la catégorie</span>
            <button class="modal-close" onclick="closeCatModal()">&times;</button>
        </div>
        <p style="font-size:14px;color:#555;line-height:1.6">
            Vous allez supprimer la catégorie <strong id="deleteCatName" style="color:#0f0e1a"></strong>.
            Assurez-vous qu'aucune application n'y est rattachée.
        </p>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeCatModal()">Annuler</button>
            <button class="btn btn-danger" id="confirmCatDeleteBtn">
                <i class='bx bx-trash'></i> Supprimer
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let pendingCatId = null;

    function confirmDeleteCat(id, name) {
        pendingCatId = id;
        document.getElementById('deleteCatName').textContent = name;
        document.getElementById('deleteCatModal').classList.add('open');
    }

    function closeCatModal() {
        document.getElementById('deleteCatModal').classList.remove('open');
        pendingCatId = null;
    }

    document.getElementById('confirmCatDeleteBtn').addEventListener('click', function () {
        if (pendingCatId) document.getElementById('del-cat-' + pendingCatId).submit();
    });

    document.getElementById('deleteCatModal').addEventListener('click', function (e) {
        if (e.target === this) closeCatModal();
    });
</script>
@endpush

@endsection