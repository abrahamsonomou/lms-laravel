@extends('layouts.public')

@section('title', 'Accueil')

@section('content')
    <section class="bg-light py-8 py-lg-10">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="display-4 fw-bold mb-3">Apprenez sans limites, où que vous soyez</h1>
                    <p class="lead mb-5">
                        {{ config('app.name', 'LMS') }} est la plateforme d'apprentissage en ligne multi-pays,
                        multi-langue et multi-devise qui connecte étudiants et formateurs du monde entier.
                    </p>
                    <div class="d-flex flex-column flex-sm-row justify-content-center gap-2">
                        <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Commencer</a>
                        <a href="{{ route('login') }}" class="btn btn-outline-primary btn-lg">Se connecter</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-6 py-lg-8">
        <div class="container">
            <div class="row mb-5">
                <div class="col-12 text-center">
                    <h2 class="fw-bold mb-1">Formations à la une</h2>
                    <p class="text-muted mb-0">Découvrez une sélection de nos meilleures formations.</p>
                </div>
            </div>

            @if ($formations->isNotEmpty())
                <div class="row">
                    @foreach ($formations as $formation)
                        <div class="col-md-4 col-12 mb-4">
                            <div class="card h-100">
                                <div class="card-body d-flex flex-column">
                                    <h4 class="mb-2"><a href="{{ route('catalogue.show', $formation) }}" class="text-reset">{{ $formation->titre }}</a></h4>
                                    <p class="text-muted flex-grow-1">{{ \Illuminate\Support\Str::limit(strip_tags($formation->description), 120) }}</p>
                                    <div class="d-flex align-items-center justify-content-between mt-3">
                                        <span class="h5 fw-bold text-primary mb-0">{{ number_format($formation->prix, 0, ',', ' ') }} {{ $formation->devise?->code }}</span>
                                        <a href="{{ route('catalogue.show', $formation) }}" class="btn btn-sm btn-outline-primary">Voir</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="text-center mt-4">
                    <a href="{{ route('catalogue.index') }}" class="btn btn-primary">Voir tout le catalogue</a>
                </div>
            @else
                <div class="row">
                    <div class="col-lg-6 mx-auto">
                        <div class="card border-0 bg-light-primary text-center">
                            <div class="card-body py-6">
                                <div class="icon-shape icon-lg bg-light-primary text-primary rounded-3 mx-auto mb-3">
                                    <i class="fe fe-book-open fs-3"></i>
                                </div>
                                <h4 class="mb-1">Bientôt disponible</h4>
                                <p class="text-muted mb-0">De nouvelles formations arrivent très prochainement. Revenez vite !</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <section class="bg-light py-6 py-lg-8">
        <div class="container">
            <div class="row mb-5">
                <div class="col-12 text-center">
                    <h2 class="fw-bold mb-1">Pourquoi nous choisir ?</h2>
                    <p class="text-muted mb-0">Une expérience d'apprentissage pensée pour votre réussite.</p>
                </div>
            </div>
            <div class="row">
                @php($features = [
                    ['icon' => 'fe-globe', 'bg' => 'primary', 'title' => 'Accessible partout', 'text' => 'Multi-pays, multi-langue et multi-devise pour apprendre depuis n\'importe où.'],
                    ['icon' => 'fe-award', 'bg' => 'success', 'title' => 'Formateurs experts', 'text' => 'Des formations conçues et animées par des professionnels qualifiés.'],
                    ['icon' => 'fe-clock', 'bg' => 'info', 'title' => 'À votre rythme', 'text' => 'Suivez vos cours quand vous le souhaitez, sur tous vos appareils.'],
                    ['icon' => 'fe-check-circle', 'bg' => 'warning', 'title' => 'Certifications', 'text' => 'Validez vos acquis et obtenez des certificats reconnus.'],
                ])
                @foreach ($features as $feature)
                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                        <div class="card h-100 border-0">
                            <div class="card-body text-center">
                                <div class="icon-shape icon-lg bg-light-{{ $feature['bg'] }} text-{{ $feature['bg'] }} rounded-3 mx-auto mb-3">
                                    <i class="fe {{ $feature['icon'] }} fs-3"></i>
                                </div>
                                <h5 class="mb-2">{{ $feature['title'] }}</h5>
                                <p class="text-muted mb-0">{{ $feature['text'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection
