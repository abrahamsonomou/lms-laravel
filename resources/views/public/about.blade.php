@extends('layouts.public')

@section('title', 'À propos')

@section('content')
    <section class="bg-light py-8 py-lg-10">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="display-5 fw-bold mb-3">À propos de {{ config('app.name', 'LMS') }}</h1>
                    <p class="lead mb-0">
                        Une plateforme d'apprentissage en ligne conçue pour rendre l'éducation accessible
                        à tous, partout dans le monde.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-6 py-lg-8">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <h2 class="fw-bold mb-3">Notre mission</h2>
                    <p class="text-muted">
                        Nous croyons que chacun devrait pouvoir apprendre sans barrière géographique,
                        linguistique ou financière. Notre plateforme réunit étudiants et formateurs
                        autour d'un objectif commun : le partage des connaissances.
                    </p>
                    <p class="text-muted mb-0">
                        Grâce à une architecture pensée pour l'international, nous accompagnons la montée
                        en compétences des apprenants dans un environnement moderne, flexible et sécurisé.
                    </p>
                </div>
                <div class="col-lg-6">
                    <div class="row">
                        @php($values = [
                            ['icon' => 'fe-globe', 'bg' => 'primary', 'title' => 'Multi-pays', 'text' => 'Une plateforme ouverte aux apprenants et formateurs du monde entier.'],
                            ['icon' => 'fe-message-circle', 'bg' => 'success', 'title' => 'Multi-langue', 'text' => 'Des contenus disponibles dans plusieurs langues pour tous les publics.'],
                            ['icon' => 'fe-dollar-sign', 'bg' => 'info', 'title' => 'Multi-devise', 'text' => 'Des paiements adaptés à la devise de chaque région.'],
                            ['icon' => 'fe-shield', 'bg' => 'warning', 'title' => 'Sécurisé', 'text' => 'Vos données et transactions sont protégées à chaque étape.'],
                        ])
                        @foreach ($values as $value)
                            <div class="col-sm-6 mb-4">
                                <div class="card h-100 border-0">
                                    <div class="card-body">
                                        <div class="icon-shape icon-lg bg-light-{{ $value['bg'] }} text-{{ $value['bg'] }} rounded-3 mb-3">
                                            <i class="fe {{ $value['icon'] }} fs-3"></i>
                                        </div>
                                        <h5 class="mb-2">{{ $value['title'] }}</h5>
                                        <p class="text-muted mb-0">{{ $value['text'] }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-light py-6 py-lg-8">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h2 class="fw-bold mb-3">Prêt à nous rejoindre ?</h2>
                    <p class="text-muted mb-4">Créez votre compte gratuitement et commencez à apprendre dès aujourd'hui.</p>
                    <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Commencer maintenant</a>
                </div>
            </div>
        </div>
    </section>
@endsection
