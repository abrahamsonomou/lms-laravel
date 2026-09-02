@csrf
<div class="row">
    <div class="col-md-6 mb-3">
        <label for="code" class="form-label">Code <span class="text-danger">*</span></label>
        <input type="text" id="code" name="code" value="{{ old('code', $categorie->code ?? '') }}"
               class="form-control @error('code') is-invalid @enderror" required>
        @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label for="nom" class="form-label">Nom <span class="text-danger">*</span></label>
        <input type="text" id="nom" name="nom" value="{{ old('nom', $categorie->nom ?? '') }}"
               class="form-control @error('nom') is-invalid @enderror" required>
        @error('nom')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label for="parent_id" class="form-label">Catégorie parente</label>
        <select id="parent_id" name="parent_id" class="form-select @error('parent_id') is-invalid @enderror">
            <option value="">— Aucune —</option>
            @foreach ($parents as $p)
                <option value="{{ $p->id }}" @selected(old('parent_id', $categorie->parent_id ?? '') == $p->id)>{{ $p->nom }}</option>
            @endforeach
        </select>
        @error('parent_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12 mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea id="description" name="description" rows="3"
                  class="form-control @error('description') is-invalid @enderror">{{ old('description', $categorie->description ?? '') }}</textarea>
        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12 mb-3">
        <label class="form-label d-block">Image</label>
        <div class="d-flex align-items-center gap-3">
            @if (isset($categorie) && $categorie->image)
                <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($categorie->image) }}"
                     alt="{{ $categorie->nom }}" class="rounded" width="64" height="64" style="object-fit: cover;">
            @endif
            <input type="file" name="image" accept="image/*" class="form-control @error('image') is-invalid @enderror">
            @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label d-block">Statut</label>
        <div class="form-check form-switch mt-2">
            <input type="hidden" name="active" value="0">
            <input type="checkbox" id="active" name="active" value="1" class="form-check-input"
                   @checked(old('active', $categorie->active ?? true))>
            <label class="form-check-label" for="active">Catégorie active</label>
        </div>
    </div>
</div>

<div class="d-flex gap-2">
    <button type="submit" class="btn btn-primary">Enregistrer</button>
    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">Annuler</a>
</div>
