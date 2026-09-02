@extends('layouts.dashboard')

@section('title', 'Tableau de bord')
@section('page-title', 'Tableau de bord')
@section('page-subtitle', 'Vue d\'ensemble de la plateforme')

@section('content')
    <div class="row">
        @php($cards = [
            ['label' => 'Utilisateurs', 'value' => number_format($stats['utilisateurs'], 0, ',', ' '), 'icon' => 'fe-users', 'bg' => 'primary'],
            ['label' => 'Étudiants', 'value' => number_format($stats['etudiants'], 0, ',', ' '), 'icon' => 'fe-book-open', 'bg' => 'info'],
            ['label' => 'Formateurs', 'value' => number_format($stats['formateurs'], 0, ',', ' '), 'icon' => 'fe-award', 'bg' => 'success'],
            ['label' => 'Formations', 'value' => number_format($stats['formations'], 0, ',', ' '), 'icon' => 'fe-book', 'bg' => 'warning'],
            ['label' => 'Inscriptions', 'value' => number_format($stats['inscriptions'], 0, ',', ' '), 'icon' => 'fe-user-check', 'bg' => 'primary'],
            ['label' => 'Certificats', 'value' => number_format($stats['certificats'], 0, ',', ' '), 'icon' => 'fe-award', 'bg' => 'info'],
            ['label' => 'Ventes', 'value' => number_format($stats['ventes'], 0, ',', ' '), 'icon' => 'fe-shopping-cart', 'bg' => 'success'],
            ['label' => 'Chiffre d\'affaires', 'value' => number_format($stats['ca'], 2, ',', ' '), 'icon' => 'fe-dollar-sign', 'bg' => 'warning'],
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
        <div class="col-lg-6 col-12 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Derniers utilisateurs</h4>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-primary">Voir tout</a>
                </div>
                <div class="table-responsive">
                    <table class="table mb-0 text-nowrap">
                        <thead class="table-light">
                            <tr><th>Nom</th><th>Email</th><th>Rôles</th></tr>
                        </thead>
                        <tbody>
                            @forelse ($derniersUtilisateurs as $utilisateur)
                                <tr>
                                    <td class="align-middle">{{ $utilisateur->name }}</td>
                                    <td class="align-middle">{{ $utilisateur->email }}</td>
                                    <td class="align-middle">
                                        @forelse ($utilisateur->roles as $role)
                                            <span class="badge bg-light-primary text-primary">{{ $role->nom }}</span>
                                        @empty
                                            <span class="text-muted">—</span>
                                        @endforelse
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center py-4 text-muted">Aucun utilisateur.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-12 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Dernières transactions</h4>
                    <a href="{{ route('admin.transactions.index') }}" class="btn btn-sm btn-outline-primary">Voir tout</a>
                </div>
                <div class="table-responsive">
                    <table class="table mb-0 text-nowrap">
                        <thead class="table-light">
                            <tr><th>Référence</th><th>Client</th><th>Montant</th></tr>
                        </thead>
                        <tbody>
                            @forelse ($dernieresTransactions as $transaction)
                                <tr>
                                    <td class="align-middle"><code>{{ $transaction->reference }}</code></td>
                                    <td class="align-middle">{{ $transaction->user?->name ?? '—' }}</td>
                                    <td class="align-middle">{{ number_format($transaction->montant, 2, ',', ' ') }} {{ $transaction->deviseSource?->code }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center py-4 text-muted">Aucune transaction.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
