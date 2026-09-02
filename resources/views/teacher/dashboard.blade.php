@extends('layouts.dashboard')

@section('title', 'Tableau de bord')
@section('page-title', 'Tableau de bord')
@section('page-subtitle', 'Bonjour ' . (auth()->user()->prenom ?? auth()->user()->name) . ', voici votre activité')

@section('page-actions')
    <a href="{{ route('teacher.formations.create') }}" class="btn btn-primary">
        <i class="fe fe-plus me-1"></i> Nouvelle formation
    </a>
@endsection

@section('content')
    <div class="row">
        @php($cards = [
            ['label' => 'Mes formations', 'value' => number_format($stats['formations'], 0, ',', ' '), 'icon' => 'fe-book', 'bg' => 'primary'],
            ['label' => 'Étudiants', 'value' => number_format($stats['etudiants'], 0, ',', ' '), 'icon' => 'fe-users', 'bg' => 'info'],
            ['label' => 'Évaluations', 'value' => number_format($stats['evaluations'], 0, ',', ' '), 'icon' => 'fe-help-circle', 'bg' => 'success'],
            ['label' => 'Revenus générés', 'value' => number_format($stats['revenus'], 2, ',', ' '), 'icon' => 'fe-dollar-sign', 'bg' => 'warning'],
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

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Mes dernières formations</h4>
            <a href="{{ route('teacher.formations.index') }}" class="btn btn-sm btn-outline-primary">Toutes mes formations</a>
        </div>
        <div class="table-responsive">
            <table class="table mb-0 text-nowrap">
                <thead class="table-light">
                    <tr><th>Titre</th><th>Statut</th><th>Cours</th><th class="text-end">Actions</th></tr>
                </thead>
                <tbody>
                    @forelse ($formations as $formation)
                        <tr>
                            <td class="align-middle">{{ $formation->titre }}</td>
                            <td class="align-middle">
                                <span class="badge bg-light-{{ $formation->statut === 'PUBLIE' ? 'success' : 'secondary' }} text-{{ $formation->statut === 'PUBLIE' ? 'success' : 'secondary' }}">{{ $formation->statut }}</span>
                            </td>
                            <td class="align-middle">{{ $formation->cours_count }}</td>
                            <td class="align-middle text-end">
                                <a href="{{ route('teacher.formations.cours.index', $formation) }}" class="btn btn-sm btn-outline-primary">Contenu</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center py-4 text-muted">Vous n'avez pas encore créé de formation.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
