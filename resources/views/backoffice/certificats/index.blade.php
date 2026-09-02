@extends('layouts.dashboard')

@section('title', 'Certificats')
@section('page-title', 'Certificats délivrés')
@section('page-subtitle', 'Tous les certificats émis par la plateforme')

@section('content')
    <div class="card">
        <div class="card-header">
            <form method="GET" action="{{ route('admin.certificats.index') }}" class="row g-2">
                <div class="col-md-4">
                    <input type="search" name="search" value="{{ request('search') }}" class="form-control" placeholder="Rechercher un numéro…">
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
                        <th>Étudiant</th>
                        <th>Formation</th>
                        <th>Mention</th>
                        <th>Score</th>
                        <th>Émis le</th>
                        <th>Statut</th>
                        <th class="text-end">Vérification</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($certificats as $certificat)
                        <tr>
                            <td class="align-middle"><code>{{ $certificat->numero }}</code></td>
                            <td class="align-middle">{{ $certificat->etudiant?->user?->name ?? '—' }}</td>
                            <td class="align-middle">{{ $certificat->formation?->titre ?? '—' }}</td>
                            <td class="align-middle">{{ $certificat->mention }}</td>
                            <td class="align-middle">{{ number_format($certificat->score, 2, ',', ' ') }} / 20</td>
                            <td class="align-middle">{{ $certificat->date_emission?->format('d/m/Y') }}</td>
                            <td class="align-middle">
                                <span class="badge bg-light-success text-success">{{ $certificat->statut }}</span>
                            </td>
                            <td class="align-middle text-end">
                                <a href="{{ route('certificats.verify', $certificat->hash_verification) }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                                    <i class="fe fe-external-link"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center py-4 text-muted">Aucun certificat délivré.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">{{ $certificats->links() }}</div>
    </div>
@endsection
