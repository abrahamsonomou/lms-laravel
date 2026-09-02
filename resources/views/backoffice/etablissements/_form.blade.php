@csrf
<div class="row">
    <div class="col-12 mb-3">
        <label for="organisation_id" class="form-label">Organisation <span class="text-danger">*</span></label>
        <select id="organisation_id" name="organisation_id" class="form-select @error('organisation_id') is-invalid @enderror" required>
            <option value="">— Sélectionner —</option>
            @foreach ($organisations as $o)
                <option value="{{ $o->id }}" @selected(old('organisation_id', $etablissement->organisation_id ?? '') == $o->id)>{{ $o->nom }}</option>
            @endforeach
        </select>
        @error('organisation_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label for="code" class="form-label">Code <span class="text-danger">*</span></label>
        <input type="text" id="code" name="code" value="{{ old('code', $etablissement->code ?? '') }}"
               class="form-control @error('code') is-invalid @enderror" required>
        @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label for="nom" class="form-label">Nom <span class="text-danger">*</span></label>
        <input type="text" id="nom" name="nom" value="{{ old('nom', $etablissement->nom ?? '') }}"
               class="form-control @error('nom') is-invalid @enderror" required>
        @error('nom')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" id="email" name="email" value="{{ old('email', $etablissement->email ?? '') }}"
               class="form-control @error('email') is-invalid @enderror">
        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label for="telephone" class="form-label">Téléphone</label>
        <input type="text" id="telephone" name="telephone" value="{{ old('telephone', $etablissement->telephone ?? '') }}"
               class="form-control @error('telephone') is-invalid @enderror">
        @error('telephone')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12 mb-3">
        <label for="adresse" class="form-label">Adresse</label>
        <textarea id="adresse" name="adresse" rows="2"
                  class="form-control @error('adresse') is-invalid @enderror">{{ old('adresse', $etablissement->adresse ?? '') }}</textarea>
        @error('adresse')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label for="pays_id" class="form-label">Pays</label>
        <select id="pays_id" name="pays_id" class="form-select @error('pays_id') is-invalid @enderror">
            <option value="">— Aucun(e) —</option>
            @foreach ($pays as $p)
                <option value="{{ $p->id }}" @selected(old('pays_id', $etablissement->pays_id ?? '') == $p->id)>{{ $p->nom }}</option>
            @endforeach
        </select>
        @error('pays_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label for="ville_id" class="form-label">Ville</label>
        <select id="ville_id" name="ville_id" class="form-select @error('ville_id') is-invalid @enderror">
            <option value="">— Aucun(e) —</option>
            @foreach ($villes as $v)
                <option value="{{ $v->id }}" @selected(old('ville_id', $etablissement->ville_id ?? '') == $v->id)>{{ $v->nom }}</option>
            @endforeach
        </select>
        @error('ville_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label d-block">Statut</label>
        <div class="form-check form-switch mt-2">
            <input type="hidden" name="active" value="0">
            <input type="checkbox" id="active" name="active" value="1" class="form-check-input"
                   @checked(old('active', $etablissement->active ?? true))>
            <label class="form-check-label" for="active">Établissement actif</label>
        </div>
    </div>
</div>

<div class="d-flex gap-2">
    <button type="submit" class="btn btn-primary">Enregistrer</button>
    <a href="{{ route('admin.etablissements.index') }}" class="btn btn-outline-secondary">Annuler</a>
</div>
