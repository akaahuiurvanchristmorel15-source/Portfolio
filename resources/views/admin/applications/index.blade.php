@extends('admin.layout.app')

@section('title', 'Applications')
@section('page-title', 'Applications')

@section('content')

{{-- Breadcrumb --}}
<div class="breadcrumb">
    <i class='bx bx-home-alt'></i>
    <span>Admin</span>
    <i class='bx bx-chevron-right'></i>
    <span style="color:#1a1a2e;font-weight:600">Applications</span>
</div>

{{-- Stats --}}
<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-card-icon"><i class='bx bx-grid-alt'></i></div>
        <div class="stat-card-val">{{ $applications->total() }}</div>
        <div class="stat-card-lbl">Total applications</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon" style="background:#EAF3DE;color:#3B6D11"><i class='bx bx-check-circle'></i></div>
        <div class="stat-card-val" style="color:#3B6D11">{{ $applications->where('actif', true)->count() }}</div>
        <div class="stat-card-lbl">Actives</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon" style="background:#FAEEDA;color:#854F0B"><i class='bx bx-category'></i></div>
        <div class="stat-card-val" style="color:#854F0B">{{ $categories->count() }}</div>
        <div class="stat-card-lbl">Catégories</div>
    </div>
</div>

{{-- Actions & Filtres --}}
<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;margin-bottom:1.25rem;">
    <form method="GET" action="{{ route('admin.applications.index') }}"
          style="display:flex;gap:.5rem;flex-wrap:wrap;flex:1;max-width:600px">
        <div style="position:relative;flex:1;min-width:180px">
            <i class='bx bx-search' style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#aaa;font-size:18px"></i>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Rechercher..."
                   class="form-control" style="padding-left:38px">
        </div>
        <select name="categorie" class="form-control" style="width:auto;min-width:150px">
            <option value="">Toutes les catégories</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('categorie') == $cat->id ? 'selected' : '' }}>
                    {{ $cat->nom }}
                </option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-secondary btn-sm">
            <i class='bx bx-filter-alt'></i> Filtrer
        </button>
        @if(request()->hasAny(['search','categorie']))
            <a href="{{ route('admin.applications.index') }}" class="btn btn-secondary btn-sm">
                <i class='bx bx-x'></i>
            </a>
        @endif
    </form>

    <a href="{{ route('admin.applications.create') }}" class="btn btn-primary">
        <i class='bx bx-plus'></i> Nouvelle application
    </a>
</div>

{{-- Table --}}
<div class="table-wrap">
    <table class="admin-table responsive-table">
        <thead>
            <tr>
                <th style="width:52px">Icône</th>
                <th>Nom</th>
                <th>Catégorie</th>
                <th>Lien</th>
                <th>Statut</th>
                <th>Ordre</th>
                <th style="width:130px">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($applications as $app)
                <tr>
                    <td data-label="Icône">
                        <div class="icon-preview-box" style="width:40px;height:40px;font-size:18px;display:flex;align-items:center;justify-content:center;">
                            @if($app->icone_url)
                                <img src="{{ Storage::url($app->icone_url) }}" alt="{{ $app->nom }}"
                                    style="width:28px;height:28px;object-fit:contain">
                            @else
                                {{ $app->initiale }}
                            @endif
                        </div>
                    </td>
                    <td data-label="Nom">
                        <div class="table-text-container">
                            <div style="font-weight:600;font-size:14px;color:#0f0e1a">{{ $app->nom }}</div>
                            @if($app->description)
                                <div style="font-size:12px;color:#999;margin-top:2px">
                                    {{ Str::limit($app->description, 55) }}
                                </div>
                            @endif
                        </div>
                    </td>
                    <td data-label="Catégorie">
                        <span class="badge badge-indigo">{{ $app->category->nom ?? '—' }}</span>
                    </td>
                    <td data-label="Lien">
                        <a href="{{ $app->lien }}" target="_blank" class="table-external-link">
                            {{ Str::limit(str_replace('https://','',$app->lien), 30) }}
                            <i class='bx bx-link-external' style="font-size:13px"></i>
                        </a>
                    </td>
                    <td data-label="Statut">
                        @if($app->actif)
                            <span class="badge badge-green"><i class='bx bx-check' style="font-size:13px"></i> Actif</span>
                        @else
                            <span class="badge badge-red"><i class='bx bx-x' style="font-size:13px"></i> Inactif</span>
                        @endif
                    </td>
                    <td data-label="Ordre">
                        <span class="order-text">{{ $app->ordre }}</span>
                    </td>
                    <td data-label="Actions">
                        <div class="actions-wrapper">
                            <a href="{{ route('admin.applications.edit', $app) }}"
                               class="btn btn-warning btn-sm btn-icon" title="Modifier">
                                <i class='bx bx-edit'></i>
                            </a>
                            <button type="button" class="btn btn-danger btn-sm btn-icon"
                                    title="Supprimer"
                                    onclick="confirmDelete({{ $app->id }}, '{{ addslashes($app->nom) }}')">
                                <i class='bx bx-trash'></i>
                            </button>
                        </div>
                        {{-- Formulaire de suppression caché --}}
                        <form id="delete-form-{{ $app->id }}"
                              method="POST"
                              action="{{ route('admin.applications.destroy', $app) }}"
                              style="display:none">
                            @csrf @method('DELETE')
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="empty-row-state">
                        <i class='bx bx-ghost' style="font-size:40px;display:block;margin-bottom:.5rem"></i>
                        Aucune application trouvée.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Pagination --}}
@if($applications->hasPages())
    <div style="margin-top:1.25rem;display:flex;justify-content:flex-end">
        {{ $applications->links() }}
    </div>
@endif

{{-- Modal confirmation suppression --}}
<div class="modal-overlay" id="deleteModal">
    <div class="modal-box" style="max-width:420px">
        <div class="modal-header">
            <span class="modal-title" style="color:#A32D2D">Supprimer l'application</span>
            <button class="modal-close" onclick="closeDeleteModal()">&times;</button>
        </div>
        <p style="font-size:14px;color:#555;line-height:1.6">
            Vous allez supprimer <strong id="deleteAppName" style="color:#0f0e1a"></strong>.
            Cette action est <strong>irréversible</strong> et supprimera également l'icône associée.
        </p>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeDeleteModal()">Annuler</button>
            <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                <i class='bx bx-trash'></i> Supprimer définitivement
            </button>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Design responsive alternatif pour le tableau (Cartes mobiles) */
    @media (max-width: 768px) {
        .table-wrap {
            overflow-x: visible;
            background: transparent;
            box-shadow: none;
            padding: 0;
        }

        .responsive-table {
            display: block;
            width: 100%;
            border: none;
        }

        /* Masque les en-têtes d'origine */
        .responsive-table thead {
            display: none;
        }

        .responsive-table tbody {
            display: block;
            width: 100%;
        }

        /* Transformation de la ligne (tr) en Carte */
        .responsive-table tr {
            display: block;
            width: 100%;
            background: #ffffff;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
            border: 1px solid #edf2f7;
            position: relative;
            box-sizing: border-box;
        }

        .responsive-table tr .empty-row-state {
            display: block;
            text-align: center;
            padding: 2rem 1rem;
            color: #aaa;
        }

        /* Transformation des cellules (td) en lignes Clé / Valeur */
        .responsive-table td {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-bottom: 1px dashed #e2e8f0;
            font-size: 13px;
            width: 100% !important;
            box-sizing: border-box;
        }

        .responsive-table td:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        /* Affichage du libellé à gauche (via data-label) */
        .responsive-table td::before {
            content: attr(data-label);
            font-weight: 700;
            color: #4a5568;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.5px;
            text-align: left;
            padding-right: 1rem;
            flex-shrink: 0;
        }

        /* Gestion des conteneurs de texte multi-lignes (ex: Nom + Description) */
        .responsive-table .table-text-container {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            text-align: right;
            width: 100%;
        }

        /* Alignements spécifiques des éléments enfants */
        .responsive-table .icon-preview-box {
            margin-left: auto;
        }
        
        .responsive-table .table-external-link {
            font-size: 12px;
            color: #534AB7;
            font-family: 'DM Mono', monospace;
            text-decoration: none;
            display: inline-flex !important;
            align-items: center;
            gap: 4px;
        }

        .responsive-table .order-text {
            font-family: 'DM Mono', monospace;
            color: #4a5568;
        }

        .responsive-table .actions-wrapper {
            display: flex;
            gap: 8px;
            justify-content: flex-end;
            width: auto;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    let pendingDeleteId = null;

    function confirmDelete(id, name) {
        pendingDeleteId = id;
        document.getElementById('deleteAppName').textContent = name;
        document.getElementById('deleteModal').classList.add('open');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.remove('open');
        pendingDeleteId = null;
    }

    document.getElementById('confirmDeleteBtn').addEventListener('click', function () {
        if (pendingDeleteId) {
            document.getElementById('delete-form-' + pendingDeleteId).submit();
        }
    });

    document.getElementById('deleteModal').addEventListener('click', function (e) {
        if (e.target === this) closeDeleteModal();
    });
</script>
@endpush

@endsection