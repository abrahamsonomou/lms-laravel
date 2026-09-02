@extends('layouts.dashboard')

@section('title', 'Abonnement')
@section('page-title', 'Abonnement')
@section('page-subtitle', 'Finalisez votre abonnement')

@section('content')
    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Moyen de paiement</h4>
                </div>
                <form method="POST" action="{{ route('student.abonnements.subscribe', $plan) }}">
                    @csrf
                    <div class="card-body">
                        @error('moyen_paiement_id')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror

                        @forelse ($moyens as $m)
                            <label class="form-check card mb-2 p-3 flex-row align-items-center">
                                <input class="form-check-input mt-0 me-3" type="radio" name="moyen_paiement_id"
                                       value="{{ $m->id }}" {{ old('moyen_paiement_id') == $m->id ? 'checked' : '' }} required>
                                <span>
                                    <span class="fw-semibold d-block">{{ $m->fournisseur?->nom }} — {{ $m->nom }}</span>
                                </span>
                            </label>
                        @empty
                            <p class="text-muted mb-0">Aucun moyen de paiement disponible pour le moment.</p>
                        @endforelse
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a href="{{ route('pricing') }}" class="btn btn-outline-secondary">Annuler</a>
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fe fe-lock me-1"></i>
                            Payer et activer
                        </button>
                    </div>
                </form>
            </div>
            <p class="text-muted small mt-2 mb-0"><i class="fe fe-info me-1"></i> Paiement simulé (démo)</p>
        </div>

        <div class="col-lg-5">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Récapitulatif</h4>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <span class="text-muted">Formule</span>
                        <span class="fw-semibold text-end">{{ $plan->nom }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <span class="text-muted">Durée</span>
                        <span class="text-end">{{ $plan->dureeEnJours() }} jours</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <span class="h5 mb-0">Total</span>
                        <span class="h4 mb-0 text-success">{{ number_format($plan->prix, 2, ',', ' ') }} {{ $plan->devise?->code }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
