<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('layouts.partials.head')
</head>
<body>
    <header class="navbar-light navbar-default sticky-top">
        <nav class="navbar navbar-expand-lg">
            <div class="container px-3">
                <a class="navbar-brand" href="{{ route('home') }}">
                    <img src="{{ asset('geeks/assets/images/brand/logo/logo.svg') }}" alt="{{ config('app.name') }}" />
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarPublic" aria-controls="navbarPublic" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarPublic">
                    <ul class="navbar-nav ms-auto align-items-lg-center">
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Accueil</a></li>
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('catalogue.*') ? 'active' : '' }}" href="{{ route('catalogue.index') }}">Formations</a></li>
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('pricing') ? 'active' : '' }}" href="{{ route('pricing') }}">Tarifs</a></li>
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">À propos</a></li>
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">Contact</a></li>

                        @auth
                            <li class="nav-item ms-lg-3">
                                <a class="btn btn-primary" href="{{ route(auth()->user()->homeRouteName()) }}">Mon espace</a>
                            </li>
                        @else
                            <li class="nav-item ms-lg-3"><a class="nav-link" href="{{ route('login') }}">Connexion</a></li>
                            <li class="nav-item ms-lg-2"><a class="btn btn-primary" href="{{ route('register') }}">Inscription</a></li>
                        @endauth
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <main>
        @include('layouts.partials.flash')
        @yield('content')
    </main>

    <footer class="bg-dark text-white-50 py-6 mt-8">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h4 class="text-white mb-1">{{ config('app.name', 'LMS') }}</h4>
                    <p class="mb-0">Plateforme d'apprentissage en ligne multi-pays, multi-langue et multi-devise.</p>
                </div>
                <div class="col-md-6 text-md-end mt-3 mt-md-0">
                    <a class="text-white-50 me-3" href="{{ route('about') }}">À propos</a>
                    <a class="text-white-50 me-3" href="{{ route('contact') }}">Contact</a>
                    <a class="text-white-50" href="{{ route('login') }}">Connexion</a>
                    <p class="mb-0 mt-2">&copy; {{ now()->year }} {{ config('app.name', 'LMS') }}. Tous droits réservés.</p>
                </div>
            </div>
        </div>
    </footer>

    @include('layouts.partials.scripts')
</body>
</html>
