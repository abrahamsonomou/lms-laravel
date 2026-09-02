@extends('layouts.dashboard')

@section('title', 'Factures')
@section('page-title', 'Factures')
@section('page-subtitle', 'Toutes les factures émises par la plateforme')

@section('content')
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <span class="text-muted d-block mb-1">Total encaissé</span>
                    <span class="h3 mb-0 text-success">{{ number_format($totalPaye, 2, ',', ' ') }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <form method="GET" action="{{ route('admin.factures.index') }}" class="row g-2">
                <div class="col-md-4">
                    <input type="search" name="search" value="{{ request('search') }}" class="form-control" placeholder="Rechercher un numéro…">
                </div>
                <div class="col-md-3">
                    <select name="statut" class="form-select">
                        <option value="">Tous les statuts</option>
                        <option value="BROUILLON" {{ request('statut') === 'BROUILLON' ? 'selected' : '' }}>Brouillon</option>
                        <option value="PAYEE" {{ request('statut') === 'PAYEE' ? 'selected' : '' }}>Payée</option>
                        <option value="REMBOURSEE" {{ request('statut') === 'REMBOURSEE' ? 'selected' : '' }}>Remboursée</option>
                        <option value="ANNULEE" {{ request('statut') === 'ANNULEE' ? 'selected' : '' }}>Annulée</option>
                    </select>
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
                        <th>Numéro</th>
                        <th>Client</th>
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
                            <td class="align-middle">{{ $f->client?->name ?? '—' }}</td>
                            <td class="align-middle">{{ $f->date_facture?->format('d/m/Y') }}</td>
                            <td class="align-middle">{{ number_format($f->total_ttc, 2, ',', ' ') }} {{ $f->devise?->code }}</td>
                            <td class="align-middle">
                                @php
                                    $statutClass = match ($f->statut) {
                                        'PAYEE' => 'success',
                                        'REMBOURSEE' => 'warning',
                                        'ANNULEE' => 'danger',
                                        default => 'secondary',
                                    };
                                @endphp
                                <span class="badge bg-light-{{ $statutClass }} text-{{ $statutClass }}">{{ $f->statut }}</span>
                            </td>
                            <td class="align-middle text-end">
                                @if ($f->statut === 'PAYEE')
                                    <form method="POST" action="{{ route('admin.factures.refund', $f) }}"
                                          onsubmit="this.motif.value = prompt('Motif du remboursement ?') || ''; return this.motif.value.trim() !== '';">
                                        @csrf
                                        <input type="hidden" name="motif">
                                        <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fe fe-corner-up-left me-1"></i> Rembourser</button>
                                    </form>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-4 text-muted">Aucune facture trouvée.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">{{ $factures->links() }}</div>
    </div>
@endsection
