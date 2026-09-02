<?php

namespace App\Models\Catalogue;

use App\Models\Core\Devise;
use App\Models\Cours\Cours;
use App\Models\Evaluation\Evaluation;
use App\Models\Organisation\Organisation;
use App\Models\User;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Table('formations')]
#[Fillable(['organisation_id', 'categorie_id', 'code', 'titre', 'slug', 'description', 'objectifs', 'niveau', 'duree', 'image', 'prix', 'devise_id', 'type', 'statut', 'date_publication', 'date_expiration', 'created_by', 'updated_by'])]
class Formation extends Model
{
    use SoftDeletes;

    public const STATUT_BROUILLON = 'BROUILLON';

    public const STATUT_PUBLIE = 'PUBLIE';

    public const STATUT_ARCHIVE = 'ARCHIVE';

    /** @var array<int, string> */
    public const STATUTS = [self::STATUT_BROUILLON, self::STATUT_PUBLIE, self::STATUT_ARCHIVE];

    /** @var array<int, string> */
    public const TYPES = ['GRATUITE', 'PAYANTE', 'ABONNEMENT'];

    /** @var array<int, string> */
    public const NIVEAUX = ['DEBUTANT', 'INTERMEDIAIRE', 'AVANCE'];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'prix' => 'decimal:2',
            'date_publication' => 'datetime',
            'date_expiration' => 'datetime',
        ];
    }

    /**
     * Only formations currently published and not expired.
     */
    public function scopePublie(Builder $query): Builder
    {
        return $query->where('statut', self::STATUT_PUBLIE)
            ->where(function (Builder $q): void {
                $q->whereNull('date_expiration')->orWhere('date_expiration', '>=', now());
            });
    }

    public function isPublie(): bool
    {
        return $this->statut === self::STATUT_PUBLIE;
    }

    public function estPayante(): bool
    {
        return $this->type !== 'GRATUITE' && (float) $this->prix > 0;
    }

    public function createur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function organisation(): BelongsTo
    {
        return $this->belongsTo(Organisation::class, 'organisation_id');
    }

    public function categorie(): BelongsTo
    {
        return $this->belongsTo(CategorieFormation::class, 'categorie_id');
    }

    public function devise(): BelongsTo
    {
        return $this->belongsTo(Devise::class, 'devise_id');
    }

    public function cours(): HasMany
    {
        return $this->hasMany(Cours::class, 'formation_id');
    }

    public function evaluations(): HasMany
    {
        return $this->hasMany(Evaluation::class, 'formation_id');
    }

    public function traductions(): HasMany
    {
        return $this->hasMany(FormationTraduction::class, 'formation_id');
    }
}
