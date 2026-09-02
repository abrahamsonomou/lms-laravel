@extends('layouts.public')

@section('title', $formation->titre)

@section('content')
    <section class="py-6 bg-dark text-white">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7 col-12">
                    <div class="mb-3">
                        @if ($formation->categorie)
                            <span class="badge bg-light-primary text-primary">{{ $formation->categorie->nom }}</span>
                        @endif
                        <span class="badge bg-light-info text-info">{{ $formation->type }}</span>
                        @if ($formation->niveau)
                            <span class="badge bg-light-warning text-warning">{{ $formation->niveau }}</span>
                        @endif
                    </div>
                    <h1 class="text-white mb-3 fw-bold">{{ $formation->titre }}</h1>
                    <div class="d-flex flex-wrap gap-4 mb-4 text-white-50">
                        @if ($formation->niveau)
                            <span><i class="fe fe-bar-chart-2 me-1"></i> {{ $formation->niveau }}</span>
                        @endif
                        @if ($formation->duree)
                            <span><i class="fe fe-clock me-1"></i> {{ $formation->duree }}</span>
                        @endif
                        @if ($formation->createur)
                            <span><i class="fe fe-user me-1"></i> {{ $formation->createur->name }}</span>
                        @endif
                    </div>
                    <div class="d-flex align-items-center gap-4">
                        <h2 class="text-white mb-0 fw-bold">{{ number_format($formation->prix, 2, ',', ' ') }} {{ $formation->devise?->code }}</h2>
                        @include('public.catalogue._enroll', ['class' => 'btn-lg'])
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-6">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-12">
                    @if ($formation->image)
                        <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($formation->image) }}" alt="{{ $formation->titre }}" class="img-fluid rounded mb-5" style="width: 100%; max-height: 400px; object-fit: cover;">
                    @endif

                    <div class="mb-5">
                        <h3 class="mb-3">Description</h3>
                        <p class="text-muted mb-0">{!! nl2br(e($formation->description)) !!}</p>
                    </div>

                    @if ($formation->objectifs)
                        <div class="mb-5">
                            <h3 class="mb-3">Objectifs</h3>
                            <p class="text-muted mb-0">{!! nl2br(e($formation->objectifs)) !!}</p>
                        </div>
                    @endif

                    <div class="mb-5">
                        <h3 class="mb-3">Programme</h3>
                        @if ($formation->cours->isEmpty())
                            <p class="text-muted mb-0">Le programme de cette formation sera bientôt disponible.</p>
                        @else
                            <div class="list-group">
                                @foreach ($formation->cours as $index => $c)
                                    <div class="list-group-item d-flex align-items-center">
                                        <span class="badge bg-light-primary text-primary me-3">{{ $index + 1 }}</span>
                                        <span>{{ $c->titre }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <div class="col-lg-4 col-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h4 class="mb-3">Informations</h4>
                            <ul class="list-unstyled mb-0">
                                @if ($formation->categorie)
                                    <li class="d-flex justify-content-between py-2 border-bottom">
                                        <span class="text-muted">Catégorie</span>
                                        <span>{{ $formation->categorie->nom }}</span>
                                    </li>
                                @endif
                                @if ($formation->niveau)
                                    <li class="d-flex justify-content-between py-2 border-bottom">
                                        <span class="text-muted">Niveau</span>
                                        <span>{{ $formation->niveau }}</span>
                                    </li>
                                @endif
                                @if ($formation->duree)
                                    <li class="d-flex justify-content-between py-2 border-bottom">
                                        <span class="text-muted">Durée</span>
                                        <span>{{ $formation->duree }}</span>
                                    </li>
                                @endif
                                <li class="d-flex justify-content-between py-2 border-bottom">
                                    <span class="text-muted">Type</span>
                                    <span>{{ $formation->type }}</span>
                                </li>
                                @if ($formation->createur)
                                    <li class="d-flex justify-content-between py-2">
                                        <span class="text-muted">Formateur</span>
                                        <span>{{ $formation->createur->name }}</span>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>

                    <div class="mb-3">
                        @include('public.catalogue._enroll', ['class' => 'w-100'])
                    </div>
                    <a href="{{ route('catalogue.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="fe fe-arrow-left me-1"></i> Retour au catalogue
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
