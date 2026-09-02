<?php

namespace App\Models\Studio;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Table('studio_pages')]
#[Fillable(['project_id', 'nom', 'slug', 'contenu_json', 'ordre', 'active'])]
class StudioPage extends Model
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'contenu_json' => 'array',
            'active' => 'boolean',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(StudioProject::class, 'project_id');
    }

    public function components(): HasMany
    {
        return $this->hasMany(StudioComponent::class, 'page_id');
    }
}
