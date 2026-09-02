@csrf
<div class="row">
    <div class="col-md-6 mb-3">
        <label for="prenom" class="form-label">Prénom <span class="text-danger">*</span></label>
        <input type="text" id="prenom" name="prenom" value="{{ old('prenom', $user->prenom ?? '') }}"
               class="form-control @error('prenom') is-invalid @enderror" required>
        @error('prenom')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label for="nom" class="form-label">Nom <span class="text-danger">*</span></label>
        <input type="text" id="nom" name="nom" value="{{ old('nom', $user->nom ?? '') }}"
               class="form-control @error('nom') is-invalid @enderror" required>
        @error('nom')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
        <input type="email" id="email" name="email" value="{{ old('email', $user->email ?? '') }}"
               class="form-control @error('email') is-invalid @enderror" required>
        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label for="telephone" class="form-label">Téléphone</label>
        <input type="text" id="telephone" name="telephone" value="{{ old('telephone', $user->telephone ?? '') }}"
               class="form-control @error('telephone') is-invalid @enderror">
        @error('telephone')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12 mb-3">
        <label class="form-label d-block">Avatar</label>
        <div class="d-flex align-items-center gap-3">
            @isset($user)
                <x-user-avatar :user="$user" size="lg" />
            @endisset
            <input type="file" name="avatar" accept="image/*" class="form-control @error('avatar') is-invalid @enderror">
            @error('avatar')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <label for="organisation_id" class="form-label">Organisation</label>
        <select id="organisation_id" name="organisation_id" class="form-select @error('organisation_id') is-invalid @enderror">
            <option value="">— Aucune —</option>
            @foreach ($organisations as $organisation)
                <option value="{{ $organisation->id }}" @selected(old('organisation_id', $user->organisation_id ?? '') == $organisation->id)>
                    {{ $organisation->nom }}
                </option>
            @endforeach
        </select>
        @error('organisation_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label d-block">Statut</label>
        <div class="form-check form-switch mt-2">
            <input type="hidden" name="active" value="0">
            <input type="checkbox" id="active" name="active" value="1" class="form-check-input"
                   @checked(old('active', $user->active ?? true))>
            <label class="form-check-label" for="active">Compte actif</label>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <label for="password" class="form-label">
            Mot de passe @if (isset($user)) <span class="text-muted small">(laisser vide pour conserver)</span> @else <span class="text-danger">*</span> @endif
        </label>
        <input type="password" id="password" name="password"
               class="form-control @error('password') is-invalid @enderror" {{ isset($user) ? '' : 'required' }}>
        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control">
    </div>
    <div class="col-12 mb-3">
        <label class="form-label">Rôles</label>
        <div class="row">
            @foreach ($roles as $role)
                <div class="col-md-4 col-6">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="role_{{ $role->id }}" name="roles[]" value="{{ $role->id }}"
                               @checked(in_array($role->id, old('roles', isset($user) ? $user->roles->pluck('id')->all() : [])))>
                        <label class="form-check-label" for="role_{{ $role->id }}">{{ $role->nom }}</label>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<div class="d-flex gap-2">
    <button type="submit" class="btn btn-primary">Enregistrer</button>
    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Annuler</a>
</div>
