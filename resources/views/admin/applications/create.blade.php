@extends('admin.layout.app')

@section('title', 'Ajouter une application')
@section('page-title', 'Applications')

@section('content')

{{-- Breadcrumb --}}
<div class="breadcrumb">
    <i class='bx bx-home-alt'></i>
    <a href="{{ route('admin.applications.index') }}">Applications</a>
    <i class='bx bx-chevron-right'></i>
    <span style="color:#1a1a2e;font-weight:600">Ajouter</span>
</div>

<div style="max-width:760px">

    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem">
        <h1 style="font-family:'Syne',sans-serif;font-size:22px;font-weight:800">
            Nouvelle application
        </h1>
        <a href="{{ route('admin.applications.index') }}" class="btn btn-secondary btn-sm">
            <i class='bx bx-arrow-back'></i> Retour
        </a>
    </div>

    {{-- Erreurs de validation --}}
    @if($errors->any())
        <div class="alert alert-error" style="margin-bottom:1.5rem">
            <i class='bx bx-error-circle'></i>
            <div>
                <strong>Veuillez corriger les erreurs suivantes :</strong>
                <ul style="margin-top:6px;padding-left:1rem;font-size:13px">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <form method="POST"
          action="{{ route('admin.applications.store') }}"
          enctype="multipart/form-data">
        @csrf

        <div class="card" style="display:flex;flex-direction:column;gap:1.25rem">

            {{-- Nom + Catégorie --}}
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
                <div class="form-group">
                    <label class="form-label" for="nom">Nom <span style="color:#A32D2D">*</span></label>
                    <input type="text" id="nom" name="nom"
                           class="form-control @error('nom') border-red-400 @enderror"
                           value="{{ old('nom') }}"
                           placeholder="ex : Figma" autofocus>
                    @error('nom')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="categorie_id">Catégorie <span style="color:#A32D2D">*</span></label>
                    <select id="categorie_id" name="categorie_id"
                            class="form-control @error('categorie_id') border-red-400 @enderror">
                        <option value="">— Choisir —</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('categorie_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->nom }}
                            </option>
                        @endforeach
                    </select>
                    @error('categorie_id')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            {{-- Description --}}
            <div class="form-group">
                <label class="form-label" for="description">Description</label>
                <textarea id="description" name="description"
                          class="form-control @error('description') border-red-400 @enderror"
                          placeholder="Courte description de l'application...">{{ old('description') }}</textarea>
                @error('description')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            {{-- Lien --}}
            <div class="form-group">
                <label class="form-label" for="lien">Lien <span style="color:#A32D2D">*</span></label>
                <div style="position:relative">
                    <i class='bx bx-link' style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#aaa;font-size:18px"></i>
                    <input type="url" id="lien" name="lien"
                           class="form-control @error('lien') border-red-400 @enderror"
                           value="{{ old('lien') }}"
                           placeholder="https://..."
                           style="padding-left:38px">
                </div>
                @error('lien')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            {{-- Icône --}}
            <div class="form-group">
                <label class="form-label">Icône</label>
                <div style="display:flex;align-items:center;gap:1.25rem">
                    <div class="icon-preview-box" id="iconPreview">
                        <i class='bx bx-image-add' style="font-size:24px;opacity:.4"></i>
                    </div>
                    <div style="flex:1">
                        <label class="drop-zone" for="icone" style="display:block;cursor:pointer;padding:1rem">
                            <i class='bx bx-upload' style="font-size:26px;display:block;margin-bottom:6px"></i>
                            <span style="font-size:13px">Cliquez pour choisir une icône</span><br>
                            <span style="font-size:11px;opacity:.6">PNG, SVG, JPG, WEBP — max 512 Ko</span>
                        </label>
                        <input type="file" id="icone" name="icone"
                               accept=".png,.svg,.jpg,.jpeg,.webp"
                               style="display:none"
                               onchange="previewIcon(this)">
                    </div>
                </div>
                @error('icone')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            {{-- Ordre + Statut --}}
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
                <div class="form-group">
                    <label class="form-label" for="ordre">Ordre d'affichage</label>
                    <input type="number" id="ordre" name="ordre" min="0"
                           class="form-control"
                           value="{{ old('ordre', 0) }}">
                    <span class="form-hint">Plus petit = affiché en premier.</span>
                </div>

                <div class="form-group" style="justify-content:flex-end">
                    <label class="form-label">Statut</label>
                    <label style="display:flex;align-items:center;gap:10px;cursor:pointer;padding:10px 0">
                        <div class="toggle-wrap">
                            <input type="hidden" name="actif" value="0">
                            <input type="checkbox" id="actif" name="actif" value="1"
                                   {{ old('actif', true) ? 'checked' : '' }}
                                   style="width:18px;height:18px;accent-color:var(--indigo);cursor:pointer">
                        </div>
                        <span style="font-size:14px;font-weight:500">Application active (visible sur le site)</span>
                    </label>
                </div>
            </div>

            {{-- Footer --}}
            <div style="display:flex;justify-content:flex-end;gap:.75rem;padding-top:.75rem;border-top:1px solid #f0f0f0">
                <a href="{{ route('admin.applications.index') }}" class="btn btn-secondary">
                    Annuler
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class='bx bx-save'></i> Enregistrer
                </button>
            </div>

        </div>
    </form>
</div>

@push('scripts')
<script>
    function previewIcon(input) {
        if (!input.files || !input.files[0]) return;
        const reader = new FileReader();
        reader.onload = function (e) {
            const box = document.getElementById('iconPreview');
            box.innerHTML = `<img src="${e.target.result}"
                style="width:36px;height:36px;object-fit:contain;border-radius:4px">`;
        };
        reader.readAsDataURL(input.files[0]);
    }
</script>
@endpush

@endsection