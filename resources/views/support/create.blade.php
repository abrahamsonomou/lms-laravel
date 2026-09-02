@extends('layouts.dashboard')

@section('title', 'Nouvelle demande')
@section('page-title', 'Nouvelle demande de support')

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('support.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="titre" class="form-label">Sujet <span class="text-danger">*</span></label>
                            <input type="text" id="titre" name="titre" value="{{ old('titre') }}"
                                   class="form-control @error('titre') is-invalid @enderror" required>
                            @error('titre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Votre message <span class="text-danger">*</span></label>
                            <textarea id="message" name="message" rows="6"
                                      class="form-control @error('message') is-invalid @enderror" required>{{ old('message') }}</textarea>
                            @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Envoyer</button>
                            <a href="{{ route('support.index') }}" class="btn btn-outline-secondary">Annuler</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
