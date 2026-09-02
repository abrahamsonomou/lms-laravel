@extends('layouts.auth')

@section('title', 'Mot de passe oublié')
@section('auth-title', 'Mot de passe oublié')
@section('auth-subtitle', 'Saisissez votre email pour recevoir un lien de réinitialisation.')

@section('content')
    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <div class="mb-3">
            <label for="email" class="form-label">Adresse email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}"
                   class="form-control @error('email') is-invalid @enderror" required autofocus>
            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="d-grid">
            <button type="submit" class="btn btn-primary">Envoyer le lien</button>
        </div>

        <div class="mt-3 text-center">
            <a href="{{ route('login') }}">Retour à la connexion</a>
        </div>
    </form>
@endsection
