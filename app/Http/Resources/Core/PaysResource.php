<?php

namespace App\Http\Resources\Core;

use App\Models\Core\Pays;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Pays
 */
class PaysResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'iso2' => $this->iso2,
            'iso3' => $this->iso3,
            'nom' => $this->nom,
            'indicatif_telephone' => $this->indicatif_telephone,
            'active' => $this->active,
            'villes' => VilleResource::collection($this->whenLoaded('villes')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
