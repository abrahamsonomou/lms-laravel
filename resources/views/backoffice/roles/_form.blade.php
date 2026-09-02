@csrf
<div class="row">
    <div class="col-md-6 mb-3">
        <label for="code" class="form-label">Code <span class="text-danger">*</span></label>
        <input type="text" id="code" name="code" value="{{ old('code', $role->code ?? '') }}"
               class="form-control @error('code') is-invalid @enderror" required>
        @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label for="nom" class="form-label">Nom <span class="text-danger">*</span></label>
        <input type="text" id="nom" name="nom" value="{{ old('nom', $role->nom ?? '') }}"
               class="form-control @error('nom') is-invalid @enderror" required>
        @error('nom')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12 mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea id="description" name="description" rows="3"
                  class="form-control @error('description') is-invalid @enderror">{{ old('description', $role->description ?? '') }}</textarea>
        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12 mb-3">
        <label class="form-label d-block">Statut</label>
        <div class="form-check form-switch mt-2">
            <input type="hidden" name="active" value="0">
            <input type="checkbox" id="active" name="active" value="1" class="form-check-input"
                   @checked(old('active', $role->active ?? true))>
            <label class="form-check-label" for="active">Rôle actif</label>
        </div>
    </div>
</div>

<div class="mb-3">
    <label class="form-label">Permissions</label>
    <div class="row">
        @foreach ($permissions as $module => $modulePermissions)
            <div class="col-md-6 col-xl-4 mb-3">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between py-2">
                        <span class="fw-semibold text-uppercase">{{ $module }}</span>
                        <a href="#" class="small text-decoration-none js-check-all-module">Tout cocher</a>
                    </div>
                    <div class="card-body">
                        @foreach ($modulePermissions as $permission)
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="permission_{{ $permission->id }}"
                                       name="permissions[]" value="{{ $permission->id }}"
                                       @checked(in_array($permission->id, old('permissions', isset($role) ? $role->permissions->pluck('id')->all() : [])))>
                                <label class="form-check-label" for="permission_{{ $permission->id }}">
                                    <code>{{ $permission->code }}</code> {{ $permission->nom }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    @error('permissions')<div class="text-danger small">{{ $message }}</div>@enderror
</div>

<div class="d-flex gap-2">
    <button type="submit" class="btn btn-primary">Enregistrer</button>
    <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary">Annuler</a>
</div>

@push('scripts')
<script>
    document.querySelectorAll('.js-check-all-module').forEach(function (link) {
        link.addEventListener('click', function (event) {
            event.preventDefault();
            const card = link.closest('.card');
            const checkboxes = card.querySelectorAll('input[type="checkbox"]');
            const allChecked = Array.from(checkboxes).every((cb) => cb.checked);
            checkboxes.forEach((cb) => { cb.checked = !allChecked; });
        });
    });
</script>
@endpush
