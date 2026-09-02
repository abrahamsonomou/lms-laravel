@extends('layouts.dashboard')

@section('title', 'Transactions')
@section('page-title', 'Transactions')
@section('page-subtitle', 'Historique des transactions de paiement')

@section('content')
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <span class="text-muted d-block mb-1">Total réussi</span>
                    <span class="h3 mb-0 text-success">{{ number_format($totalReussi, 2, ',', ' ') }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <form method="GET" action="{{ route('admin.transactions.index') }}" class="row g-2">
                <div class="col-md-4">
                    <input type="search" name="search" value="{{ request('search') }}" class="form-control" placeholder="Rechercher une référence…">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-outline-secondary"><i class="fe fe-search"></i></button>
                </div>
            </form>
        </div>
        <div class="table-responsive">
            <table class="table mb-0 text-nowrap">
                <thead class="table-light">
                    <tr>
                        <th>Référence</th>
                        <th>Utilisateur</th>
                        <th>Moyen</th>
                        <th>Montant</th>
                        <th>Statut</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transactions as $t)
                        <tr>
                            <td class="align-middle"><code>{{ $t->reference }}</code></td>
                            <td class="align-middle">{{ $t->user?->name ?? '—' }}</td>
                            <td class="align-middle">{{ $t->moyenPaiement?->fournisseur?->nom }} — {{ $t->moyenPaiement?->nom }}</td>
                            <td class="align-middle">{{ number_format($t->montant, 2, ',', ' ') }} {{ $t->deviseSource?->code }}</td>
                            <td class="align-middle">
                                @php
                                    $statutClass = match ($t->statut) {
                                        'REUSSI' => 'success',
                                        'ECHOUE' => 'danger',
                                        default => 'warning',
                                    };
                                @endphp
                                <span class="badge bg-light-{{ $statutClass }} text-{{ $statutClass }}">{{ $t->statut }}</span>
                            </td>
                            <td class="align-middle">{{ $t->date_transaction?->format('d/m/Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-4 text-muted">Aucune transaction trouvée.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">{{ $transactions->links() }}</div>
    </div>
@endsection
