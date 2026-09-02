<?php

namespace App\Services;

use App\Models\Catalogue\Formation;
use App\Models\Coupon\Coupon;

class CouponValidator
{
    /**
     * Validate a coupon code for a formation/amount and compute the discount.
     *
     * @return array{valid: bool, coupon: ?Coupon, remise: float, message: ?string}
     */
    public function validate(?string $code, Formation $formation, float $montant): array
    {
        if ($code === null || trim($code) === '') {
            return $this->fail(null, 'Aucun code saisi.');
        }

        $coupon = Coupon::query()->where('code', trim($code))->where('active', true)->first();

        if ($coupon === null) {
            return $this->fail(null, 'Code promo invalide.');
        }

        if ($coupon->date_debut !== null && now()->lt($coupon->date_debut)) {
            return $this->fail($coupon, "Ce code n'est pas encore actif.");
        }

        if ($coupon->date_fin !== null && now()->gt($coupon->date_fin)) {
            return $this->fail($coupon, 'Ce code promo a expiré.');
        }

        if ($coupon->nombre_utilisations !== null && $coupon->utilisations >= $coupon->nombre_utilisations) {
            return $this->fail($coupon, "Ce code promo n'est plus disponible.");
        }

        if ($coupon->montant_minimum !== null && $montant < (float) $coupon->montant_minimum) {
            return $this->fail($coupon, 'Montant minimum de '.number_format((float) $coupon->montant_minimum, 2, ',', ' ').' requis.');
        }

        if ($coupon->formations()->exists() && ! $coupon->formations()->whereKey($formation->id)->exists()) {
            return $this->fail($coupon, "Ce code n'est pas applicable à cette formation.");
        }

        return ['valid' => true, 'coupon' => $coupon, 'remise' => $this->remise($coupon, $montant), 'message' => null];
    }

    /**
     * Record a coupon usage (call once a payment succeeds).
     */
    public function consume(Coupon $coupon): void
    {
        $coupon->increment('utilisations');
    }

    private function remise(Coupon $coupon, float $montant): float
    {
        $remise = $coupon->type_remise === 'POURCENTAGE'
            ? $montant * (float) $coupon->valeur / 100
            : (float) $coupon->valeur;

        return round(min($remise, $montant), 2);
    }

    /**
     * @return array{valid: bool, coupon: ?Coupon, remise: float, message: string}
     */
    private function fail(?Coupon $coupon, string $message): array
    {
        return ['valid' => false, 'coupon' => $coupon, 'remise' => 0.0, 'message' => $message];
    }
}
