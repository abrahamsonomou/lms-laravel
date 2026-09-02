@extends('layouts.dashboard')

@section('title', 'Établissements')
@section('page-title', 'Établissements')
@section('page-subtitle', 'Gestion des établissements')

@section('page-actions')
    <a href="{{ route('admin.etablissements.create') }}" class="btn btn-primary">
        <i class="fe fe-plus me-1"></i> Nouvel établissement
    </a>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <form method="GET" action="{{ route('admin.etablissements.index') }}" class="row g-2">
                <div class="col-md-4">
                    <select name="organisation_id" class="form-select">
                        <option value="">Toutes les organisations</option>
                        @foreach ($organisations as $organisation)
                            <option value="{{ $organisation->id }}" @selected(request('organisation_id') == $organisation->id)>{{ $organisation->nom }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <input type="search" name="search" value="{{ request('search') }}" class="form-control" placeholder="Rechercher un établissement…">
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
                        <th>Code</th>
                        <th>Nom</th>
                        <th>Organisation</th>
                        <th>Pays</th>
                        <th>Statut</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($etablissements as $e)
                        <tr>
                            <td class="align-middle"><code>{{ $e->code }}</code></td>
                            <td class="align-middle">{{ $e->nom }}</td>
                            <td class="align-middle">{{ $e->organisation?->nom ?? '—' }}</td>
                            <td class="align-middle">{{ $e->pays?->nom ?? '—' }}</td>
                            <td class="align-middle">
                                @if ($e->active)
                                    <span class="badge bg-light-success text-success">Actif</span>
                                @else
                                    <span class="badge bg-light-danger text-danger">Inactif</span>
                                @endif
                            </td>
                            <td class="align-middle text-end">
                                <a href="{{ route('admin.etablissements.edit', $e) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="fe fe-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.etablissements.destroy', $e) }}" class="d-inline"
                                      onsubmit="return confirm('Supprimer cet établissement ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fe fe-trash-2"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-4 text-muted">Aucun établissement trouvé.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $etablissements->links() }}
        </div>
    </div>
@endsection
