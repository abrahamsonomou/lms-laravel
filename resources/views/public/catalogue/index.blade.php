@extends('layouts.public')

@section('title', 'Catalogue')

@section('content')
    <section class="py-6 bg-light">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7 col-md-8 col-12">
                    <h1 class="mb-2 fw-bold">Notre catalogue de formations</h1>
                    <p class="mb-0 text-muted">Découvrez nos formations et développez vos compétences.</p>
                </div>
                <div class="col-lg-5 col-md-4 col-12 mt-3 mt-md-0">
                    <form method="GET" action="{{ route('catalogue.index') }}">
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="fe fe-search"></i></span>
                            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Rechercher une formation...">
                            @if (request('categorie'))
                                <input type="hidden" name="categorie" value="{{ request('categorie') }}">
                            @endif
                            <button type="submit" class="btn btn-primary">Rechercher</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <section class="py-6">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-12 mb-4 mb-lg-0">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="mb-0">Catégories</h4>
                        </div>
                        <div class="list-group list-group-flush">
                            <a href="{{ route('catalogue.index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ ! request('categorie') ? 'active' : '' }}">
                                Toutes
                            </a>
                            @foreach ($categories as $c)
                                <a href="{{ route('catalogue.index', ['categorie' => $c->code]) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ request('categorie') === $c->code ? 'active' : '' }}">
                                    {{ $c->nom }}
                                    <span class="badge bg-light-primary text-primary rounded-pill">{{ $c->formations_count }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="col-lg-9 col-12">
                    @if ($formations->isEmpty())
                        <div class="card">
                            <div class="card-body text-center py-6">
                                <i class="fe fe-search fs-1 text-muted mb-3 d-block"></i>
                                <p class="mb-0 text-muted">Aucune formation ne correspond à votre recherche.</p>
                            </div>
                        </div>
                    @else
                        <div class="row">
                            @foreach ($formations as $f)
                                <div class="col-md-6 col-12 mb-4">
                                    <a href="{{ route('catalogue.show', $f) }}" class="text-reset text-decoration-none">
                                        <div class="card h-100 card-hover">
                                            @if ($f->image)
                                                <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($f->image) }}" alt="{{ $f->titre }}" class="card-img-top" style="height: 180px; object-fit: cover;">
                                            @else
                                                <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 180px;">
                                                    <i class="fe fe-image fs-1 text-muted"></i>
                                                </div>
                                            @endif
                                            <div class="card-body d-flex flex-column">
                                                <div class="mb-2">
                                                    @if ($f->categorie)
                                                        <span class="badge bg-light-primary text-primary">{{ $f->categorie->nom }}</span>
                                                    @endif
                                                    <span class="badge bg-light-info text-info">{{ $f->type }}</span>
                                                </div>
                                                <h4 class="mb-2">{{ $f->titre }}</h4>
                                                <p class="text-muted mb-3">{{ \Illuminate\Support\Str::limit(strip_tags($f->description), 100) }}</p>
                                                <div class="mt-auto">
                                                    <h4 class="fw-bold mb-0">{{ number_format($f->prix, 2, ',', ' ') }} {{ $f->devise?->code }}</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-2">
                            {{ $formations->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
