@extends('layouts.dashboard')

@section('title', 'Éditeur — ' . $page->nom)
@section('page-title', 'Éditeur : ' . $page->nom)

@section('page-actions')
    <div class="d-flex gap-2">
        <a href="{{ route('landing.show', $page) }}" target="_blank" class="btn btn-outline-primary"><i class="fe fe-eye me-1"></i> Aperçu</a>
        <a href="{{ route('admin.studio.edit', $page->project_id) }}" class="btn btn-outline-secondary">Retour au projet</a>
    </div>
@endsection

@section('content')
    <div class="row">
        {{-- Colonne blocs --}}
        <div class="col-lg-7 mb-4">
            <div class="card mb-4">
                <div class="card-header"><h4 class="mb-0">Contenu de la page</h4></div>
                <div class="card-body">
                    @forelse ($page->contenu_json ?? [] as $index => $bloc)
                        <div class="border rounded p-3 mb-2 d-flex justify-content-between align-items-start">
                            <div>
                                <span class="badge bg-light-primary text-primary text-uppercase">{{ $bloc['type'] ?? 'bloc' }}</span>
                                <div class="fw-semibold mt-1">{{ $bloc['titre'] ?? '—' }}</div>
                                @isset($bloc['contenu'])<small class="text-muted">{{ \Illuminate\Support\Str::limit($bloc['contenu'], 80) }}</small>@endisset
                            </div>
                            <form method="POST" action="{{ route('admin.studio.pages.removeBlock', ['page' => $page, 'index' => $index]) }}">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fe fe-trash-2"></i></button>
                            </form>
                        </div>
                    @empty
                        <p class="text-muted mb-0">Aucun bloc. Ajoutez-en un depuis le panneau de droite.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Colonne outils --}}
        <div class="col-lg-5 mb-4">
            <div class="card mb-4">
                <div class="card-header"><h4 class="mb-0">Ajouter un bloc</h4></div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.studio.pages.addBlock', $page) }}">
                        @csrf
                        <div class="mb-3">
                            <label for="type" class="form-label">Type</label>
                            <select id="type" name="type" class="form-select">
                                @foreach ($blocs as $b)
                                    <option value="{{ $b }}">{{ ucfirst($b) }}</option>
                                @endforeach
                            </select>
                            <div class="form-text">hero : titre + sous-titre + bouton · texte : titre + contenu · cta : titre + bouton</div>
                        </div>
                        <div class="mb-3">
                            <label for="titre" class="form-label">Titre</label>
                            <input type="text" id="titre" name="titre" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="sous_titre" class="form-label">Sous-titre</label>
                            <input type="text" id="sous_titre" name="sous_titre" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="contenu" class="form-label">Contenu</label>
                            <textarea id="contenu" name="contenu" rows="3" class="form-control"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label for="bouton_texte" class="form-label">Bouton (texte)</label>
                                <input type="text" id="bouton_texte" name="bouton_texte" class="form-control">
                            </div>
                            <div class="col-6 mb-3">
                                <label for="bouton_url" class="form-label">Bouton (URL)</label>
                                <input type="text" id="bouton_url" name="bouton_url" class="form-control" placeholder="https://…">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Ajouter le bloc</button>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h4 class="mb-0">Réglages de la page</h4></div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.studio.pages.update', $page) }}">
                        @csrf @method('PUT')
                        <div class="mb-3">
                            <label for="nom" class="form-label">Nom</label>
                            <input type="text" id="nom" name="nom" value="{{ old('nom', $page->nom) }}" class="form-control @error('nom') is-invalid @enderror" required>
                            @error('nom')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label for="slug" class="form-label">Slug (URL)</label>
                            <div class="input-group">
                                <span class="input-group-text">/p/</span>
                                <input type="text" id="slug" name="slug" value="{{ old('slug', $page->slug) }}" class="form-control @error('slug') is-invalid @enderror" required>
                                @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input type="hidden" name="active" value="0">
                            <input type="checkbox" id="active" name="active" value="1" class="form-check-input" @checked($page->active)>
                            <label class="form-check-label" for="active">Page visible</label>
                        </div>
                        <button type="submit" class="btn btn-outline-primary w-100">Enregistrer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
