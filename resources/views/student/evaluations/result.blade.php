@extends('layouts.dashboard')

@section('title', 'Résultat — ' . $tentative->evaluation->titre)
@section('page-title', 'Résultat — ' . $tentative->evaluation->titre)

@php($statutBadges = [
    'REUSSI' => 'success',
    'ECHOUE' => 'danger',
    'EN_COURS' => 'secondary',
])
@php($noteMax = $tentative->evaluation->note_max ?? 20)
@php($statut = $tentative->statut ?? 'EN_COURS')
@php($pourcentage = $noteMax > 0 ? min(100, ($tentative->note / $noteMax) * 100) : 0)
@php($mesReponses = $tentative->reponsesEtudiants->keyBy('question_id'))

@section('page-actions')
    <a href="{{ route('student.formations.evaluations.index', $tentative->evaluation->formation_id) }}" class="btn btn-outline-secondary">
        <i class="fe fe-arrow-left me-1"></i> Retour aux évaluations
    </a>
@endsection

@section('content')
    {{-- Synthèse --}}
    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h3 class="mb-1">{{ $tentative->note }} / {{ $noteMax }}</h3>
                    <span class="text-muted">Score obtenu</span>
                </div>
                <span class="badge bg-light-{{ $statutBadges[$statut] ?? 'secondary' }} text-{{ $statutBadges[$statut] ?? 'secondary' }} fs-6">
                    {{ $statut }}
                </span>
            </div>
            <div class="progress" style="height: 10px;">
                <div class="progress-bar bg-{{ $statutBadges[$statut] ?? 'secondary' }}" role="progressbar"
                     style="width: {{ $pourcentage }}%;"
                     aria-valuenow="{{ $pourcentage }}" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>
    </div>

    {{-- Correction --}}
    @foreach ($tentative->evaluation->questions as $index => $question)
        @php($maReponse = $mesReponses[$question->id] ?? null)
        @php($estCorrecte = $maReponse?->correcte)
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <h5 class="mb-0">
                        <span class="text-muted me-1">{{ $index + 1 }}.</span>
                        {{ $question->question }}
                    </h5>
                    @if ($estCorrecte)
                        <span class="badge bg-light-success text-success">
                            <i class="fe fe-check me-1"></i> Correct
                        </span>
                    @else
                        <span class="badge bg-light-danger text-danger">
                            <i class="fe fe-x me-1"></i> Incorrect
                        </span>
                    @endif
                </div>

                <ul class="list-group list-group-flush">
                    @foreach ($question->reponses as $reponse)
                        <li class="list-group-item px-0 {{ $reponse->correcte ? 'bg-light-success text-success fw-semibold rounded px-2' : '' }}">
                            @if ($reponse->correcte)
                                <i class="fe fe-check-circle me-1"></i>
                            @endif
                            {{ $reponse->libelle }}
                        </li>
                    @endforeach
                </ul>

                @if (!empty($question->explication))
                    <div class="alert alert-info mt-3 mb-0">
                        <i class="fe fe-info me-1"></i> {{ $question->explication }}
                    </div>
                @endif
            </div>
        </div>
    @endforeach
@endsection
