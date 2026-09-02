@extends('layouts.dashboard')

@section('title', 'Évaluations — ' . $formation->titre)
@section('page-title', 'Évaluations — ' . $formation->titre)

@php($statutBadges = [
    'REUSSI' => 'success',
    'ECHOUE' => 'danger',
    'EN_COURS' => 'secondary',
])

@section('page-actions')
    <a href="{{ route('student.formations.show', $formation) }}" class="btn btn-outline-secondary">
        <i class="fe fe-arrow-left me-1"></i> Retour à la formation
    </a>
@endsection

@section('content')
    @if ($formation->evaluations->isEmpty())
        <div class="card">
            <div class="card-body text-center py-6">
                <div class="icon-shape icon-lg bg-light-info text-info rounded-3 mx-auto mb-3">
                    <i class="fe fe-help-circle fs-3"></i>
                </div>
                <h4 class="mb-0">Aucune évaluation disponible pour cette formation.</h4>
            </div>
        </div>
    @else
        <div class="row">
            @foreach ($formation->evaluations as $evaluation)
                @php($historique = $tentatives[$evaluation->id] ?? collect())
                <div class="col-lg-6 col-12 mb-4">
                    <div class="card h-100">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h4 class="mb-0">{{ $evaluation->titre }}</h4>
                                <span class="badge bg-light-info text-info">{{ $evaluation->type }}</span>
                            </div>

                            <ul class="list-inline text-muted small mb-3">
                                <li class="list-inline-item">
                                    <i class="fe fe-list me-1"></i>{{ $evaluation->questions_count }} question(s)
                                </li>
                                <li class="list-inline-item">
                                    <i class="fe fe-award me-1"></i>Note max : {{ $evaluation->note_max ?? 20 }}
                                </li>
                                <li class="list-inline-item">
                                    <i class="fe fe-repeat me-1"></i>Tentatives : {{ $evaluation->tentatives_max ?? 'illimité' }}
                                </li>
                            </ul>

                            @if ($historique->isNotEmpty())
                                <div class="mb-3">
                                    <span class="text-uppercase fw-bold text-muted small">Historique</span>
                                    <ul class="list-group list-group-flush mt-1">
                                        @foreach ($historique as $t)
                                            @php($statut = $t->statut ?? 'EN_COURS')
                                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                                <span>
                                                    Tentative {{ $t->numero }} : {{ $t->note }} / {{ $evaluation->note_max ?? 20 }}
                                                    <span class="badge bg-light-{{ $statutBadges[$statut] ?? 'secondary' }} text-{{ $statutBadges[$statut] ?? 'secondary' }} ms-1">
                                                        {{ $statut }}
                                                    </span>
                                                </span>
                                                @if ($statut !== 'EN_COURS')
                                                    <a href="{{ route('student.tentatives.result', $t) }}" class="btn btn-sm btn-outline-info">
                                                        <i class="fe fe-eye me-1"></i> Voir
                                                    </a>
                                                @else
                                                    <a href="{{ route('student.tentatives.take', $t) }}" class="btn btn-sm btn-outline-primary">
                                                        <i class="fe fe-play me-1"></i> Reprendre
                                                    </a>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="mt-auto">
                                <form method="POST" action="{{ route('student.evaluations.start', $evaluation) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-primary w-100" @disabled($evaluation->questions_count == 0)>
                                        @if ($evaluation->questions_count == 0)
                                            <i class="fe fe-slash me-1"></i> Aucune question
                                        @else
                                            <i class="fe fe-play-circle me-1"></i> Démarrer l'évaluation
                                        @endif
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection
