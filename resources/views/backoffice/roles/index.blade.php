@extends('layouts.dashboard')

@section('title', 'Rôles')
@section('page-title', 'Rôles')
@section('page-subtitle', 'Gestion des rôles et de leurs permissions')

@section('page-actions')
    <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
        <i class="fe fe-plus me-1"></i> Nouveau rôle
    </a>
@endsection

@section('content')
    <div class="card">
        <div class="table-responsive">
            <table class="table mb-0 text-nowrap">
                <thead class="table-light">
                    <tr>
                        <th>Code</th>
                        <th>Nom</th>
                        <th>Permissions</th>
                        <th>Utilisateurs</th>
                        <th>Statut</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($roles as $role)
                        <tr>
                            <td class="align-middle"><code>{{ $role->code }}</code></td>
                            <td class="align-middle">{{ $role->nom }}</td>
                            <td class="align-middle">
                                <span class="badge bg-light-primary text-primary">{{ $role->permissions_count }}</span>
                            </td>
                            <td class="align-middle">
                                <span class="badge bg-light-info text-info">{{ $role->users_count }}</span>
                            </td>
                            <td class="align-middle">
                                @if ($role->active)
                                    <span class="badge bg-light-success text-success">Actif</span>
                                @else
                                    <span class="badge bg-light-danger text-danger">Inactif</span>
                                @endif
                            </td>
                            <td class="align-middle text-end">
                                <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="fe fe-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.roles.destroy', $role) }}" class="d-inline"
                                      onsubmit="return confirm('Supprimer ce rôle ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fe fe-trash-2"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-4 text-muted">Aucun rôle trouvé.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $roles->links() }}
        </div>
    </div>
@endsection
