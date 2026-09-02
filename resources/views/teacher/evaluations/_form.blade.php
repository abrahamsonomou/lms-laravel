@csrf
<div class="row">
    <div class="col-12 mb-3">
        <label for="titre" class="form-label">Titre <span class="text-danger">*</span></label>
        <input type="text" id="titre" name="titre" value="{{ old('titre', $evaluation->titre ?? '') }}"
               class="form-control @error('titre') is-invalid @enderror" required>
        @error('titre')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
        <select id="type" name="type" class="form-select @error('type') is-invalid @enderror" required>
            @foreach ($types as $type)
                <option value="{{ $type }}" @selected(old('type', $evaluation->type ?? '') == $type)>{{ $type }}</option>
            @endforeach
        </select>
        @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label for="duree" class="form-label">Durée (minutes)</label>
        <input type="number" id="duree" name="duree" min="0" value="{{ old('duree', $evaluation->duree ?? '') }}"
               class="form-control @error('duree') is-invalid @enderror">
        @error('duree')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4 mb-3">
        <label for="note_max" class="form-label">Note max</label>
        <input type="number" id="note_max" name="note_max" step="0.01" value="{{ old('note_max', $evaluation->note_max ?? '') }}"
               class="form-control @error('note_max') is-invalid @enderror">
        @error('note_max')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4 mb-3">
        <label for="note_min" class="form-label">Note min (réussite)</label>
        <input type="number" id="note_min" name="note_min" step="0.01" value="{{ old('note_min', $evaluation->note_min ?? '') }}"
               class="form-control @error('note_min') is-invalid @enderror">
        @error('note_min')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4 mb-3">
        <label for="tentatives_max" class="form-label">Tentatives max</label>
        <input type="number" id="tentatives_max" name="tentatives_max" min="1" value="{{ old('tentatives_max', $evaluation->tentatives_max ?? '') }}"
               class="form-control @error('tentatives_max') is-invalid @enderror">
        @error('tentatives_max')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12 mb-3">
        <div class="form-check form-switch">
            <input type="hidden" name="active" value="0">
            <input type="checkbox" id="active" name="active" value="1" class="form-check-input"
                   @checked(old('active', $evaluation->active ?? true))>
            <label for="active" class="form-check-label">Active</label>
        </div>
        @error('active')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>
</div>

<div class="d-flex gap-2">
    <button type="submit" class="btn btn-primary">Enregistrer</button>
    <a href="{{ $cancelUrl }}" class="btn btn-outline-secondary">Annuler</a>
</div>
