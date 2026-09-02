@extends('layouts.dashboard')

@section('title', 'Nouveau projet')
@section('page-title', 'Nouveau projet Studio')

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.studio.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="nom" class="form-label">Nom <span class="text-danger">*</span></label>
                            <input type="text" id="nom" name="nom" value="{{ old('nom') }}" class="form-control @error('nom') is-invalid @enderror" required>
                            @error('nom')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea id="description" name="description" rows="3" class="form-control">{{ old('description') }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="statut" class="form-label">Statut</label>
                            <select id="statut" name="statut" class="form-select">
                                <option value="BROUILLON" @selected(old('statut') === 'BROUILLON')>Brouillon</option>
                                <option value="PUBLIE" @selected(old('statut') === 'PUBLIE')>Publié</option>
                            </select>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Créer</button>
                            <a href="{{ route('admin.studio.index') }}" class="btn btn-outline-secondary">Annuler</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
