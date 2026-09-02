<?php

namespace App\Models\Formateur;

use App\Models\User;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Table('formateurs')]
#[Fillable(['user_id', 'matricule', 'biographie', 'specialite', 'experience', 'tarif', 'devise_id', 'active'])]
class Formateur extends Model
{
    use SoftDeletes;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tarif' => 'decimal:2',
            'active' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function specialites(): BelongsToMany
    {
        return $this->belongsToMany(Specialite::class, 'formateur_specialites');
    }

    public function disponibilites(): HasMany
    {
        return $this->hasMany(DisponibiliteFormateur::class);
    }
}
