@extends('layouts.dashboard')

@section('title', 'Rapports')
@section('page-title', 'Rapports')
@section('page-subtitle', 'Analyse de l\'activité commerciale')

@section('page-actions')
    <div class="d-flex gap-2">
        <a href="{{ route('admin.exports.factures') }}" class="btn btn-outline-secondary"><i class="fe fe-download me-1"></i> Factures (CSV)</a>
        <a href="{{ route('admin.exports.inscriptions') }}" class="btn btn-outline-secondary"><i class="fe fe-download me-1"></i> Inscriptions (CSV)</a>
    </div>
@endsection

@section('content')
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.rapports.index') }}" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label for="from" class="form-label">Du</label>
                    <input type="date" id="from" name="from" value="{{ $from }}" class="form-control">
                </div>
                <div class="col-md-3">
                    <label for="to" class="form-label">Au</label>
                    <input type="date" id="to" name="to" value="{{ $to }}" class="form-control">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">Appliquer</button>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        @php($cards = [
            ['label' => 'Chiffre d\'affaires', 'value' => number_format($stats['ca'], 2, ',', ' '), 'icon' => 'fe-dollar-sign', 'bg' => 'success'],
            ['label' => 'Ventes', 'value' => number_format($stats['ventes'], 0, ',', ' '), 'icon' => 'fe-shopping-cart', 'bg' => 'primary'],
            ['label' => 'Panier moyen', 'value' => number_format($stats['panier_moyen'], 2, ',', ' '), 'icon' => 'fe-trending-up', 'bg' => 'info'],
            ['label' => 'Inscriptions', 'value' => number_format($stats['inscriptions'], 0, ',', ' '), 'icon' => 'fe-user-check', 'bg' => 'warning'],
            ['label' => 'Remboursements', 'value' => number_format($stats['remboursements'], 2, ',', ' '), 'icon' => 'fe-corner-up-left', 'bg' => 'danger'],
        ])
        @foreach ($cards as $card)
            <div class="col-xl col-lg-4 col-md-6 col-12 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="icon-shape icon-md bg-light-{{ $card['bg'] }} text-{{ $card['bg'] }} rounded-3 mb-2">
                            <i class="fe {{ $card['icon'] }}"></i>
                        </div>
                        <span class="fs-6 text-uppercase fw-semibold text-muted d-block">{{ $card['label'] }}</span>
                        <h3 class="fw-bold mt-1 mb-0">{{ $card['value'] }}</h3>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="card">
        <div class="card-header"><h4 class="mb-0">Détail par jour</h4></div>
        <div class="table-responsive">
            <table class="table mb-0 text-nowrap">
                <thead class="table-light">
                    <tr><th>Date</th><th>Ventes</th><th>Chiffre d'affaires</th></tr>
                </thead>
                <tbody>
                    @forelse ($parJour as $date => $ligne)
                        <tr>
                            <td class="align-middle">{{ \Illuminate\Support\Carbon::parse($date)->format('d/m/Y') }}</td>
                            <td class="align-middle">{{ $ligne['ventes'] }}</td>
                            <td class="align-middle">{{ number_format($ligne['ca'], 2, ',', ' ') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center py-4 text-muted">Aucune vente sur la période.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
