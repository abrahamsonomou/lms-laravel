@extends('layouts.auth')

@section('title', 'Vérification email')
@section('auth-title', 'Vérifiez votre email')
@section('auth-subtitle', 'Un lien de confirmation a été envoyé à votre adresse email.')

@section('content')
    <p class="text-muted">
        Merci de votre inscription ! Avant de commencer, cliquez sur le lien que nous venons de vous envoyer par email.
        Si vous ne l'avez pas reçu, nous pouvons vous en renvoyer un.
    </p>

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <div class="d-grid mb-3">
            <button type="submit" class="btn btn-primary">Renvoyer le lien de vérification</button>
        </div>
    </form>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <div class="text-center">
            <button type="submit" class="btn btn-link p-0">Se déconnecter</button>
        </div>
    </form>
@endsection
