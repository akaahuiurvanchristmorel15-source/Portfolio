@extends('admin.layout.app')

@section('title', 'Modifier — ' . $categorie->nom)
@section('page-title', 'Catégories')

@section('content')

<div class="breadcrumb">
    <i class='bx bx-home-alt'></i>
    <a href="{{ route('admin.categories.index') }}">Catégories</a>
    <i class='bx bx-chevron-right'></i>
    <span style="color:#1a1a2e;font-weight:600">Modifier — {{ $categorie->nom }}</span>
</div>

<div style="max-width:600px">

    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem">
        <h1 style="font-family:'Syne',sans-serif;font-size:22px;font-weight:800">
            {{ $categorie->nom }}
        </h1>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary btn-sm">
            <i class='bx bx-arrow-back'></i> Retour
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-error" style="margin-bottom:1.5rem">
            <i class='bx bx-error-circle'></i>
            <div>
                <strong>Erreurs :</strong>
                <ul style="margin-top:6px;padding-left:1rem;font-size:13px">
                    @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
                </ul>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.categories.update', $categorie) }}">
        @csrf @method('PUT')

        <div class="card" style="display:flex;flex-direction:column;gap:1.25rem;margin-bottom:1.25rem">

            <div class="form-group">
                <label class="form-label" for="nom">Nom <span style="color:#A32D2D">*</span></label>
                <input type="text" id="nom" name="nom"
                       class="form-control @error('nom') border-red-400 @enderror"
                       value="{{ old('nom', $categorie->nom) }}"
                       placeholder="ex : Design">
                @error('nom') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Slug actuel</label>
                <code style="font-family:'DM Mono',monospace;font-size:13px;
                             background:#f5f5f5;padding:8px 12px;border-radius:8px;
                             color:#534AB7;display:block">
                    {{ $categorie->slug }}
                </code>
                <span class="form-hint">Le slug se régénère automatiquement si le nom change.</span>
            </div>

            <div class="form-group">
                <label class="form-label" for="description">Description</label>
                <textarea id="description" name="description"
                          class="form-control" style="min-height:80px"
                          placeholder="Description courte (optionnel)...">{{ old('description', $categorie->description) }}</textarea>
            </div>

            <div class="form-group">
                <label class="form-label" for="ordre">Ordre</label>
                <input type="number" id="ordre" name="ordre" min="0"
                       class="form-control"
                       value="{{ old('ordre', $categorie->ordre) }}">
            </div>

            {{-- Info applications rattachées --}}
            @if($categorie->applications_count > 0)
                <div style="display:flex;align-items:center;gap:8px;padding:10px 14px;
                            background:var(--indigo-light);border-radius:8px;font-size:13px;color:var(--indigo)">
                    <i class='bx bx-info-circle' style="font-size:18px"></i>
                    {{ $categorie->applications_count }} application(s) rattachée(s) à cette catégorie.
                </div>
            @endif

            <div style="display:flex;justify-content:flex-end;gap:.75rem;
                        padding-top:.75rem;border-top:1px solid #f0f0f0">
                <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Annuler</a>
                <button type="submit" class="btn btn-primary">
                    <i class='bx bx-save'></i> Mettre à jour
                </button>
            </div>
        </div>
    </form>

    {{-- Zone danger --}}
    @if($categorie->applications_count === 0)
        <div class="card" style="border-color:#FCEBEB;background:#fffafa">
            <div style="display:flex;align-items:center;justify-content:space-between;gap:1rem;flex-wrap:wrap">
                <div>
                    <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:15px;
                                color:#A32D2D;margin-bottom:4px">Zone de danger</div>
                    <p style="font-size:13px;color:#888">Suppression définitive et irréversible.</p>
                </div>
                <button type="button" class="btn btn-danger"
                        onclick="document.getElementById('dangerCatModal').classList.add('open')">
                    <i class='bx bx-trash'></i> Supprimer
                </button>
            </div>
        </div>
    @endif
</div>

<div class="modal-overlay" id="dangerCatModal">
    <div class="modal-box" style="max-width:420px">
        <div class="modal-header">
            <span class="modal-title" style="color:#A32D2D">Supprimer la catégorie ?</span>
            <button class="modal-close"
                    onclick="document.getElementById('dangerCatModal').classList.remove('open')">&times;</button>
        </div>
        <p style="font-size:14px;color:#555;line-height:1.6">
            Vous allez supprimer <strong style="color:#0f0e1a">{{ $categorie->nom }}</strong>. Action irréversible.
        </p>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary"
                    onclick="document.getElementById('dangerCatModal').classList.remove('open')">Annuler</button>
            <form method="POST" action="{{ route('admin.categories.destroy', $categorie) }}" style="display:inline">
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
    document.getElementById('dangerCatModal').addEventListener('click', function(e) {
        if (e.target === this) this.classList.remove('open');
    });
</script>
@endpush

@endsection