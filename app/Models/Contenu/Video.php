<?php

namespace App\Models\Contenu;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Table('videos')]
#[Fillable(['contenu_id', 'provider', 'video_id', 'url', 'duree', 'resolution', 'thumbnail'])]
class Video extends Model
{
    public function contenu(): BelongsTo
    {
        return $this->belongsTo(Contenu::class, 'contenu_id');
    }
}
