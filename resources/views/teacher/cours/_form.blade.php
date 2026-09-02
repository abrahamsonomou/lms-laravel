@csrf
<div class="row">
    <div class="col-12 mb-3">
        <label for="titre" class="form-label">Titre <span class="text-danger">*</span></label>
        <input type="text" id="titre" name="titre" value="{{ old('titre', $cours->titre ?? '') }}"
               class="form-control @error('titre') is-invalid @enderror" required>
        @error('titre')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12 mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea id="description" name="description" rows="4"
                  class="form-control @error('description') is-invalid @enderror">{{ old('description', $cours->description ?? '') }}</textarea>
        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4 mb-3">
        <label for="duree" class="form-label">Durée (heures)</label>
        <input type="number" id="duree" name="duree" min="0" value="{{ old('duree', $cours->duree ?? '') }}"
               class="form-control @error('duree') is-invalid @enderror">
        @error('duree')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4 mb-3">
        <label for="ordre" class="form-label">Ordre</label>
        <input type="number" id="ordre" name="ordre" min="0" value="{{ old('ordre', $cours->ordre ?? '') }}"
               class="form-control @error('ordre') is-invalid @enderror">
        @error('ordre')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4 mb-3">
        <label for="statut" class="form-label">Statut <span class="text-danger">*</span></label>
        <select id="statut" name="statut" class="form-select @error('statut') is-invalid @enderror" required>
            <option value="BROUILLON" @selected(old('statut', $cours->statut ?? 'BROUILLON') == 'BROUILLON')>BROUILLON</option>
            <option value="PUBLIE" @selected(old('statut', $cours->statut ?? 'BROUILLON') == 'PUBLIE')>PUBLIE</option>
        </select>
        @error('statut')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

<div class="d-flex gap-2">
    <button type="submit" class="btn btn-primary">Enregistrer</button>
    <a href="{{ $cancelUrl }}" class="btn btn-outline-secondary">Annuler</a>
</div>
