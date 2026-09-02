<?php

namespace App\Http\Resources\Core;

use App\Models\Core\Devise;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Devise
 */
class DeviseResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'symbole' => $this->symbole,
            'nom' => $this->nom,
            'nombre_decimales' => $this->nombre_decimales,
            'active' => $this->active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
