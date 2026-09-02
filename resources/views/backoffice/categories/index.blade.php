@extends('layouts.dashboard')

@section('title', 'Catégories')
@section('page-title', 'Catégories')
@section('page-subtitle', 'Gestion des catégories de formations')

@section('page-actions')
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
        <i class="fe fe-plus me-1"></i> Nouvelle catégorie
    </a>
@endsection

@section('content')
    <div class="card">
        <div class="table-responsive">
            <table class="table mb-0 text-nowrap">
                <thead class="table-light">
                    <tr>
                        <th>Image</th>
                        <th>Code</th>
                        <th>Nom</th>
                        <th>Parent</th>
                        <th>Sous-catégories</th>
                        <th>Formations</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $c)
                        <tr>
                            <td class="align-middle">
                                @if ($c->image)
                                    <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($c->image) }}"
                                         alt="{{ $c->nom }}" class="rounded" width="40" height="40" style="object-fit: cover;">
                                @else
                                    <span class="avatar avatar-sm rounded-circle bg-light-primary text-primary d-inline-flex align-items-center justify-content-center"
                                          style="width: 40px; height: 40px;">
                                        {{ strtoupper(substr($c->nom, 0, 1)) }}
                                    </span>
                                @endif
                            </td>
                            <td class="align-middle"><code>{{ $c->code }}</code></td>
                            <td class="align-middle">{{ $c->nom }}</td>
                            <td class="align-middle">{{ $c->parent?->nom ?? '—' }}</td>
                            <td class="align-middle">
                                <span class="badge bg-light-info text-info">{{ $c->children_count }}</span>
                            </td>
                            <td class="align-middle">
                                <span class="badge bg-light-primary text-primary">{{ $c->formations_count }}</span>
                            </td>
                            <td class="align-middle text-end">
                                <a href="{{ route('admin.categories.edit', $c) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="fe fe-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.categories.destroy', $c) }}" class="d-inline"
                                      onsubmit="return confirm('Supprimer cette catégorie ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fe fe-trash-2"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center py-4 text-muted">Aucune catégorie trouvée.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $categories->links() }}
        </div>
    </div>
@endsection
