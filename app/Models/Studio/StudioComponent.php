<?php

namespace App\Models\Studio;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Table('studio_components')]
#[Fillable(['page_id', 'type', 'configuration_json', 'ordre'])]
class StudioComponent extends Model
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'configuration_json' => 'array',
        ];
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(StudioPage::class, 'page_id');
    }
}
