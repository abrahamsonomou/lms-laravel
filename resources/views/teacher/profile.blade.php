@extends('layouts.dashboard')

@section('title', 'Mon profil')
@section('page-title', 'Mon profil')
@section('page-subtitle', 'Gérez vos informations personnelles.')

@section('content')
    @unless ($user->hasVerifiedEmail())
        <div class="alert alert-warning d-flex justify-content-between align-items-center">
            <span><i class="fe fe-alert-triangle me-1"></i> Votre adresse email n'est pas encore vérifiée.</span>
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="btn btn-sm btn-warning">Renvoyer le lien</button>
            </form>
        </div>
    @endunless

    <form method="POST" action="{{ route('teacher.profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-lg-4 col-12 mb-4">
                <div class="card">
                    <div class="card-body text-center">
                        <x-user-avatar :user="$user" size="xl" class="mx-auto mb-3" />
                        <h5 class="mb-0">{{ $user->name }}</h5>
                        <p class="text-muted small">{{ $user->email }}</p>
                        <div class="text-start mt-3">
                            <label for="avatar" class="form-label">Photo de profil</label>
                            <input type="file" id="avatar" name="avatar" accept="image/*"
                                   class="form-control @error('avatar') is-invalid @enderror">
                            @error('avatar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8 col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="prenom" class="form-label">Prénom <span class="text-danger">*</span></label>
                                <input type="text" id="prenom" name="prenom" value="{{ old('prenom', $user->prenom) }}"
                                       class="form-control @error('prenom') is-invalid @enderror" required>
                                @error('prenom')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="nom" class="form-label">Nom <span class="text-danger">*</span></label>
                                <input type="text" id="nom" name="nom" value="{{ old('nom', $user->nom) }}"
                                       class="form-control @error('nom') is-invalid @enderror" required>
                                @error('nom')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                                       class="form-control @error('email') is-invalid @enderror" required>
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                <div class="form-text">Changer d'email nécessitera une nouvelle vérification.</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="telephone" class="form-label">Téléphone</label>
                                <input type="text" id="telephone" name="telephone" value="{{ old('telephone', $user->telephone) }}"
                                       class="form-control @error('telephone') is-invalid @enderror">
                                @error('telephone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">
                                    Mot de passe <span class="text-muted small">(laisser vide pour conserver)</span>
                                </label>
                                <input type="password" id="password" name="password"
                                       class="form-control @error('password') is-invalid @enderror" autocomplete="new-password">
                                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                       class="form-control" autocomplete="new-password">
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                            <a href="{{ route('teacher.dashboard') }}" class="btn btn-outline-secondary">Retour au tableau de bord</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
