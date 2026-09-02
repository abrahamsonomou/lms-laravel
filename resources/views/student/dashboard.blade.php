@extends('layouts.dashboard')

@section('title', 'Tableau de bord')
@section('page-title', 'Tableau de bord')
@section('page-subtitle', 'Bonjour ' . (auth()->user()->prenom ?? auth()->user()->name) . ', bon apprentissage !')

@section('content')
    <div class="row">
        @php($cards = [
            ['label' => 'Mes formations', 'value' => number_format($stats['cours'], 0, ',', ' '), 'icon' => 'fe-book-open', 'bg' => 'primary'],
            ['label' => 'Terminées', 'value' => number_format($stats['terminees'], 0, ',', ' '), 'icon' => 'fe-check-circle', 'bg' => 'success'],
            ['label' => 'Progression moyenne', 'value' => number_format($stats['progression'], 0, ',', ' ') . '%', 'icon' => 'fe-trending-up', 'bg' => 'info'],
            ['label' => 'Certificats', 'value' => number_format($stats['certificats'], 0, ',', ' '), 'icon' => 'fe-award', 'bg' => 'warning'],
        ])
        @foreach ($cards as $card)
            <div class="col-xl-3 col-lg-6 col-md-6 col-12 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="fs-6 text-uppercase fw-semibold text-muted">{{ $card['label'] }}</span>
                                <h2 class="fw-bold mt-2 mb-0">{{ $card['value'] }}</h2>
                            </div>
                            <div class="icon-shape icon-lg bg-light-{{ $card['bg'] }} text-{{ $card['bg'] }} rounded-3">
                                <i class="fe {{ $card['icon'] }} fs-3"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row">
        <div class="col-lg-8 col-12 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Continuer l'apprentissage</h4>
                    <a href="{{ route('student.formations.index') }}" class="btn btn-sm btn-outline-primary">Mes formations</a>
                </div>
                <div class="card-body">
                    @forelse ($enCours as $inscription)
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <a href="{{ route('student.formations.show', $inscription->formation) }}" class="fw-semibold text-reset">
                                    {{ $inscription->formation?->titre }}
                                </a>
                                <span class="text-muted">{{ number_format($inscription->progression, 0) }}%</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar" role="progressbar" style="width: {{ $inscription->progression }}%;"></div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <p class="text-muted mb-3">Vous ne suivez aucune formation pour le moment.</p>
                            <a href="{{ route('catalogue.index') }}" class="btn btn-primary">Explorer le catalogue</a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-12 mb-4">
            <div class="card h-100">
                <div class="card-body d-flex flex-column justify-content-center text-center">
                    <div class="icon-shape icon-lg bg-light-primary text-primary rounded-3 mx-auto mb-3">
                        <i class="fe fe-compass fs-3"></i>
                    </div>
                    <h4 class="mb-1">Découvrez de nouvelles formations</h4>
                    <p class="text-muted">Explorez notre catalogue et développez vos compétences.</p>
                    <a href="{{ route('catalogue.index') }}" class="btn btn-primary mt-2">Voir le catalogue</a>
                </div>
            </div>
        </div>
    </div>
@endsection
