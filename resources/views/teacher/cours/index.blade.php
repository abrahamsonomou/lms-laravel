@extends('layouts.dashboard')

@section('title', 'Contenu — ' . $formation->titre)
@section('page-title', 'Contenu — ' . $formation->titre)
@section('page-subtitle', 'Structurez les cours de cette formation.')

@section('page-actions')
    <div class="d-flex gap-2">
        <a href="{{ route('teacher.formations.cours.create', $formation) }}" class="btn btn-primary">
            <i class="fe fe-plus me-1"></i> Nouveau cours
        </a>
        <a href="{{ route('teacher.formations.index') }}" class="btn btn-outline-secondary">
            <i class="fe fe-arrow-left me-1"></i> Retour
        </a>
    </div>
@endsection

@php
    $statutBadges = [
        'BROUILLON' => 'secondary',
        'PUBLIE' => 'success',
    ];
@endphp

@section('content')
    @if ($formation->cours->isEmpty())
        <div class="card">
            <div class="card-body text-center py-6">
                <i class="fe fe-layers fs-1 text-muted mb-3 d-block"></i>
                <p class="mb-3 text-muted">Aucun cours. Ajoutez-en un pour structurer la formation.</p>
                <a href="{{ route('teacher.formations.cours.create', $formation) }}" class="btn btn-primary">
                    <i class="fe fe-plus me-1"></i> Nouveau cours
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
                            <th>Statut</th>
                            <th>Modules</th>
                            <th>Durée</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($formation->cours as $c)
                            <tr>
                                <td>
                                    <h5 class="mb-0">{{ $c->titre }}</h5>
                                </td>
                                <td>
                                    <span class="badge bg-light-{{ $statutBadges[$c->statut] ?? 'secondary' }} text-{{ $statutBadges[$c->statut] ?? 'secondary' }}">{{ $c->statut }}</span>
                                </td>
                                <td>{{ $c->modules_count }} module{{ $c->modules_count > 1 ? 's' : '' }}</td>
                                <td>{{ $c->duree ? $c->duree . ' h' : '—' }}</td>
                                <td class="text-end">
                                    <div class="d-inline-flex gap-2">
                                        <a href="{{ route('teacher.cours.builder', $c) }}" class="btn btn-sm btn-primary">
                                            <i class="fe fe-layers me-1"></i> Builder
                                        </a>
                                        <a href="{{ route('teacher.cours.edit', $c) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fe fe-edit-2 me-1"></i> Éditer
                                        </a>
                                        <form method="POST" action="{{ route('teacher.cours.destroy', $c) }}" onsubmit="return confirm('Supprimer ce cours ?');">
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
