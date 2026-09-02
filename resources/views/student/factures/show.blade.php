@extends('layouts.dashboard')

@section('title', 'Facture '.$facture->numero)
@section('page-title', 'Facture '.$facture->numero)
@section('page-subtitle', 'Détail de votre facture')

@section('page-actions')
    <div class="d-flex gap-2">
        <a href="{{ route('student.factures.index') }}" class="btn btn-outline-secondary">
            <i class="fe fe-arrow-left me-1"></i> Retour
        </a>
        <button type="button" onclick="window.print()" class="btn btn-primary">
            <i class="fe fe-printer me-1"></i> Imprimer
        </button>
    </div>
@endsection

@section('content')
    @php
        $statutClass = match ($facture->statut) {
            'PAYEE' => 'success',
            'ANNULEE' => 'danger',
            default => 'secondary',
        };
    @endphp

    <div class="card mb-4">
        <div class="card-header d-flex align-items-center justify-content-between">
            <div>
                <h4 class="mb-1">Facture <code>{{ $facture->numero }}</code></h4>
                <p class="mb-0 text-muted">{{ $facture->date_facture?->format('d/m/Y') }}</p>
            </div>
            <span class="badge bg-light-{{ $statutClass }} text-{{ $statutClass }}">{{ $facture->statut }}</span>
        </div>
        <div class="card-body">
            <div class="mb-4">
                <h6 class="text-muted mb-1">Client</h6>
                <p class="mb-0 fw-semibold">{{ $facture->client?->name }}</p>
            </div>

            <div class="table-responsive">
                <table class="table text-nowrap">
                    <thead class="table-light">
                        <tr>
                            <th>Description</th>
                            <th class="text-end">Qté</th>
                            <th class="text-end">Prix unitaire</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($facture->lignes as $ligne)
                            <tr>
                                <td class="align-middle">{{ $ligne->description }}</td>
                                <td class="align-middle text-end">{{ $ligne->quantite }}</td>
                                <td class="align-middle text-end">{{ number_format($ligne->prix_unitaire, 2, ',', ' ') }} {{ $facture->devise?->code }}</td>
                                <td class="align-middle text-end">{{ number_format($ligne->total, 2, ',', ' ') }} {{ $facture->devise?->code }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-end text-muted">Sous-total</td>
                            <td class="text-end">{{ number_format($facture->sous_total, 2, ',', ' ') }} {{ $facture->devise?->code }}</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-end text-muted">Taxe</td>
                            <td class="text-end">{{ number_format($facture->taxe, 2, ',', ' ') }} {{ $facture->devise?->code }}</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-end fw-bold">Total TTC</td>
                            <td class="text-end fw-bold">{{ number_format($facture->total_ttc, 2, ',', ' ') }} {{ $facture->devise?->code }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    @if ($transaction)
        @php
            $txClass = match ($transaction->statut) {
                'REUSSI' => 'success',
                'ECHOUE' => 'danger',
                default => 'warning',
            };
        @endphp
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Paiement</h4>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-1">Référence</h6>
                        <p class="mb-0"><code>{{ $transaction->reference }}</code></p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted mb-1">Moyen de paiement</h6>
                        <p class="mb-0">{{ $transaction->moyenPaiement?->fournisseur?->nom }} — {{ $transaction->moyenPaiement?->nom }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted mb-1">Statut</h6>
                        <span class="badge bg-light-{{ $txClass }} text-{{ $txClass }}">{{ $transaction->statut }}</span>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted mb-1">Date</h6>
                        <p class="mb-0">{{ $transaction->date_transaction?->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
