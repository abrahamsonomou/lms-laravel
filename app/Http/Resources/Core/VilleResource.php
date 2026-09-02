<?php

namespace App\Http\Resources\Core;

use App\Models\Core\Ville;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Ville
 */
class VilleResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'pays_id' => $this->pays_id,
            'nom' => $this->nom,
            'code' => $this->code,
            'active' => $this->active,
            'pays' => new PaysResource($this->whenLoaded('pays')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
