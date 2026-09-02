<?php

namespace App\Models\Studio;

use App\Models\Organisation\Organisation;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Table('studio_projects')]
#[Fillable(['organisation_id', 'type', 'nom', 'description', 'created_by', 'statut'])]
class StudioProject extends Model
{
    public function organisation(): BelongsTo
    {
        return $this->belongsTo(Organisation::class);
    }

    public function pages(): HasMany
    {
        return $this->hasMany(StudioPage::class, 'project_id');
    }
}
