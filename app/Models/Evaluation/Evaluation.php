<?php

namespace App\Models\Evaluation;

use App\Models\Catalogue\Formation;
use App\Models\Cours\Cours;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Table('evaluations')]
#[Fillable(['formation_id', 'cours_id', 'titre', 'type', 'duree', 'note_max', 'note_min', 'tentatives_max', 'active'])]
class Evaluation extends Model
{
    use SoftDeletes;

    /** @var array<int, string> */
    public const TYPES = ['QUIZ', 'EXAMEN'];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'duree' => 'integer',
            'note_max' => 'decimal:2',
            'note_min' => 'decimal:2',
            'tentatives_max' => 'integer',
            'active' => 'boolean',
        ];
    }

    public function formation(): BelongsTo
    {
        return $this->belongsTo(Formation::class, 'formation_id');
    }

    public function cours(): BelongsTo
    {
        return $this->belongsTo(Cours::class, 'cours_id');
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    public function tentatives(): HasMany
    {
        return $this->hasMany(Tentative::class);
    }
}
