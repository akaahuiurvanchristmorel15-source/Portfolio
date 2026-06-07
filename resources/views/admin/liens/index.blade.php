@extends('admin.layout.app')

@section('title', 'Gestion des liens')
@section('page-title', 'Liens')

@section('content')

<div class="breadcrumb">
    <i class='bx bx-home-alt'></i>
    <span>Admin</span>
    <i class='bx bx-chevron-right'></i>
    <span style="color:#1a1a2e;font-weight:600">Liens</span>
</div>

<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem">
    <div>
        <h1 style="font-family:'Syne',sans-serif;font-size:22px;font-weight:800">
            Gestion des liens
        </h1>
        <p style="font-size:13px;color:#888;margin-top:3px">
            Modifiez rapidement l'URL de chaque application sans passer par l'édition complète.
        </p>
    </div>

    {{-- Recherche inline --}}
    <div style="position:relative;width:260px">
        <i class='bx bx-search'
           style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#aaa;font-size:18px"></i>
        <input type="text"
               id="linkSearch"
               placeholder="Filtrer les applications..."
               class="form-control"
               style="padding-left:38px">
    </div>
</div>

<div class="table-wrap">
    <table class="admin-table" id="linkTable">
        <thead>
            <tr>
                <th style="width:52px"></th>
                <th>Application</th>
                <th>Catégorie</th>
                <th>URL actuelle</th>
                <th>Statut</th>
                <th style="width:90px">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($applications as $app)
                <tr data-app-name="{{ strtolower($app->nom) }}">
                    <td>
                        <div class="icon-preview-box" style="width:38px;height:38px;font-size:16px">
                            @if($app->icone_url)
                                <img src="{{ $app->icone_url }}" alt="{{ $app->nom }}"
                                     style="width:26px;height:26px;object-fit:contain">
                            @else
                                {{ $app->initiale }}
                            @endif
                        </div>
                    </td>
                    <td>
                        <span style="font-weight:600;font-size:14px">{{ $app->nom }}</span>
                    </td>
                    <td>
                        <span class="badge badge-indigo">{{ $app->category->nom ?? '—' }}</span>
                    </td>
                    <td>
                        <div style="display:flex;align-items:center;gap:6px;max-width:320px">
                            <a href="{{ $app->lien }}" target="_blank"
                               style="font-size:12px;color:var(--indigo);font-family:'DM Mono',monospace;
                                      text-decoration:none;overflow:hidden;text-overflow:ellipsis;
                                      white-space:nowrap;flex:1"
                               title="{{ $app->lien }}">
                                {{ $app->lien }}
                            </a>
                            <a href="{{ $app->lien }}" target="_blank"
                               style="color:#aaa;flex-shrink:0" title="Ouvrir">
                                <i class='bx bx-link-external' style="font-size:15px"></i>
                            </a>
                        </div>
                    </td>
                    <td>
                        @if($app->actif)
                            <span class="badge badge-green">Actif</span>
                        @else
                            <span class="badge badge-red">Inactif</span>
                        @endif
                    </td>
                    <td>
                        <button type="button"
                                class="btn btn-warning btn-sm"
                                onclick="openLinkModal({{ $app->id }}, '{{ addslashes($app->nom) }}', '{{ addslashes($app->lien) }}')">
                            <i class='bx bx-edit'></i> Éditer
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align:center;padding:3rem;color:#aaa">
                        <i class='bx bx-link' style="font-size:40px;display:block;margin-bottom:.5rem"></i>
                        Aucune application trouvée.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Modal édition lien --}}
<div class="modal-overlay" id="linkModal">
    <div class="modal-box" style="max-width:460px">
        <div class="modal-header">
            <span class="modal-title">
                <i class='bx bx-edit' style="color:var(--indigo);vertical-align:middle;margin-right:4px"></i>
                Modifier le lien
            </span>
            <button class="modal-close" onclick="closeLinkModal()">&times;</button>
        </div>

        <div class="form-group" style="margin-bottom:1rem">
            <label class="form-label">Application</label>
            <input type="text" id="modalAppName" class="form-control" disabled
                   style="background:#f5f5f5;color:#555;font-weight:600">
        </div>

        <div class="form-group" style="margin-bottom:.75rem">
            <label class="form-label">Lien actuel</label>
            <div style="display:flex;align-items:center;gap:8px">
                <code id="currentLinkDisplay"
                      style="font-size:12px;font-family:'DM Mono',monospace;color:#888;
                             background:#f5f5f5;padding:6px 10px;border-radius:6px;flex:1;
                             overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
                </code>
                <a id="currentLinkOpen" href="#" target="_blank"
                   style="color:#aaa;flex-shrink:0" title="Ouvrir le lien actuel">
                    <i class='bx bx-link-external' style="font-size:16px"></i>
                </a>
            </div>
        </div>

        <form id="linkForm" method="POST">
            @csrf
            @method('PATCH')

            <div class="form-group">
                <label class="form-label" for="newLien">Nouveau lien <span style="color:#A32D2D">*</span></label>
                <div style="position:relative">
                    <i class='bx bx-link'
                       style="position:absolute;left:12px;top:50%;transform:translateY(-50%);
                              color:#aaa;font-size:18px"></i>
                    <input type="url" id="newLien" name="lien"
                           class="form-control"
                           placeholder="https://..."
                           style="padding-left:38px">
                </div>
                <span class="form-hint">Commencez par https://</span>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeLinkModal()">Annuler</button>
                <button type="submit" class="btn btn-primary">
                    <i class='bx bx-save'></i> Mettre à jour
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // ── FILTRE INLINE ──────────────────────────────────────────────
    document.getElementById('linkSearch').addEventListener('input', function () {
        const q = this.value.toLowerCase();
        document.querySelectorAll('#linkTable tbody tr[data-app-name]').forEach(row => {
            row.style.display = row.dataset.appName.includes(q) ? '' : 'none';
        });
    });

    // ── MODAL LIEN ─────────────────────────────────────────────────
    function openLinkModal(id, nom, lien) {
        document.getElementById('modalAppName').value   = nom;
        document.getElementById('currentLinkDisplay').textContent = lien;
        document.getElementById('currentLinkOpen').href = lien;
        document.getElementById('newLien').value        = lien;
        document.getElementById('linkForm').action      = `/admin/applications/${id}/lien`;
        document.getElementById('linkModal').classList.add('open');
        setTimeout(() => {
            const input = document.getElementById('newLien');
            input.focus();
            input.select();
        }, 150);
    }

    function closeLinkModal() {
        document.getElementById('linkModal').classList.remove('open');
    }

    document.getElementById('linkModal').addEventListener('click', function (e) {
        if (e.target === this) closeLinkModal();
    });
</script>
@endpush

@endsection