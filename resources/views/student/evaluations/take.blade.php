@extends('layouts.dashboard')

@section('title', $tentative->evaluation->titre)
@section('page-title', $tentative->evaluation->titre)

@section('page-actions')
    <a href="{{ route('student.formations.evaluations.index', $tentative->evaluation->formation_id) }}" class="btn btn-outline-secondary">
        <i class="fe fe-arrow-left me-1"></i> Retour aux évaluations
    </a>
@endsection

@section('content')
    @if ($tentative->evaluation->questions->isEmpty())
        <div class="card">
            <div class="card-body text-center py-6">
                <div class="icon-shape icon-lg bg-light-warning text-warning rounded-3 mx-auto mb-3">
                    <i class="fe fe-alert-triangle fs-3"></i>
                </div>
                <h4 class="mb-0">Cette évaluation ne contient aucune question.</h4>
            </div>
        </div>
    @else
        <form method="POST" action="{{ route('student.tentatives.submit', $tentative) }}">
            @csrf

            @foreach ($tentative->evaluation->questions as $index => $question)
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h5 class="mb-0">
                                <span class="text-muted me-1">{{ $index + 1 }}.</span>
                                {{ $question->question }}
                            </h5>
                            <span class="badge bg-light-secondary text-secondary">{{ $question->points }} pt(s)</span>
                        </div>

                        @foreach ($question->reponses as $reponse)
                            <div class="form-check mb-2">
                                @if ($question->type === 'MULTIPLE')
                                    <input class="form-check-input" type="checkbox"
                                           name="answers[{{ $question->id }}][]"
                                           value="{{ $reponse->id }}"
                                           id="reponse-{{ $reponse->id }}">
                                @else
                                    <input class="form-check-input" type="radio"
                                           name="answers[{{ $question->id }}]"
                                           value="{{ $reponse->id }}"
                                           id="reponse-{{ $reponse->id }}">
                                @endif
                                <label class="form-check-label" for="reponse-{{ $reponse->id }}">
                                    {{ $reponse->libelle }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">
                    <i class="fe fe-check-circle me-1"></i> Terminer et soumettre
                </button>
            </div>
        </form>
    @endif
@endsection
