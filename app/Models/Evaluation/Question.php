<?php

namespace App\Models\Evaluation;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Table('questions')]
#[Fillable(['evaluation_id', 'type', 'question', 'points', 'ordre', 'explication'])]
class Question extends Model
{
    public const TYPE_QCM = 'QCM';

    public const TYPE_MULTIPLE = 'MULTIPLE';

    public const TYPE_VRAI_FAUX = 'VRAI_FAUX';

    /** @var array<int, string> */
    public const TYPES = [self::TYPE_QCM, self::TYPE_MULTIPLE, self::TYPE_VRAI_FAUX];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'points' => 'decimal:2',
            'ordre' => 'integer',
        ];
    }

    public function evaluation(): BelongsTo
    {
        return $this->belongsTo(Evaluation::class);
    }

    public function reponses(): HasMany
    {
        return $this->hasMany(Reponse::class);
    }
}
