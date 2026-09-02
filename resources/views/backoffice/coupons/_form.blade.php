@csrf
<div class="row">
    <div class="col-md-4 mb-3">
        <label for="code" class="form-label">Code <span class="text-danger">*</span></label>
        <input type="text" id="code" name="code" value="{{ old('code', $coupon->code ?? '') }}"
               class="form-control @error('code') is-invalid @enderror" required>
        @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-8 mb-3">
        <label for="nom" class="form-label">Nom / description</label>
        <input type="text" id="nom" name="nom" value="{{ old('nom', $coupon->nom ?? '') }}"
               class="form-control @error('nom') is-invalid @enderror">
        @error('nom')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4 mb-3">
        <label for="type_remise" class="form-label">Type de remise <span class="text-danger">*</span></label>
        <select id="type_remise" name="type_remise" class="form-select @error('type_remise') is-invalid @enderror">
            @foreach (['POURCENTAGE' => 'Pourcentage (%)', 'MONTANT' => 'Montant fixe'] as $value => $label)
                <option value="{{ $value }}" @selected(old('type_remise', $coupon->type_remise ?? 'POURCENTAGE') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @error('type_remise')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4 mb-3">
        <label for="valeur" class="form-label">Valeur <span class="text-danger">*</span></label>
        <input type="number" step="0.01" min="0" id="valeur" name="valeur" value="{{ old('valeur', $coupon->valeur ?? '') }}"
               class="form-control @error('valeur') is-invalid @enderror" required>
        @error('valeur')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4 mb-3">
        <label for="montant_minimum" class="form-label">Montant minimum</label>
        <input type="number" step="0.01" min="0" id="montant_minimum" name="montant_minimum" value="{{ old('montant_minimum', $coupon->montant_minimum ?? '') }}"
               class="form-control @error('montant_minimum') is-invalid @enderror">
        @error('montant_minimum')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4 mb-3">
        <label for="date_debut" class="form-label">Date de début</label>
        <input type="date" id="date_debut" name="date_debut" value="{{ old('date_debut', $coupon->date_debut?->format('Y-m-d') ?? '') }}"
               class="form-control @error('date_debut') is-invalid @enderror">
        @error('date_debut')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4 mb-3">
        <label for="date_fin" class="form-label">Date de fin</label>
        <input type="date" id="date_fin" name="date_fin" value="{{ old('date_fin', $coupon->date_fin?->format('Y-m-d') ?? '') }}"
               class="form-control @error('date_fin') is-invalid @enderror">
        @error('date_fin')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4 mb-3">
        <label for="nombre_utilisations" class="form-label">Utilisations max</label>
        <input type="number" min="1" id="nombre_utilisations" name="nombre_utilisations" value="{{ old('nombre_utilisations', $coupon->nombre_utilisations ?? '') }}"
               class="form-control @error('nombre_utilisations') is-invalid @enderror" placeholder="Illimité si vide">
        @error('nombre_utilisations')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label d-block">Statut</label>
        <div class="form-check form-switch mt-2">
            <input type="hidden" name="active" value="0">
            <input type="checkbox" id="active" name="active" value="1" class="form-check-input" @checked(old('active', $coupon->active ?? true))>
            <label class="form-check-label" for="active">Coupon actif</label>
        </div>
    </div>
    <div class="col-12 mb-3">
        <label class="form-label">Formations concernées <span class="text-muted small">(aucune sélection = toutes)</span></label>
        <div class="row">
            @foreach ($formations as $formation)
                <div class="col-md-4 col-6">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="formation_{{ $formation->id }}" name="formations[]" value="{{ $formation->id }}"
                               @checked(in_array($formation->id, old('formations', isset($coupon) ? $coupon->formations->pluck('id')->all() : [])))>
                        <label class="form-check-label" for="formation_{{ $formation->id }}">{{ $formation->titre }}</label>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<div class="d-flex gap-2">
    <button type="submit" class="btn btn-primary">Enregistrer</button>
    <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline-secondary">Annuler</a>
</div>
