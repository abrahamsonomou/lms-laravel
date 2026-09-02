@csrf
<div class="row">
    <div class="col-md-4 mb-3">
        <label for="code" class="form-label">Code <span class="text-danger">*</span></label>
        <input type="text" id="code" name="code" value="{{ old('code', $plan->code ?? '') }}"
               class="form-control @error('code') is-invalid @enderror" required>
        @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-8 mb-3">
        <label for="nom" class="form-label">Nom <span class="text-danger">*</span></label>
        <input type="text" id="nom" name="nom" value="{{ old('nom', $plan->nom ?? '') }}"
               class="form-control @error('nom') is-invalid @enderror" required>
        @error('nom')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12 mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea id="description" name="description" rows="3"
                  class="form-control @error('description') is-invalid @enderror">{{ old('description', $plan->description ?? '') }}</textarea>
        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4 mb-3">
        <label for="prix" class="form-label">Prix <span class="text-danger">*</span></label>
        <input type="number" step="0.01" min="0" id="prix" name="prix" value="{{ old('prix', $plan->prix ?? '') }}"
               class="form-control @error('prix') is-invalid @enderror" required>
        @error('prix')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4 mb-3">
        <label for="devise_id" class="form-label">Devise</label>
        <select id="devise_id" name="devise_id" class="form-select @error('devise_id') is-invalid @enderror">
            <option value="">—</option>
            @foreach ($devises as $d)
                <option value="{{ $d->id }}" @selected(old('devise_id', $plan->devise_id ?? '') == $d->id)>{{ $d->code }}</option>
            @endforeach
        </select>
        @error('devise_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4 mb-3">
        <label for="duree" class="form-label">Durée <span class="text-muted small">(jours, optionnel)</span></label>
        <input type="number" min="1" id="duree" name="duree" value="{{ old('duree', $plan->duree ?? '') }}"
               class="form-control @error('duree') is-invalid @enderror" placeholder="Selon le type si vide">
        @error('duree')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4 mb-3">
        <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
        <select id="type" name="type" class="form-select @error('type') is-invalid @enderror" required>
            @foreach ($types as $type)
                <option value="{{ $type }}" @selected(old('type', $plan->type ?? '') === $type)>{{ $type }}</option>
            @endforeach
        </select>
        @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label d-block">Statut</label>
        <div class="form-check form-switch mt-2">
            <input type="hidden" name="active" value="0">
            <input type="checkbox" id="active" name="active" value="1" class="form-check-input" @checked(old('active', $plan->active ?? true))>
            <label class="form-check-label" for="active">Plan actif</label>
        </div>
    </div>
</div>

<div class="d-flex gap-2">
    <button type="submit" class="btn btn-primary">Enregistrer</button>
    <a href="{{ $cancelUrl }}" class="btn btn-outline-secondary">Annuler</a>
</div>
