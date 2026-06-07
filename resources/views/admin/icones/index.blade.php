@extends('admin.layout.app')

@section('title', 'Icônes')
@section('page-title', 'Icônes')

@section('content')

<div class="breadcrumb">
    <i class='bx bx-home-alt'></i>
    <span>Admin</span>
    <i class='bx bx-chevron-right'></i>
    <span style="color:#1a1a2e;font-weight:600">Icônes</span>
</div>

{{-- En-tête --}}
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem">
    <div>
        <h1 style="font-family:'Syne',sans-serif;font-size:22px;font-weight:800">
            Bibliothèque d'icônes
        </h1>
        <p style="font-size:13px;color:#888;margin-top:3px">
            {{ $icones->total() }} icône(s) importée(s)
        </p>
    </div>
    <button type="button" class="btn btn-primary"
            onclick="document.getElementById('uploadInput').click()">
        <i class='bx bx-upload'></i> Importer des icônes
    </button>
</div>

{{-- Zone de drop --}}
<div class="card" style="margin-bottom:1.5rem">
    <div id="dropZone" class="drop-zone" style="margin-bottom:0">
        <i class='bx bx-cloud-upload' style="font-size:40px;display:block;margin-bottom:.5rem;color:var(--indigo-mid)"></i>
        <p style="font-size:14px;font-weight:600;color:#555">Glissez-déposez vos icônes ici</p>
        <p style="font-size:12px;color:#aaa;margin-top:4px">PNG, SVG, JPG, WEBP — max 512 Ko par fichier — jusqu'à 10 à la fois</p>
        <form id="uploadForm" method="POST"
              action="{{ route('admin.icones.store') }}"
              enctype="multipart/form-data" style="display:none">
            @csrf
            <input type="file" id="uploadInput" name="fichiers[]"
                   accept=".png,.svg,.jpg,.jpeg,.webp"
                   multiple>
        </form>
    </div>

    {{-- Barre de progression (masquée par défaut) --}}
    <div id="uploadProgress" style="display:none;margin-top:1rem">
        <div style="display:flex;align-items:center;justify-content:space-between;font-size:12px;
                    color:#555;margin-bottom:6px">
            <span id="progressLabel">Envoi en cours...</span>
            <span id="progressPct">0%</span>
        </div>
        <div style="height:6px;background:#f0f0f0;border-radius:100px;overflow:hidden">
            <div id="progressBar"
                 style="height:100%;background:var(--indigo);border-radius:100px;
                        width:0%;transition:width .3s"></div>
        </div>
    </div>
</div>

{{-- Grille icônes --}}
@if($icones->count())
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(130px,1fr));gap:1rem">
        @foreach($icones as $icone)
            <div class="icon-card card"
                 style="padding:1rem;text-align:center;display:flex;flex-direction:column;
                        align-items:center;gap:.6rem;transition:transform .2s,box-shadow .2s;cursor:default"
                 onmouseenter="this.style.transform='translateY(-3px)';this.style.boxShadow='0 6px 20px rgba(0,0,0,.1)'"
                 onmouseleave="this.style.transform='';this.style.boxShadow=''">

                <div style="width:56px;height:56px;background:var(--indigo-light);border-radius:12px;
                            display:flex;align-items:center;justify-content:center;overflow:hidden">
                    <img src="{{ $icone->url }}" alt="{{ $icone->nom }}"
                         style="width:40px;height:40px;object-fit:contain">
                </div>

                {{-- Nom éditable --}}
                <div style="width:100%">
                    <span id="name-{{ $icone->id }}"
                          style="font-size:12px;font-weight:600;color:#333;
                                 display:block;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;
                                 width:100%;text-align:center"
                          title="{{ $icone->nom }}">
                        {{ $icone->nom }}
                    </span>
                    <span style="font-size:10px;color:#bbb;font-family:'DM Mono',monospace">
                        .{{ $icone->extension }} · {{ $icone->taille_humaine }}
                    </span>
                </div>

                {{-- Actions --}}
                <div style="display:flex;gap:5px">
                    <button type="button"
                            class="btn btn-warning btn-sm btn-icon"
                            title="Renommer"
                            onclick="openRenameModal({{ $icone->id }}, '{{ addslashes($icone->nom) }}')">
                        <i class='bx bx-edit' style="font-size:14px"></i>
                    </button>
                    <button type="button"
                            class="btn btn-danger btn-sm btn-icon"
                            title="Supprimer"
                            onclick="confirmDeleteIcon({{ $icone->id }}, '{{ addslashes($icone->nom) }}')">
                        <i class='bx bx-trash' style="font-size:14px"></i>
                    </button>
                    <form id="del-icon-{{ $icone->id }}"
                          method="POST"
                          action="{{ route('admin.icones.destroy', $icone) }}"
                          style="display:none">
                        @csrf @method('DELETE')
                    </form>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    @if($icones->hasPages())
        <div style="margin-top:1.5rem;display:flex;justify-content:flex-end">
            {{ $icones->links() }}
        </div>
    @endif

@else
    <div class="card" style="text-align:center;padding:3rem;color:#aaa">
        <i class='bx bx-image-add' style="font-size:50px;display:block;margin-bottom:.75rem;color:#ddd"></i>
        <p style="font-size:15px;font-weight:600;color:#555">Aucune icône importée.</p>
        <p style="font-size:13px;margin-top:4px">Utilisez la zone ci-dessus pour commencer.</p>
    </div>
@endif

{{-- Modal renommer --}}
<div class="modal-overlay" id="renameModal">
    <div class="modal-box" style="max-width:420px">
        <div class="modal-header">
            <span class="modal-title">Renommer l'icône</span>
            <button class="modal-close" onclick="closeRenameModal()">&times;</button>
        </div>
        <div class="form-group">
            <label class="form-label">Nouveau nom</label>
            <input type="text" id="renameInput" class="form-control" placeholder="Nom de l'icône">
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeRenameModal()">Annuler</button>
            <button class="btn btn-primary" onclick="submitRename()">
                <i class='bx bx-save'></i> Renommer
            </button>
        </div>
    </div>
</div>

{{-- Modal suppression icône --}}
<div class="modal-overlay" id="deleteIconModal">
    <div class="modal-box" style="max-width:400px">
        <div class="modal-header">
            <span class="modal-title" style="color:#A32D2D">Supprimer l'icône</span>
            <button class="modal-close" onclick="closeDeleteIconModal()">&times;</button>
        </div>
        <p style="font-size:14px;color:#555;line-height:1.6">
            Vous allez supprimer l'icône <strong id="deleteIconName" style="color:#0f0e1a"></strong>.
            Le fichier sera définitivement effacé du serveur.
        </p>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeDeleteIconModal()">Annuler</button>
            <button class="btn btn-danger" id="confirmDeleteIconBtn">
                <i class='bx bx-trash'></i> Supprimer
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // ── DRAG & DROP ──────────────────────────────────────────────────
    const dropZone   = document.getElementById('dropZone');
    const uploadForm = document.getElementById('uploadForm');
    const uploadInput= document.getElementById('uploadInput');

    dropZone.addEventListener('click', () => uploadInput.click());

    ['dragenter','dragover'].forEach(evt =>
        dropZone.addEventListener(evt, e => {
            e.preventDefault();
            dropZone.classList.add('drag-over');
        })
    );

    ['dragleave','drop'].forEach(evt =>
        dropZone.addEventListener(evt, e => {
            e.preventDefault();
            dropZone.classList.remove('drag-over');
        })
    );

    dropZone.addEventListener('drop', e => {
        const dt = e.dataTransfer;
        if (dt.files.length) {
            // Crée un nouveau DataTransfer pour assigner les fichiers à l'input
            const transfer = new DataTransfer();
            Array.from(dt.files).forEach(f => transfer.items.add(f));
            uploadInput.files = transfer.files;
            submitUploadForm();
        }
    });

    uploadInput.addEventListener('change', () => {
        if (uploadInput.files.length) submitUploadForm();
    });

    function submitUploadForm() {
        const progress = document.getElementById('uploadProgress');
        const bar      = document.getElementById('progressBar');
        const pct      = document.getElementById('progressPct');
        progress.style.display = 'block';

        const xhr  = new XMLHttpRequest();
        const data = new FormData(uploadForm);

        xhr.upload.onprogress = e => {
            if (e.lengthComputable) {
                const p = Math.round((e.loaded / e.total) * 100);
                bar.style.width = p + '%';
                pct.textContent = p + '%';
            }
        };

        xhr.onload = () => {
            if (xhr.status === 200 || xhr.status === 302) {
                window.location.reload();
            }
        };

        xhr.open('POST', uploadForm.action);
        xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
        xhr.send(data);
    }

    // ── RENOMMER ──────────────────────────────────────────────────────
    let renameIconId = null;

    function openRenameModal(id, name) {
        renameIconId = id;
        document.getElementById('renameInput').value = name;
        document.getElementById('renameModal').classList.add('open');
        setTimeout(() => document.getElementById('renameInput').focus(), 100);
    }

    function closeRenameModal() {
        document.getElementById('renameModal').classList.remove('open');
        renameIconId = null;
    }

    function submitRename() {
        if (!renameIconId) return;
        const nom = document.getElementById('renameInput').value.trim();
        if (!nom) return;

        fetch(`/admin/icones/${renameIconId}/rename`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ nom }),
        })
        .then(r => r.json())
        .then(() => {
            const el = document.getElementById('name-' + renameIconId);
            if (el) { el.textContent = nom; el.title = nom; }
            closeRenameModal();
        })
        .catch(() => alert('Erreur lors du renommage.'));
    }

    // ── SUPPRIMER ────────────────────────────────────────────────────
    let pendingDeleteIconId = null;

    function confirmDeleteIcon(id, name) {
        pendingDeleteIconId = id;
        document.getElementById('deleteIconName').textContent = name;
        document.getElementById('deleteIconModal').classList.add('open');
    }

    function closeDeleteIconModal() {
        document.getElementById('deleteIconModal').classList.remove('open');
        pendingDeleteIconId = null;
    }

    document.getElementById('confirmDeleteIconBtn').addEventListener('click', function () {
        if (pendingDeleteIconId) {
            document.getElementById('del-icon-' + pendingDeleteIconId).submit();
        }
    });

    // Fermer modals en cliquant en dehors
    ['renameModal', 'deleteIconModal'].forEach(id => {
        document.getElementById(id).addEventListener('click', function(e) {
            if (e.target === this) this.classList.remove('open');
        });
    });
</script>
@endpush

@endsection