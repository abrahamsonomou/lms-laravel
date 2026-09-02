@extends('layouts.dashboard')

@section('title', 'Paiement')
@section('page-title', 'Paiement')
@section('page-subtitle', 'Finalisez votre inscription à la formation')

@section('content')
    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Moyen de paiement</h4>
                </div>
                <form method="POST" action="{{ route('student.checkout.store', $formation) }}">
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
                    @if (($coupon['valid'] ?? false))
                        <input type="hidden" name="coupon_code" value="{{ $codeCoupon }}">
                    @endif
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a href="{{ route('catalogue.show', $formation) }}" class="btn btn-outline-secondary">Annuler</a>
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fe fe-lock me-1"></i>
                            Payer {{ number_format($total, 2, ',', ' ') }} {{ $formation->devise?->code }}
                        </button>
                    </div>
                </form>
            </div>
            <p class="text-muted small mt-2 mb-0"><i class="fe fe-info me-1"></i> Paiement simulé (démo)</p>
        </div>

        <div class="col-lg-5">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Résumé de la commande</h4>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <span class="text-muted">Formation</span>
                        <span class="fw-semibold text-end">{{ $formation->titre }}</span>
                    </div>

                    {{-- Code promo --}}
                    <form method="GET" action="{{ route('student.checkout.show', $formation) }}" class="mb-3">
                        <label for="coupon" class="form-label">Code promo</label>
                        <div class="input-group">
                            <input type="text" id="coupon" name="coupon" value="{{ $codeCoupon }}" class="form-control" placeholder="EX : BIENVENUE10">
                            <button type="submit" class="btn btn-outline-primary">Appliquer</button>
                        </div>
                        @if ($coupon !== null)
                            @if ($coupon['valid'])
                                <div class="form-text text-success"><i class="fe fe-check me-1"></i> Code appliqué : -{{ number_format($remise, 2, ',', ' ') }} {{ $formation->devise?->code }}</div>
                            @else
                                <div class="form-text text-danger"><i class="fe fe-x me-1"></i> {{ $coupon['message'] }}</div>
                            @endif
                        @endif
                    </form>

                    <hr>
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">Sous-total</span>
                        <span>{{ number_format($prix, 2, ',', ' ') }} {{ $formation->devise?->code }}</span>
                    </div>
                    @if ($remise > 0)
                        <div class="d-flex justify-content-between mb-1 text-success">
                            <span>Remise</span>
                            <span>-{{ number_format($remise, 2, ',', ' ') }} {{ $formation->devise?->code }}</span>
                        </div>
                    @endif
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <span class="h5 mb-0">Total</span>
                        <span class="h4 mb-0 text-success">{{ number_format($total, 2, ',', ' ') }} {{ $formation->devise?->code }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
