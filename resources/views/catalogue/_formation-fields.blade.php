<div class="row">
    <div class="col-md-6 mb-3">
        <label for="titre" class="form-label">Titre <span class="text-danger">*</span></label>
        <input type="text" id="titre" name="titre" value="{{ old('titre', $formation->titre ?? '') }}"
               class="form-control @error('titre') is-invalid @enderror" required>
        @error('titre')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label for="categorie_id" class="form-label">Catégorie</label>
        <select id="categorie_id" name="categorie_id" class="form-select @error('categorie_id') is-invalid @enderror">
            <option value="">— Aucune —</option>
            @foreach ($categories as $c)
                <option value="{{ $c->id }}" @selected(old('categorie_id', $formation->categorie_id ?? '') == $c->id)>{{ $c->nom }}</option>
            @endforeach
        </select>
        @error('categorie_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12 mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea id="description" name="description" rows="3"
                  class="form-control @error('description') is-invalid @enderror">{{ old('description', $formation->description ?? '') }}</textarea>
        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12 mb-3">
        <label for="objectifs" class="form-label">Objectifs</label>
        <textarea id="objectifs" name="objectifs" rows="3"
                  class="form-control @error('objectifs') is-invalid @enderror">{{ old('objectifs', $formation->objectifs ?? '') }}</textarea>
        @error('objectifs')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label for="niveau" class="form-label">Niveau</label>
        <select id="niveau" name="niveau" class="form-select @error('niveau') is-invalid @enderror">
            <option value="">— Aucun —</option>
            @foreach ($niveaux as $n)
                <option value="{{ $n }}" @selected(old('niveau', $formation->niveau ?? '') == $n)>{{ $n }}</option>
            @endforeach
        </select>
        @error('niveau')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label for="duree" class="form-label">Durée (heures)</label>
        <input type="number" id="duree" name="duree" value="{{ old('duree', $formation->duree ?? '') }}"
               class="form-control @error('duree') is-invalid @enderror" min="0">
        @error('duree')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label for="prix" class="form-label">Prix</label>
        <input type="number" id="prix" name="prix" value="{{ old('prix', $formation->prix ?? '') }}"
               class="form-control @error('prix') is-invalid @enderror" step="0.01" min="0">
        @error('prix')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label for="devise_id" class="form-label">Devise</label>
        <select id="devise_id" name="devise_id" class="form-select @error('devise_id') is-invalid @enderror">
            <option value="">— Aucune —</option>
            @foreach ($devises as $d)
                <option value="{{ $d->id }}" @selected(old('devise_id', $formation->devise_id ?? '') == $d->id)>{{ $d->code }}</option>
            @endforeach
        </select>
        @error('devise_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
        <select id="type" name="type" class="form-select @error('type') is-invalid @enderror" required>
            @foreach ($types as $t)
                <option value="{{ $t }}" @selected(old('type', $formation->type ?? 'PAYANTE') == $t)>{{ $t }}</option>
            @endforeach
        </select>
        @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label for="statut" class="form-label">Statut <span class="text-danger">*</span></label>
        <select id="statut" name="statut" class="form-select @error('statut') is-invalid @enderror" required>
            @foreach ($statuts as $s)
                <option value="{{ $s }}" @selected(old('statut', $formation->statut ?? 'BROUILLON') == $s)>{{ $s }}</option>
            @endforeach
        </select>
        @error('statut')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label for="date_publication" class="form-label">Date de publication</label>
        <input type="date" id="date_publication" name="date_publication"
               value="{{ old('date_publication', isset($formation) && $formation->date_publication ? $formation->date_publication->format('Y-m-d') : '') }}"
               class="form-control @error('date_publication') is-invalid @enderror">
        @error('date_publication')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label for="date_expiration" class="form-label">Date d'expiration</label>
        <input type="date" id="date_expiration" name="date_expiration"
               value="{{ old('date_expiration', isset($formation) && $formation->date_expiration ? $formation->date_expiration->format('Y-m-d') : '') }}"
               class="form-control @error('date_expiration') is-invalid @enderror">
        @error('date_expiration')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12 mb-3">
        <label class="form-label d-block">Image</label>
        <div class="d-flex align-items-center gap-3">
            @if (isset($formation) && $formation->image)
                <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($formation->image) }}"
                     alt="{{ $formation->titre }}" class="rounded" width="64" height="64" style="object-fit: cover;">
            @endif
            <input type="file" name="image" accept="image/*" class="form-control @error('image') is-invalid @enderror">
            @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
</div>
