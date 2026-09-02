@extends('layouts.dashboard')

@section('title', 'Évaluations — ' . $formation->titre)
@section('page-title', 'Évaluations — ' . $formation->titre)
@section('page-subtitle', 'Gérez les évaluations de cette formation.')

@section('page-actions')
    <div class="d-flex gap-2">
        <a href="{{ route('teacher.formations.evaluations.create', $formation) }}" class="btn btn-primary">
            <i class="fe fe-plus me-1"></i> Nouvelle évaluation
        </a>
        <a href="{{ route('teacher.formations.index') }}" class="btn btn-outline-secondary">
            <i class="fe fe-arrow-left me-1"></i> Retour
        </a>
    </div>
@endsection

@php
    $typeBadges = [
        'QUIZ' => 'primary',
        'EXAMEN' => 'warning',
    ];
@endphp

@section('content')
    @if ($formation->evaluations->isEmpty())
        <div class="card">
            <div class="card-body text-center py-6">
                <i class="fe fe-clipboard fs-1 text-muted mb-3 d-block"></i>
                <p class="mb-3 text-muted">Aucune évaluation. Créez-en une pour évaluer les apprenants.</p>
                <a href="{{ route('teacher.formations.evaluations.create', $formation) }}" class="btn btn-primary">
                    <i class="fe fe-plus me-1"></i> Nouvelle évaluation
                </a>
            </div>
        </div>
    @else
        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover text-nowrap mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Titre</th>
                            <th>Type</th>
                            <th>Durée</th>
                            <th>Questions</th>
                            <th>Note max</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($formation->evaluations as $e)
                            <tr>
                                <td>
                                    <h5 class="mb-0">{{ $e->titre }}</h5>
                                </td>
                                <td>
                                    <span class="badge bg-light-{{ $typeBadges[$e->type] ?? 'secondary' }} text-{{ $typeBadges[$e->type] ?? 'secondary' }}">{{ $e->type }}</span>
                                </td>
                                <td>{{ $e->duree ? $e->duree . ' min' : '—' }}</td>
                                <td>{{ $e->questions_count }} question{{ $e->questions_count > 1 ? 's' : '' }}</td>
                                <td>{{ $e->note_max }}</td>
                                <td class="text-end">
                                    <div class="d-inline-flex gap-2">
                                        <a href="{{ route('teacher.evaluations.builder', $e) }}" class="btn btn-sm btn-primary">
                                            <i class="fe fe-list me-1"></i> Builder
                                        </a>
                                        <a href="{{ route('teacher.evaluations.edit', $e) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fe fe-edit-2 me-1"></i> Éditer
                                        </a>
                                        <form method="POST" action="{{ route('teacher.evaluations.destroy', $e) }}" onsubmit="return confirm('Supprimer cette évaluation ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fe fe-trash-2 me-1"></i> Supprimer
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endsection
