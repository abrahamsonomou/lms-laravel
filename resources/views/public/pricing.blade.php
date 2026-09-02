@extends('layouts.public')

@section('title', 'Tarifs')

@section('content')
    <section class="bg-light py-8 py-lg-10">
        <div class="container">
            <div class="row justify-content-center text-center">
                <div class="col-lg-8">
                    <h1 class="display-4 fw-bold mb-3">Nos formules d'abonnement</h1>
                    <p class="lead mb-0">Choisissez la formule qui vous correspond et accédez à toutes nos formations sans limite.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-6 py-lg-8">
        <div class="container">
            @if ($plans->isEmpty())
                <div class="row justify-content-center">
                    <div class="col-lg-6 text-center">
                        <p class="text-muted mb-0">Aucune formule d'abonnement n'est disponible pour le moment.</p>
                    </div>
                </div>
            @else
                <div class="row justify-content-center g-4">
                    @foreach ($plans as $plan)
                        @php($featured = $plan->type === 'ANNUEL')
                        <div class="col-lg-4 col-md-6">
                            <div class="card h-100 {{ $featured ? 'border-primary border-2 shadow' : '' }}">
                                @if ($featured)
                                    <div class="card-header bg-primary text-white text-center py-2">
                                        <span class="fw-semibold"><i class="fe fe-star me-1"></i> Recommandé</span>
                                    </div>
                                @endif
                                <div class="card-body d-flex flex-column p-5">
                                    <h3 class="fw-bold mb-3">{{ $plan->nom }}</h3>
                                    <div class="mb-3">
                                        <span class="display-4 fw-bold">{{ number_format($plan->prix, 0, ',', ' ') }} {{ $plan->devise?->code }}</span>
                                        <span class="text-muted"> / {{ strtolower($plan->type) }}</span>
                                    </div>
                                    @if ($plan->description)
                                        <p class="text-muted mb-4">{{ $plan->description }}</p>
                                    @endif
                                    <ul class="list-unstyled mb-4">
                                        <li class="mb-2"><i class="fe fe-check text-success me-2"></i> Accès illimité à toutes les formations</li>
                                        <li class="mb-2"><i class="fe fe-check text-success me-2"></i> Certificats de réussite</li>
                                        <li class="mb-2"><i class="fe fe-check text-success me-2"></i> Support prioritaire</li>
                                    </ul>
                                    <div class="mt-auto">
                                        @auth
                                            @if (auth()->user()->isEtudiant())
                                                <a href="{{ route('student.abonnements.checkout', $plan) }}" class="btn {{ $featured ? 'btn-primary' : 'btn-outline-primary' }} w-100">S'abonner</a>
                                            @else
                                                <a href="{{ route(auth()->user()->homeRouteName()) }}" class="btn btn-outline-secondary w-100">Mon espace</a>
                                            @endif
                                        @else
                                            <a href="{{ route('register') }}" class="btn {{ $featured ? 'btn-primary' : 'btn-outline-primary' }} w-100">Créer un compte</a>
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
@endsection
