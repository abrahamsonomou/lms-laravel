@extends('layouts.dashboard')

@section('title', 'Mes factures')
@section('page-title', 'Mes factures')
@section('page-subtitle', 'Historique de vos achats et paiements')

@section('content')
    <div class="card">
        <div class="table-responsive">
            <table class="table mb-0 text-nowrap">
                <thead class="table-light">
                    <tr>
                        <th>Numéro</th>
                        <th>Date</th>
                        <th>Total</th>
                        <th>Statut</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($factures as $f)
                        <tr>
                            <td class="align-middle"><code>{{ $f->numero }}</code></td>
                            <td class="align-middle">{{ $f->date_facture?->format('d/m/Y') }}</td>
                            <td class="align-middle">{{ number_format($f->total_ttc, 2, ',', ' ') }} {{ $f->devise?->code }}</td>
                            <td class="align-middle">
                                @php
                                    $statutClass = match ($f->statut) {
                                        'PAYEE' => 'success',
                                        'ANNULEE' => 'danger',
                                        default => 'secondary',
                                    };
                                @endphp
                                <span class="badge bg-light-{{ $statutClass }} text-{{ $statutClass }}">{{ $f->statut }}</span>
                            </td>
                            <td class="align-middle text-end">
                                <a href="{{ route('student.factures.show', $f) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fe fe-eye me-1"></i> Voir
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                Vous n'avez aucune facture pour le moment.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">{{ $factures->links() }}</div>
    </div>
@endsection
