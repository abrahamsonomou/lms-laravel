<?php

namespace App\Models\Progression;

use App\Models\Catalogue\Formation;
use App\Models\Cours\Cours;
use App\Models\Cours\Lecon;
use App\Models\Etudiant\Etudiant;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Table('progressions')]
#[Fillable(['etudiant_id', 'formation_id', 'cours_id', 'lecon_id', 'progression', 'temps_consomme', 'date_debut', 'date_derniere_activite', 'terminee', 'date_completion'])]
class Progression extends Model
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'progression' => 'decimal:2',
            'temps_consomme' => 'integer',
            'date_debut' => 'datetime',
            'date_derniere_activite' => 'datetime',
            'terminee' => 'boolean',
            'date_completion' => 'datetime',
        ];
    }

    public function etudiant(): BelongsTo
    {
        return $this->belongsTo(Etudiant::class, 'etudiant_id');
    }

    public function formation(): BelongsTo
    {
        return $this->belongsTo(Formation::class, 'formation_id');
    }

    public function cours(): BelongsTo
    {
        return $this->belongsTo(Cours::class, 'cours_id');
    }

    public function lecon(): BelongsTo
    {
        return $this->belongsTo(Lecon::class, 'lecon_id');
    }
}
