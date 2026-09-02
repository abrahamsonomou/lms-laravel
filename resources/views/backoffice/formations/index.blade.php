@extends('layouts.dashboard')

@section('title', 'Formations')
@section('page-title', 'Formations')
@section('page-subtitle', 'Gestion du catalogue de formations')

@section('page-actions')
    <a href="{{ route('admin.formations.create') }}" class="btn btn-primary">
        <i class="fe fe-plus me-1"></i> Nouvelle formation
    </a>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <form method="GET" action="{{ route('admin.formations.index') }}" class="row g-2">
                <div class="col-md-3">
                    <select name="statut" class="form-select">
                        <option value="">Tous les statuts</option>
                        @foreach ($statuts as $s)
                            <option value="{{ $s }}" @selected(request('statut') == $s)>{{ $s }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <input type="search" name="search" value="{{ request('search') }}" class="form-control" placeholder="Rechercher une formation…">
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
                        <th>Image</th>
                        <th>Titre</th>
                        <th>Catégorie</th>
                        <th>Prix</th>
                        <th>Statut</th>
                        <th>Créateur</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($formations as $f)
                        <tr>
                            <td class="align-middle">
                                @if ($f->image)
                                    <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($f->image) }}"
                                         alt="{{ $f->titre }}" class="rounded" width="40" height="40" style="object-fit: cover;">
                                @else
                                    <span class="avatar avatar-sm rounded-circle bg-light-primary text-primary d-inline-flex align-items-center justify-content-center"
                                          style="width: 40px; height: 40px;">
                                        {{ strtoupper(substr($f->titre, 0, 1)) }}
                                    </span>
                                @endif
                            </td>
                            <td class="align-middle">{{ $f->titre }}</td>
                            <td class="align-middle">{{ $f->categorie?->nom ?? '—' }}</td>
                            <td class="align-middle">{{ number_format($f->prix, 2, ',', ' ') }} {{ $f->devise?->code }}</td>
                            <td class="align-middle">
                                @php
                                    $statutClass = match ($f->statut) {
                                        'PUBLIE' => 'bg-light-success text-success',
                                        'ARCHIVE' => 'bg-light-dark text-dark',
                                        default => 'bg-light-secondary text-secondary',
                                    };
                                @endphp
                                <span class="badge {{ $statutClass }}">{{ $f->statut }}</span>
                            </td>
                            <td class="align-middle">{{ $f->createur?->name ?? '—' }}</td>
                            <td class="align-middle text-end">
                                <a href="{{ route('admin.formations.edit', $f) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="fe fe-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.formations.destroy', $f) }}" class="d-inline"
                                      onsubmit="return confirm('Supprimer cette formation ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fe fe-trash-2"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center py-4 text-muted">Aucune formation trouvée.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $formations->links() }}
        </div>
    </div>
@endsection
