@extends('layouts.dashboard')

@section('title', 'Mes formations')
@section('page-title', 'Mes formations')
@section('page-subtitle', 'Retrouvez toutes les formations auxquelles vous êtes inscrit.')

@php($statutBadges = [
    'INSCRIT' => 'secondary',
    'EN_COURS' => 'info',
    'TERMINE' => 'success',
])

@section('content')
    @if ($inscriptions->isEmpty())
        <div class="card">
            <div class="card-body text-center py-6">
                <div class="icon-shape icon-lg bg-light-primary text-primary rounded-3 mx-auto mb-3">
                    <i class="fe fe-book-open fs-3"></i>
                </div>
                <h4 class="mb-2">Vous n'êtes inscrit à aucune formation.</h4>
                <p class="text-muted mb-4">Parcourez notre catalogue et commencez votre apprentissage dès aujourd'hui.</p>
                <a href="{{ route('catalogue.index') }}" class="btn btn-primary">
                    <i class="fe fe-compass me-1"></i> Explorer le catalogue
                </a>
            </div>
        </div>
    @else
        <div class="row">
            @foreach ($inscriptions as $i)
                @php($statut = $i->statut ?? 'INSCRIT')
                @php($progression = (float) ($i->progression ?? 0))
                <div class="col-md-4 col-12 mb-4">
                    <div class="card h-100">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="badge bg-light-secondary text-secondary">
                                    {{ $i->formation?->categorie?->nom ?? 'Non classé' }}
                                </span>
                                <span class="badge bg-light-{{ $statutBadges[$statut] ?? 'secondary' }} text-{{ $statutBadges[$statut] ?? 'secondary' }}">
                                    {{ $statut }}
                                </span>
                            </div>

                            <h4 class="mb-1">{{ $i->formation?->titre }}</h4>

                            @if ($i->formation?->devise?->code)
                                <p class="text-muted small mb-3">{{ $i->formation->devise->code }}</p>
                            @endif

                            <div class="mt-auto">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="small text-muted">Progression</span>
                                    <span class="small fw-semibold">{{ number_format($progression, 0) }}%</span>
                                </div>
                                <div class="progress mb-3" style="height: 8px;">
                                    <div class="progress-bar" role="progressbar" style="width: {{ $progression }}%;"
                                         aria-valuenow="{{ $progression }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>

                                @if ($i->formation)
                                    <a href="{{ route('student.formations.show', $i->formation) }}" class="btn btn-primary w-100">
                                        <i class="fe fe-play-circle me-1"></i> Continuer
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-2">
            {{ $inscriptions->links() }}
        </div>
    @endif
@endsection
