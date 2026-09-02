@extends('layouts.dashboard')

@section('title', 'Mes certificats')
@section('page-title', 'Mes certificats')
@section('page-subtitle', 'Vos certificats de réussite')

@section('content')
    <div class="card">
        <div class="table-responsive">
            <table class="table mb-0 text-nowrap">
                <thead class="table-light">
                    <tr>
                        <th>Numéro</th>
                        <th>Formation</th>
                        <th>Mention</th>
                        <th>Score</th>
                        <th>Émis le</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($certificats as $certificat)
                        <tr>
                            <td class="align-middle"><code>{{ $certificat->numero }}</code></td>
                            <td class="align-middle">{{ $certificat->formation?->titre }}</td>
                            <td class="align-middle"><span class="badge bg-light-primary text-primary">{{ $certificat->mention }}</span></td>
                            <td class="align-middle">{{ number_format($certificat->score, 2, ',', ' ') }} / 20</td>
                            <td class="align-middle">{{ $certificat->date_emission?->format('d/m/Y') }}</td>
                            <td class="align-middle text-end">
                                <a href="{{ route('student.certificats.show', $certificat) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fe fe-eye me-1"></i> Voir
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                Vous n'avez pas encore de certificat. Terminez une formation à 100 % pour en obtenir un.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">{{ $certificats->links() }}</div>
    </div>
@endsection
