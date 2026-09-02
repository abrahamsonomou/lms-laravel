<?php

namespace App\Http\Resources\Core;

use App\Models\Core\TauxChange;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin TauxChange
 */
class TauxChangeResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'devise_source_id' => $this->devise_source_id,
            'devise_cible_id' => $this->devise_cible_id,
            'taux' => $this->taux,
            'date_effet' => $this->date_effet,
            'date_fin' => $this->date_fin,
            'source' => $this->source,
            'active' => $this->active,
            'devise_source' => new DeviseResource($this->whenLoaded('deviseSource')),
            'devise_cible' => new DeviseResource($this->whenLoaded('deviseCible')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
