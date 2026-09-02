@extends('layouts.dashboard')

@section('title', 'Mon abonnement')
@section('page-title', 'Mon abonnement')

@section('content')
    <div class="row">
        <div class="col-lg-8">
            @if ($actif)
                <div class="card bg-light-success mb-4">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h3 class="mb-0">{{ $actif->plan?->nom }}</h3>
                            <span class="badge bg-light-success text-success">Actif</span>
                        </div>
                        <p class="mb-1"><i class="fe fe-calendar me-1"></i> Actif jusqu'au {{ $actif->date_fin?->format('d/m/Y') }}</p>
                        <p class="mb-3 text-muted"><i class="fe fe-check-circle me-1"></i> Accès illimité à toutes les formations</p>
                        <form method="POST" action="{{ route('student.abonnements.toggleRenew', $actif) }}">
                            @csrf
                            @if ($actif->auto_renew)
                                <span class="badge bg-success me-2"><i class="fe fe-refresh-cw me-1"></i> Renouvellement auto activé</span>
                                <button type="submit" class="btn btn-sm btn-outline-secondary">Désactiver</button>
                            @else
                                <span class="badge bg-secondary me-2">Renouvellement auto désactivé</span>
                                <button type="submit" class="btn btn-sm btn-outline-success">Activer le renouvellement auto</button>
                            @endif
                        </form>
                    </div>
                </div>
            @else
                <div class="card mb-4">
                    <div class="card-body p-4 text-center py-6">
                        <div class="icon-shape icon-lg bg-light-primary text-primary rounded-3 mx-auto mb-3">
                            <i class="fe fe-award fs-3"></i>
                        </div>
                        <h4 class="mb-2">Vous n'avez pas d'abonnement actif</h4>
                        <p class="text-muted mb-4">Abonnez-vous pour accéder à toutes les formations sans limite.</p>
                        <a href="{{ route('pricing') }}" class="btn btn-primary">Voir les formules</a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @if ($historique->isNotEmpty())
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Historique</h4>
            </div>
            <div class="table-responsive">
                <table class="table mb-0 text-nowrap">
                    <thead class="table-light">
                        <tr>
                            <th>Plan</th>
                            <th>Début</th>
                            <th>Fin</th>
                            <th>Prix</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($historique as $abonnement)
                            <tr>
                                <td class="align-middle">{{ $abonnement->plan?->nom }}</td>
                                <td class="align-middle">{{ $abonnement->date_debut?->format('d/m/Y') }}</td>
                                <td class="align-middle">{{ $abonnement->date_fin?->format('d/m/Y') }}</td>
                                <td class="align-middle">{{ number_format($abonnement->prix, 2, ',', ' ') }}</td>
                                <td class="align-middle">
                                    <span class="badge bg-light-{{ $abonnement->statut === 'ACTIF' ? 'success' : 'secondary' }} text-{{ $abonnement->statut === 'ACTIF' ? 'success' : 'secondary' }}">
                                        {{ $abonnement->statut }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endsection
