@extends('layouts.dashboard')

@section('title', $project->nom)
@section('page-title', $project->nom)
@section('page-subtitle', 'Projet Studio')

@section('page-actions')
    <a href="{{ route('admin.studio.index') }}" class="btn btn-outline-secondary"><i class="fe fe-arrow-left me-1"></i> Projets</a>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-5 mb-4">
            <div class="card">
                <div class="card-header"><h4 class="mb-0">Paramètres</h4></div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.studio.update', $project) }}">
                        @csrf @method('PUT')
                        <div class="mb-3">
                            <label for="nom" class="form-label">Nom</label>
                            <input type="text" id="nom" name="nom" value="{{ old('nom', $project->nom) }}" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea id="description" name="description" rows="3" class="form-control">{{ old('description', $project->description) }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="statut" class="form-label">Statut</label>
                            <select id="statut" name="statut" class="form-select">
                                <option value="BROUILLON" @selected($project->statut === 'BROUILLON')>Brouillon</option>
                                <option value="PUBLIE" @selected($project->statut === 'PUBLIE')>Publié</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-7 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Pages</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.studio.pages.store', $project) }}" class="mb-4">
                        @csrf
                        <div class="input-group">
                            <input type="text" name="nom" class="form-control @error('nom') is-invalid @enderror" placeholder="Nom de la nouvelle page" required>
                            <button type="submit" class="btn btn-primary"><i class="fe fe-plus me-1"></i> Ajouter</button>
                            @error('nom')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </form>

                    <ul class="list-group">
                        @forelse ($project->pages as $page)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="fw-semibold">{{ $page->nom }}</span>
                                    <small class="text-muted d-block"><code>/p/{{ $page->slug }}</code>
                                        @if (! $page->active)<span class="badge bg-light-secondary text-secondary ms-1">masquée</span>@endif
                                    </small>
                                </div>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('admin.studio.pages.builder', $page) }}" class="btn btn-sm btn-primary">Éditer</a>
                                    <a href="{{ route('landing.show', $page) }}" target="_blank" class="btn btn-sm btn-outline-secondary"><i class="fe fe-external-link"></i></a>
                                    <form method="POST" action="{{ route('admin.studio.pages.destroy', $page) }}" onsubmit="return confirm('Supprimer cette page ?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fe fe-trash-2"></i></button>
                                    </form>
                                </div>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted">Aucune page. Ajoutez-en une ci-dessus.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
