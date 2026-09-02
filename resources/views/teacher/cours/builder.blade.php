@extends('layouts.dashboard')

@section('title', 'Builder — ' . $cours->titre)
@section('page-title', 'Builder — ' . $cours->titre)
@section('page-subtitle', 'Organisez les modules, chapitres et leçons.')

@section('page-actions')
    <a href="{{ route('teacher.formations.cours.index', $cours->formation_id) }}" class="btn btn-outline-secondary">
        <i class="fe fe-arrow-left me-1"></i> Retour aux cours
    </a>
@endsection

@section('content')
    {{-- Ajouter un module --}}
    <div class="card mb-4">
        <div class="card-header">
            <h4 class="mb-0"><i class="fe fe-plus-square me-1"></i> Ajouter un module</h4>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('teacher.cours.modules.store', $cours) }}">
                @csrf
                <div class="row g-2 align-items-start">
                    <div class="col-md-5">
                        <input type="text" name="titre" value="{{ old('titre') }}"
                               class="form-control @error('titre') is-invalid @enderror" placeholder="Titre du module" required>
                        @error('titre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-5">
                        <input type="text" name="description" value="{{ old('description') }}"
                               class="form-control @error('description') is-invalid @enderror" placeholder="Description (optionnel)">
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-2 d-grid">
                        <button type="submit" class="btn btn-primary">Ajouter le module</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @forelse ($cours->modules as $module)
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h4 class="mb-0"><i class="fe fe-folder me-1"></i> {{ $module->titre }}</h4>
                <form method="POST" action="{{ route('teacher.modules.destroy', $module) }}" onsubmit="return confirm('Supprimer ce module et tout son contenu ?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer le module">
                        <i class="fe fe-trash-2"></i>
                    </button>
                </form>
            </div>
            <div class="card-body">
                {{-- Ajouter un chapitre --}}
                <form method="POST" action="{{ route('teacher.modules.chapitres.store', $module) }}" class="mb-3">
                    @csrf
                    <div class="row g-2">
                        <div class="col-md-9">
                            <input type="text" name="titre" class="form-control" placeholder="Titre du chapitre" required>
                        </div>
                        <div class="col-md-3 d-grid">
                            <button type="submit" class="btn btn-outline-primary">
                                <i class="fe fe-plus me-1"></i> Ajouter un chapitre
                            </button>
                        </div>
                    </div>
                </form>

                @forelse ($module->chapitres as $chapitre)
                    <div class="border rounded p-3 mb-3 ms-3">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <h5 class="mb-0"><i class="fe fe-bookmark me-1"></i> {{ $chapitre->titre }}</h5>
                            <form method="POST" action="{{ route('teacher.chapitres.destroy', $chapitre) }}" onsubmit="return confirm('Supprimer ce chapitre et ses leçons ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer le chapitre">
                                    <i class="fe fe-trash-2"></i>
                                </button>
                            </form>
                        </div>

                        {{-- Leçons --}}
                        @if ($chapitre->lecons->isNotEmpty())
                            <ul class="list-group list-group-flush mb-3">
                                @foreach ($chapitre->lecons as $lecon)
                                    <li class="list-group-item d-flex align-items-center justify-content-between px-0">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge bg-light-primary text-primary">{{ $lecon->type }}</span>
                                            <span>{{ $lecon->titre }}</span>
                                            @if ($lecon->contenus->isNotEmpty())
                                                <i class="fe fe-paperclip text-muted" title="Contenu attaché"></i>
                                            @endif
                                            @if ($lecon->duree)
                                                <span class="text-muted small">{{ $lecon->duree }} min</span>
                                            @endif
                                        </div>
                                        <form method="POST" action="{{ route('teacher.lecons.destroy', $lecon) }}" onsubmit="return confirm('Supprimer cette leçon ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer la leçon">
                                                <i class="fe fe-trash-2"></i>
                                            </button>
                                        </form>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted small mb-3">Aucune leçon dans ce chapitre.</p>
                        @endif

                        {{-- Ajouter une leçon --}}
                        <form method="POST" action="{{ route('teacher.chapitres.lecons.store', $chapitre) }}" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-2 align-items-end">
                                <div class="col-md-3">
                                    <label class="form-label small mb-1">Titre <span class="text-danger">*</span></label>
                                    <input type="text" name="titre" class="form-control form-control-sm" placeholder="Titre de la leçon" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label small mb-1">Type</label>
                                    <select name="type" class="form-select form-select-sm" required>
                                        @foreach ($typesLecon as $type)
                                            <option value="{{ $type }}">{{ $type }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-1">
                                    <label class="form-label small mb-1">Durée</label>
                                    <input type="number" name="duree" min="0" class="form-control form-control-sm" placeholder="min">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small mb-1">URL</label>
                                    <input type="url" name="url" class="form-control form-control-sm" placeholder="Lien vidéo/ressource">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label small mb-1">Fichier</label>
                                    <input type="file" name="fichier" class="form-control form-control-sm">
                                </div>
                                <div class="col-md-1 d-grid">
                                    <button type="submit" class="btn btn-sm btn-primary" title="Ajouter la leçon">
                                        <i class="fe fe-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                @empty
                    <p class="text-muted small ms-3 mb-0">Aucun chapitre. Ajoutez-en un ci-dessus.</p>
                @endforelse
            </div>
        </div>
    @empty
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fe fe-folder-plus fs-1 text-muted mb-3 d-block"></i>
                <p class="mb-0 text-muted">Aucun module. Commencez par ajouter un module ci-dessus.</p>
            </div>
        </div>
    @endforelse
@endsection
