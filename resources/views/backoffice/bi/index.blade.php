@extends('layouts.dashboard')

@section('title', 'BI / Analytics')
@section('page-title', 'BI / Analytics')
@section('page-subtitle', 'Indicateurs issus de l\'entrepôt décisionnel')

@section('page-actions')
    <form method="POST" action="{{ route('admin.bi.rebuild') }}">
        @csrf
        <button type="submit" class="btn btn-primary"><i class="fe fe-refresh-cw me-1"></i> Reconstruire</button>
    </form>
@endsection

@section('content')
    <div class="row">
        @php($cards = [
            ['label' => 'Revenu (BI)', 'value' => number_format($totaux['revenu'], 2, ',', ' '), 'icon' => 'fe-dollar-sign', 'bg' => 'success'],
            ['label' => 'Ventes', 'value' => number_format($totaux['ventes'], 0, ',', ' '), 'icon' => 'fe-shopping-cart', 'bg' => 'primary'],
            ['label' => 'Inscriptions', 'value' => number_format($totaux['inscriptions'], 0, ',', ' '), 'icon' => 'fe-user-check', 'bg' => 'info'],
            ['label' => 'Formations terminées', 'value' => number_format($totaux['termines'], 0, ',', ' '), 'icon' => 'fe-check-circle', 'bg' => 'warning'],
        ])
        @foreach ($cards as $card)
            <div class="col-xl-3 col-lg-6 col-md-6 col-12 mb-4">
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

    <div class="row">
        <div class="col-lg-6 col-12 mb-4">
            <div class="card h-100">
                <div class="card-header"><h4 class="mb-0">Top formations — Revenu</h4></div>
                <div class="table-responsive">
                    <table class="table mb-0 text-nowrap">
                        <thead class="table-light"><tr><th>Formation</th><th>Ventes</th><th>Revenu</th></tr></thead>
                        <tbody>
                            @forelse ($topVentes as $vente)
                                <tr>
                                    <td class="align-middle">{{ $dimensions[$vente->formation_id]->titre ?? '—' }}</td>
                                    <td class="align-middle">{{ $vente->nombre_ventes }}</td>
                                    <td class="align-middle">{{ number_format($vente->revenu, 2, ',', ' ') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center py-4 text-muted">Aucune donnée. Cliquez sur « Reconstruire ».</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-12 mb-4">
            <div class="card h-100">
                <div class="card-header"><h4 class="mb-0">Top formations — Inscriptions</h4></div>
                <div class="table-responsive">
                    <table class="table mb-0 text-nowrap">
                        <thead class="table-light"><tr><th>Formation</th><th>Inscrits</th><th>Terminés</th></tr></thead>
                        <tbody>
                            @forelse ($topInscriptions as $insc)
                                <tr>
                                    <td class="align-middle">{{ $dimensions[$insc->formation_id]->titre ?? '—' }}</td>
                                    <td class="align-middle">{{ $insc->nombre_inscriptions }}</td>
                                    <td class="align-middle">{{ $insc->nombre_termines }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center py-4 text-muted">Aucune donnée.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
