@extends('layouts.dashboard')

@section('title', 'Organisations')
@section('page-title', 'Organisations')
@section('page-subtitle', 'Gestion des organisations de la plateforme')

@section('page-actions')
    <a href="{{ route('admin.organisations.create') }}" class="btn btn-primary">
        <i class="fe fe-plus me-1"></i> Nouvelle organisation
    </a>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <form method="GET" action="{{ route('admin.organisations.index') }}" class="row g-2">
                <div class="col-md-4">
                    <input type="search" name="search" value="{{ request('search') }}" class="form-control" placeholder="Rechercher une organisation…">
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
                        <th>Logo</th>
                        <th>Code</th>
                        <th>Nom</th>
                        <th>Pays</th>
                        <th>Devise</th>
                        <th>Établissements</th>
                        <th>Statut</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($organisations as $organisation)
                        <tr>
                            <td class="align-middle">
                                @if ($organisation->logo)
                                    <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($organisation->logo) }}"
                                         alt="{{ $organisation->nom }}" class="rounded" width="40" height="40" style="object-fit: cover;">
                                @else
                                    <span class="avatar avatar-sm rounded-circle bg-light-primary text-primary d-inline-flex align-items-center justify-content-center"
                                          style="width: 40px; height: 40px;">
                                        {{ strtoupper(substr($organisation->nom, 0, 1)) }}
                                    </span>
                                @endif
                            </td>
                            <td class="align-middle"><code>{{ $organisation->code }}</code></td>
                            <td class="align-middle">{{ $organisation->nom }}</td>
                            <td class="align-middle">{{ $organisation->pays?->nom ?? '—' }}</td>
                            <td class="align-middle">{{ $organisation->devise?->code ?? '—' }}</td>
                            <td class="align-middle">
                                <span class="badge bg-light-info text-info">{{ $organisation->etablissements_count }}</span>
                            </td>
                            <td class="align-middle">
                                @if ($organisation->active)
                                    <span class="badge bg-light-success text-success">Actif</span>
                                @else
                                    <span class="badge bg-light-danger text-danger">Inactif</span>
                                @endif
                            </td>
                            <td class="align-middle text-end">
                                <a href="{{ route('admin.organisations.edit', $organisation) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="fe fe-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.organisations.destroy', $organisation) }}" class="d-inline"
                                      onsubmit="return confirm('Supprimer cette organisation ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fe fe-trash-2"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center py-4 text-muted">Aucune organisation trouvée.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $organisations->links() }}
        </div>
    </div>
@endsection
