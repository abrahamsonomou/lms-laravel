@extends('layouts.dashboard')

@section('title', "Plans d'abonnement")
@section('page-title', "Plans d'abonnement")

@section('page-actions')
    <a href="{{ route('admin.plans.create') }}" class="btn btn-primary"><i class="fe fe-plus me-1"></i> Nouveau plan</a>
@endsection

@section('content')
    <div class="card">
        <div class="table-responsive">
            <table class="table mb-0 text-nowrap">
                <thead class="table-light">
                    <tr>
                        <th>Nom</th>
                        <th>Code</th>
                        <th>Prix</th>
                        <th>Type</th>
                        <th>Durée</th>
                        <th>Abonnements</th>
                        <th>Statut</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($plans as $p)
                        <tr>
                            <td class="align-middle">{{ $p->nom }}</td>
                            <td class="align-middle"><code>{{ $p->code }}</code></td>
                            <td class="align-middle">{{ number_format($p->prix, 2, ',', ' ') }} {{ $p->devise?->code }}</td>
                            <td class="align-middle">
                                <span class="badge bg-light-primary text-primary">{{ $p->type }}</span>
                            </td>
                            <td class="align-middle">{{ $p->dureeEnJours() }} j</td>
                            <td class="align-middle">{{ $p->abonnements_count }}</td>
                            <td class="align-middle">
                                <span class="badge bg-light-{{ $p->active ? 'success' : 'danger' }} text-{{ $p->active ? 'success' : 'danger' }}">
                                    {{ $p->active ? 'Actif' : 'Inactif' }}
                                </span>
                            </td>
                            <td class="align-middle text-end">
                                <a href="{{ route('admin.plans.edit', $p) }}" class="btn btn-sm btn-outline-secondary"><i class="fe fe-edit"></i></a>
                                <form method="POST" action="{{ route('admin.plans.destroy', $p) }}" class="d-inline" onsubmit="return confirm('Supprimer ce plan ?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fe fe-trash-2"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center py-4 text-muted">Aucun plan d'abonnement.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">{{ $plans->links() }}</div>
    </div>
@endsection
