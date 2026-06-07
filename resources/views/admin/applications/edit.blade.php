@extends('admin.layout.app')

@section('title', 'Modifier — ' . $application->nom)
@section('page-title', 'Applications')

@section('content')

{{-- Breadcrumb --}}
<div class="breadcrumb">
    <i class='bx bx-home-alt'></i>
    <a href="{{ route('admin.applications.index') }}">Applications</a>
    <i class='bx bx-chevron-right'></i>
    <span style="color:#1a1a2e;font-weight:600">Modifier — {{ $application->nom }}</span>
</div>

<div style="max-width:760px">

    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem">
        <div style="display:flex;align-items:center;gap:12px">
            <div class="icon-preview-box" style="width:44px;height:44px">
                @if($application->icone_url)
                    <img src="{{ $application->icone_url }}" alt="{{ $application->nom }}"
                         style="width:30px;height:30px;object-fit:contain">
                @else
                    {{ $application->initiale }}
                @endif
            </div>
            <h1 style="font-family:'Syne',sans-serif;font-size:22px;font-weight:800">
                {{ $application->nom }}
            </h1>
        </div>
        <a href="{{ route('admin.applications.index') }}" class="btn btn-secondary btn-sm">
            <i class='bx bx-arrow-back'></i> Retour
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-error" style="margin-bottom:1.5rem">
            <i class='bx bx-error-circle'></i>
            <div>
                <strong>Erreurs de validation :</strong>
                <ul style="margin-top:6px;padding-left:1rem;font-size:13px">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    {{-- ── FORMULAIRE PRINCIPAL ── --}}
    <form method="POST"
          action="{{ route('admin.applications.update', $application) }}"
          enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="card" style="display:flex;flex-direction:column;gap:1.25rem;margin-bottom:1.25rem">

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
                <div class="form-group">
                    <label class="form-label" for="nom">Nom <span style="color:#A32D2D">*</span></label>
                    <input type="text" id="nom" name="nom"
                           class="form-control @error('nom') border-red-400 @enderror"
                           value="{{ old('nom', $application->nom) }}"
                           placeholder="ex : Figma">
                    @error('nom') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="categorie_id">Catégorie <span style="color:#A32D2D">*</span></label>
                    <select id="categorie_id" name="categorie_id"
                            class="form-control @error('categorie_id') border-red-400 @enderror">
                        <option value="">— Choisir —</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}"
                                {{ old('categorie_id', $application->categorie_id) == $cat->id ? 'selected' : '' }}>
                                {{ $cat->nom }}
                            </option>
                        @endforeach
                    </select>
                    @error('categorie_id') <span class="form-error">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="description">Description</label>
                <textarea id="description" name="description"
                          class="form-control @error('description') border-red-400 @enderror"
                          placeholder="Courte description...">{{ old('description', $application->description) }}</textarea>
                @error('description') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="lien">Lien <span style="color:#A32D2D">*</span></label>
                <div style="position:relative">
                    <i class='bx bx-link' style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#aaa;font-size:18px"></i>
                    <input type="url" id="lien" name="lien"
                           class="form-control @error('lien') border-red-400 @enderror"
                           value="{{ old('lien', $application->lien) }}"
                           placeholder="https://..."
                           style="padding-left:38px">
                </div>
                @error('lien') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            {{-- Icône --}}
            <div class="form-group">
                <label class="form-label">Icône</label>
                <div style="display:flex;align-items:center;gap:1.25rem">

                    {{-- Preview icône actuelle --}}
                    <div class="icon-preview-box" id="iconPreview">
                        @if($application->icone_url)
                            <img src="{{ $application->icone_url }}" id="currentIconImg"
                                 alt="{{ $application->nom }}"
                                 style="width:36px;height:36px;object-fit:contain;border-radius:4px">
                        @else
                            <span id="currentIconInitial" style="font-size:20px;font-weight:800;font-family:'Syne',sans-serif">
                                {{ $application->initiale }}
                            </span>
                        @endif
                    </div>

                    <div style="flex:1">
                        <label class="drop-zone" for="icone" style="display:block;cursor:pointer;padding:.8rem">
                            <i class='bx bx-upload' style="font-size:22px;display:block;margin-bottom:4px"></i>
                            <span style="font-size:13px">
                                {{ $application->icone ? 'Remplacer l\'icône' : 'Choisir une icône' }}
                            </span><br>
                            <span style="font-size:11px;opacity:.6">PNG, SVG, JPG, WEBP — max 512 Ko</span>
                        </label>
                        <input type="file" id="icone" name="icone"
                               accept=".png,.svg,.jpg,.jpeg,.webp"
                               style="display:none"
                               onchange="previewIcon(this)">
                    </div>

                    {{-- Option supprimer icône existante --}}
                    @if($application->icone)
                        <label style="display:flex;align-items:center;gap:6px;font-size:13px;color:#A32D2D;cursor:pointer;white-space:nowrap">
                            <input type="checkbox" name="supprimer_icone" value="1"
                                   style="accent-color:#A32D2D;width:15px;height:15px">
                            Supprimer l'icône
                        </label>
                    @endif
                </div>
                @error('icone') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
                <div class="form-group">
                    <label class="form-label" for="ordre">Ordre d'affichage</label>
                    <input type="number" id="ordre" name="ordre" min="0"
                           class="form-control"
                           value="{{ old('ordre', $application->ordre) }}">
                    <span class="form-hint">Plus petit = affiché en premier.</span>
                </div>

                <div class="form-group">
                    <label class="form-label">Statut</label>
                    <label style="display:flex;align-items:center;gap:10px;cursor:pointer;padding:10px 0">
                        <input type="hidden" name="actif" value="0">
                        <input type="checkbox" id="actif" name="actif" value="1"
                               {{ old('actif', $application->actif) ? 'checked' : '' }}
                               style="width:18px;height:18px;accent-color:var(--indigo);cursor:pointer">
                        <span style="font-size:14px;font-weight:500">Application active</span>
                    </label>
                </div>
            </div>

            <div style="display:flex;justify-content:flex-end;gap:.75rem;padding-top:.75rem;border-top:1px solid #f0f0f0">
                <a href="{{ route('admin.applications.index') }}" class="btn btn-secondary">Annuler</a>
                <button type="submit" class="btn btn-primary">
                    <i class='bx bx-save'></i> Mettre à jour
                </button>
            </div>
        </div>
    </form>

    {{-- ── ZONE DANGER : suppression ── --}}
    <div class="card" style="border-color:#FCEBEB;background:#fffafa">
        <div style="display:flex;align-items:center;justify-content:space-between;gap:1rem;flex-wrap:wrap">
            <div>
                <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:15px;color:#A32D2D;margin-bottom:4px">
                    Zone de danger
                </div>
                <p style="font-size:13px;color:#888">
                    La suppression est définitive et efface également l'icône associée.
                </p>
            </div>
            <button type="button" class="btn btn-danger"
                    onclick="document.getElementById('dangerModal').classList.add('open')">
                <i class='bx bx-trash'></i> Supprimer cette application
            </button>
        </div>
    </div>
</div>

{{-- Modal confirmation danger --}}
<div class="modal-overlay" id="dangerModal">
    <div class="modal-box" style="max-width:420px">
        <div class="modal-header">
            <span class="modal-title" style="color:#A32D2D">
                <i class='bx bx-error-circle'></i> Supprimer l'application ?
            </span>
            <button class="modal-close"
                    onclick="document.getElementById('dangerModal').classList.remove('open')">&times;</button>
        </div>
        <p style="font-size:14px;color:#555;line-height:1.6">
            Vous allez supprimer <strong style="color:#0f0e1a">{{ $application->nom }}</strong>.
            Cette action est <strong>irréversible</strong>.
        </p>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary"
                    onclick="document.getElementById('dangerModal').classList.remove('open')">
                Annuler
            </button>
            <form method="POST" action="{{ route('admin.applications.destroy', $application) }}" style="display:inline">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class='bx bx-trash'></i> Supprimer définitivement
                </button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function previewIcon(input) {
        if (!input.files || !input.files[0]) return;
        const reader = new FileReader();
        reader.onload = function (e) {
            document.getElementById('iconPreview').innerHTML =
                `<img src="${e.target.result}"
                      style="width:36px;height:36px;object-fit:contain;border-radius:4px">`;
        };
        reader.readAsDataURL(input.files[0]);
    }

    document.getElementById('dangerModal').addEventListener('click', function(e) {
        if (e.target === this) this.classList.remove('open');
    });
</script>
@endpush

@endsection