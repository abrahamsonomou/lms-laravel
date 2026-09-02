@extends('layouts.dashboard')

@section('title', 'Mes formations')
@section('page-title', 'Mes formations')
@section('page-subtitle', 'Gérez le catalogue de vos formations.')

@section('page-actions')
    <a href="{{ route('teacher.formations.create') }}" class="btn btn-primary">
        <i class="fe fe-plus me-1"></i> Nouvelle formation
    </a>
@endsection

@php
    $statutBadges = [
        'BROUILLON' => 'secondary',
        'PUBLIE' => 'success',
        'ARCHIVE' => 'dark',
    ];
@endphp

@section('content')
    <div class="row mb-4">
        <div class="col-lg-6 col-md-8 col-12">
            <form method="GET" action="{{ route('teacher.formations.index') }}">
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="fe fe-search"></i></span>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Rechercher une formation...">
                    <button type="submit" class="btn btn-primary">Rechercher</button>
                </div>
            </form>
        </div>
    </div>

    @if ($formations->isEmpty())
        <div class="card">
            <div class="card-body text-center py-6">
                <i class="fe fe-book fs-1 text-muted mb-3 d-block"></i>
                <p class="mb-3 text-muted">Vous n'avez pas encore créé de formation.</p>
                <a href="{{ route('teacher.formations.create') }}" class="btn btn-primary">
                    <i class="fe fe-plus me-1"></i> Nouvelle formation
                </a>
            </div>
        </div>
    @else
        <div class="row">
            @foreach ($formations as $f)
                <div class="col-md-4 col-12 mb-4">
                    <div class="card h-100">
                        @if ($f->image)
                            <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($f->image) }}" alt="{{ $f->titre }}" class="card-img-top" style="height: 180px; object-fit: cover;">
                        @else
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 180px;">
                                <i class="fe fe-image fs-1 text-muted"></i>
                            </div>
                        @endif
                        <div class="card-body d-flex flex-column">
                            <div class="mb-2">
                                <span class="badge bg-light-{{ $statutBadges[$f->statut] ?? 'secondary' }} text-{{ $statutBadges[$f->statut] ?? 'secondary' }}">{{ $f->statut }}</span>
                                @if ($f->categorie)
                                    <span class="badge bg-light-primary text-primary">{{ $f->categorie->nom }}</span>
                                @endif
                            </div>
                            <h4 class="mb-2">{{ $f->titre }}</h4>
                            <div class="mt-auto pt-2">
                                <h3 class="fw-bold mb-3">{{ number_format($f->prix, 2, ',', ' ') }} {{ $f->devise?->code }}</h3>
                                <div class="d-flex gap-2 flex-wrap">
                                    <a href="{{ route('teacher.formations.cours.index', $f) }}" class="btn btn-sm btn-primary">
                                        <i class="fe fe-layers me-1"></i> Contenu
                                    </a>
                                    <a href="{{ route('teacher.formations.evaluations.index', $f) }}" class="btn btn-sm btn-outline-info">
                                        <i class="fe fe-help-circle me-1"></i> Quiz
                                    </a>
                                    <a href="{{ route('teacher.formations.edit', $f) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fe fe-edit-2 me-1"></i> Éditer
                                    </a>
                                    <form method="POST" action="{{ route('teacher.formations.destroy', $f) }}" onsubmit="return confirm('Supprimer cette formation ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fe fe-trash-2 me-1"></i> Supprimer
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-2">
            {{ $formations->links() }}
        </div>
    @endif
@endsection
