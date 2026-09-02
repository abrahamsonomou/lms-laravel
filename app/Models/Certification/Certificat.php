<?php

namespace App\Models\Certification;

use App\Models\Catalogue\Formation;
use App\Models\Etudiant\Etudiant;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Table('certificats')]
#[Fillable(['etudiant_id', 'formation_id', 'numero', 'date_emission', 'date_expiration', 'score', 'mention', 'fichier', 'hash_verification', 'statut'])]
class Certificat extends Model
{
    use SoftDeletes;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date_emission' => 'date',
            'date_expiration' => 'date',
            'score' => 'decimal:2',
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
}
