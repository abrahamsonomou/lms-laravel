@extends('layouts.dashboard')

@section('title', 'Remboursements')
@section('page-title', 'Remboursements')
@section('page-subtitle', 'Historique des remboursements effectués')

@section('content')
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <span class="text-muted d-block mb-1">Total remboursé</span>
                    <span class="h3 mb-0 text-warning">{{ number_format($total, 2, ',', ' ') }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table mb-0 text-nowrap">
                <thead class="table-light">
                    <tr>
                        <th>Référence</th>
                        <th>Facture</th>
                        <th>Client</th>
                        <th>Montant</th>
                        <th>Motif</th>
                        <th>Statut</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($remboursements as $r)
                        <tr>
                            <td class="align-middle"><code>{{ $r->reference }}</code></td>
                            <td class="align-middle">{{ $r->transaction?->facture?->numero ?? '—' }}</td>
                            <td class="align-middle">{{ $r->transaction?->facture?->client?->name ?? '—' }}</td>
                            <td class="align-middle">{{ number_format($r->montant, 2, ',', ' ') }} {{ $r->devise?->code }}</td>
                            <td class="align-middle">{{ \Illuminate\Support\Str::limit($r->motif, 40) }}</td>
                            <td class="align-middle"><span class="badge bg-light-success text-success">{{ $r->statut }}</span></td>
                            <td class="align-middle">{{ $r->date_remboursement?->format('d/m/Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center py-4 text-muted">Aucun remboursement.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">{{ $remboursements->links() }}</div>
    </div>
@endsection
