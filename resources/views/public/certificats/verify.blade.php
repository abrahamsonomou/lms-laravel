@extends('layouts.public')

@section('title', 'Vérification de certificat')

@section('content')
    <section class="py-8">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-7">
                    @if ($certificat)
                        <div class="card border-success shadow-sm">
                            <div class="card-body p-5 text-center">
                                <div class="icon-shape icon-lg bg-light-success text-success rounded-circle mx-auto mb-3">
                                    <i class="fe fe-check-circle fs-2"></i>
                                </div>
                                <h2 class="fw-bold mb-1">Certificat authentique</h2>
                                <p class="text-muted mb-4">Ce certificat a bien été délivré par {{ config('app.name', 'LMS') }}.</p>

                                <div class="row text-start g-3">
                                    <div class="col-sm-6"><small class="text-muted d-block">Titulaire</small><strong>{{ $certificat->etudiant?->user?->name }}</strong></div>
                                    <div class="col-sm-6"><small class="text-muted d-block">Formation</small><strong>{{ $certificat->formation?->titre }}</strong></div>
                                    <div class="col-sm-6"><small class="text-muted d-block">Numéro</small><strong>{{ $certificat->numero }}</strong></div>
                                    <div class="col-sm-6"><small class="text-muted d-block">Mention</small><strong>{{ $certificat->mention }}</strong></div>
                                    <div class="col-sm-6"><small class="text-muted d-block">Score</small><strong>{{ number_format($certificat->score, 2, ',', ' ') }} / 20</strong></div>
                                    <div class="col-sm-6"><small class="text-muted d-block">Émis le</small><strong>{{ $certificat->date_emission?->format('d/m/Y') }}</strong></div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="card border-danger shadow-sm">
                            <div class="card-body p-5 text-center">
                                <div class="icon-shape icon-lg bg-light-danger text-danger rounded-circle mx-auto mb-3">
                                    <i class="fe fe-x-circle fs-2"></i>
                                </div>
                                <h2 class="fw-bold mb-1">Certificat introuvable</h2>
                                <p class="text-muted mb-0">Aucun certificat ne correspond à ce code de vérification.</p>
                            </div>
                        </div>
                    @endif

                    <div class="text-center mt-4">
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary">Retour à l'accueil</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
