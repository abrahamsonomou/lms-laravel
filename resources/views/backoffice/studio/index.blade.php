@extends('layouts.dashboard')

@section('title', 'Studio')
@section('page-title', 'Studio')
@section('page-subtitle', 'Constructeur de pages no-code')

@section('page-actions')
    <a href="{{ route('admin.studio.create') }}" class="btn btn-primary"><i class="fe fe-plus me-1"></i> Nouveau projet</a>
@endsection

@section('content')
    <div class="card">
        <div class="table-responsive">
            <table class="table mb-0 text-nowrap">
                <thead class="table-light">
                    <tr><th>Nom</th><th>Type</th><th>Pages</th><th>Statut</th><th class="text-end">Actions</th></tr>
                </thead>
                <tbody>
                    @forelse ($projects as $project)
                        <tr>
                            <td class="align-middle fw-semibold">{{ $project->nom }}</td>
                            <td class="align-middle"><span class="badge bg-light-primary text-primary">{{ $project->type }}</span></td>
                            <td class="align-middle">{{ $project->pages_count }}</td>
                            <td class="align-middle">
                                <span class="badge bg-light-{{ $project->statut === 'PUBLIE' ? 'success' : 'secondary' }} text-{{ $project->statut === 'PUBLIE' ? 'success' : 'secondary' }}">{{ $project->statut }}</span>
                            </td>
                            <td class="align-middle text-end">
                                <a href="{{ route('admin.studio.edit', $project) }}" class="btn btn-sm btn-outline-primary">Ouvrir</a>
                                <form method="POST" action="{{ route('admin.studio.destroy', $project) }}" class="d-inline" onsubmit="return confirm('Supprimer ce projet ?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fe fe-trash-2"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center py-4 text-muted">Aucun projet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">{{ $projects->links() }}</div>
    </div>
@endsection
