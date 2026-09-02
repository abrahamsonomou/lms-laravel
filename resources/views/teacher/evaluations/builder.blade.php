@extends('layouts.dashboard')

@section('title', 'Builder — ' . $evaluation->titre)
@section('page-title', 'Builder — ' . $evaluation->titre)
@section('page-subtitle', 'Composez les questions et leurs réponses.')

@section('page-actions')
    <a href="{{ route('teacher.formations.evaluations.index', $evaluation->formation_id) }}" class="btn btn-outline-secondary">
        <i class="fe fe-arrow-left me-1"></i> Retour aux évaluations
    </a>
@endsection

@php
    $typeQuestionBadges = [
        'QCM' => 'primary',
        'MULTIPLE' => 'info',
        'VRAI_FAUX' => 'warning',
    ];
@endphp

@section('content')
    {{-- Ajouter une question --}}
    <div class="card mb-4">
        <div class="card-header">
            <h4 class="mb-0"><i class="fe fe-plus-square me-1"></i> Ajouter une question</h4>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('teacher.evaluations.questions.store', $evaluation) }}">
                @csrf
                <div class="row g-3">
                    <div class="col-12">
                        <label for="question" class="form-label">Question <span class="text-danger">*</span></label>
                        <textarea id="question" name="question" rows="2"
                                  class="form-control @error('question') is-invalid @enderror" required>{{ old('question') }}</textarea>
                        @error('question')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-8">
                        <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
                        <select id="type" name="type" class="form-select @error('type') is-invalid @enderror" required>
                            @foreach ($typesQuestion as $type)
                                <option value="{{ $type }}" @selected(old('type') == $type)>{{ $type }}</option>
                            @endforeach
                        </select>
                        <div class="form-text">QCM = une seule bonne réponse · MULTIPLE = plusieurs bonnes réponses · VRAI_FAUX = vrai ou faux.</div>
                        @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label for="points" class="form-label">Points</label>
                        <input type="number" id="points" name="points" step="0.01" min="0" value="{{ old('points', 1) }}"
                               class="form-control @error('points') is-invalid @enderror">
                        @error('points')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label for="explication" class="form-label">Explication (optionnel)</label>
                        <textarea id="explication" name="explication" rows="2"
                                  class="form-control @error('explication') is-invalid @enderror">{{ old('explication') }}</textarea>
                        @error('explication')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fe fe-plus me-1"></i> Ajouter la question
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @forelse ($evaluation->questions as $question)
        <div class="card mb-4">
            <div class="card-header d-flex align-items-start justify-content-between">
                <div>
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <h4 class="mb-0">Question {{ $loop->iteration }}</h4>
                        <span class="badge bg-light-{{ $typeQuestionBadges[$question->type] ?? 'secondary' }} text-{{ $typeQuestionBadges[$question->type] ?? 'secondary' }}">{{ $question->type }}</span>
                        <span class="badge bg-light-secondary text-secondary">{{ $question->points }} pt{{ $question->points > 1 ? 's' : '' }}</span>
                    </div>
                    <p class="mb-0">{{ $question->question }}</p>
                    @if ($question->explication)
                        <p class="text-muted small mb-0 mt-1"><i class="fe fe-info me-1"></i>{{ $question->explication }}</p>
                    @endif
                </div>
                <form method="POST" action="{{ route('teacher.questions.destroy', $question) }}" onsubmit="return confirm('Supprimer cette question et ses réponses ?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer la question">
                        <i class="fe fe-trash-2"></i>
                    </button>
                </form>
            </div>
            <div class="card-body">
                {{-- Réponses --}}
                @if ($question->reponses->isNotEmpty())
                    <ul class="list-group list-group-flush mb-3">
                        @foreach ($question->reponses as $reponse)
                            <li class="list-group-item d-flex align-items-center justify-content-between px-0">
                                <div class="d-flex align-items-center gap-2">
                                    <span>{{ $reponse->libelle }}</span>
                                    @if ($reponse->correcte)
                                        <span class="badge bg-light-success text-success"><i class="fe fe-check me-1"></i>Correcte</span>
                                    @endif
                                </div>
                                <form method="POST" action="{{ route('teacher.reponses.destroy', $reponse) }}" onsubmit="return confirm('Supprimer cette réponse ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer la réponse">
                                        <i class="fe fe-trash-2"></i>
                                    </button>
                                </form>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted small mb-3">Aucune réponse. Ajoutez-en ci-dessous.</p>
                @endif

                {{-- Ajouter une réponse --}}
                <form method="POST" action="{{ route('teacher.questions.reponses.store', $question) }}">
                    @csrf
                    <div class="row g-2 align-items-center">
                        <div class="col-md-7">
                            <input type="text" name="libelle" class="form-control form-control-sm" placeholder="Libellé de la réponse" required>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check">
                                <input type="checkbox" id="correcte-{{ $question->id }}" name="correcte" value="1" class="form-check-input">
                                <label for="correcte-{{ $question->id }}" class="form-check-label">Bonne réponse</label>
                            </div>
                        </div>
                        <div class="col-md-2 d-grid">
                            <button type="submit" class="btn btn-sm btn-outline-primary">
                                <i class="fe fe-plus me-1"></i> Ajouter
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @empty
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fe fe-help-circle fs-1 text-muted mb-3 d-block"></i>
                <p class="mb-0 text-muted">Aucune question. Commencez par en ajouter une ci-dessus.</p>
            </div>
        </div>
    @endforelse
@endsection
