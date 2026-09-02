<?php

namespace App\Models;

use App\Models\Abonnement\Abonnement;
use App\Models\Core\Langue;
use App\Models\Core\Pays;
use App\Models\Etudiant\Etudiant;
use App\Models\Formateur\Formateur;
use App\Models\Notification\Notification;
use App\Models\Organisation\Organisation;
use App\Models\Rbac\Role;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

#[Fillable(['organisation_id', 'name', 'nom', 'prenom', 'email', 'telephone', 'password', 'avatar', 'langue_id', 'pays_id', 'timezone', 'active'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'telephone_verified_at' => 'datetime',
            'password' => 'hashed',
            'active' => 'boolean',
        ];
    }

    public function organisation(): BelongsTo
    {
        return $this->belongsTo(Organisation::class);
    }

    public function langue(): BelongsTo
    {
        return $this->belongsTo(Langue::class);
    }

    public function pays(): BelongsTo
    {
        return $this->belongsTo(Pays::class);
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    public function etudiant(): HasOne
    {
        return $this->hasOne(Etudiant::class);
    }

    /**
     * In-app LMS notifications (distinct from Laravel's database notifications).
     */
    public function appNotifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'user_id')->latest();
    }

    public function unreadNotificationsCount(): int
    {
        return $this->appNotifications()->where('lu', false)->count();
    }

    /**
     * The user's currently active subscription, if any.
     */
    public function abonnementActif(): ?Abonnement
    {
        return Abonnement::query()
            ->where('statut', 'ACTIF')
            ->where(function ($query): void {
                $query->whereNull('date_fin')->orWhere('date_fin', '>=', now());
            })
            ->whereHas('utilisateurs', fn ($query) => $query->whereKey($this->id))
            ->latest('date_fin')
            ->first();
    }

    public function hasAbonnementActif(): bool
    {
        return $this->abonnementActif() !== null;
    }

    public function formateur(): HasOne
    {
        return $this->hasOne(Formateur::class);
    }

    /**
     * Determine whether the user owns at least one of the given role codes.
     *
     * @param  string|array<int, string>  $codes
     */
    public function hasRole(string|array $codes): bool
    {
        return $this->roles()->whereIn('code', (array) $codes)->exists();
    }

    public function isAdmin(): bool
    {
        return $this->hasRole(['SUPER_ADMIN', 'ADMIN_ORGANISATION', 'ADMIN_ETABLISSEMENT']);
    }

    public function isFormateur(): bool
    {
        return $this->hasRole('FORMATEUR');
    }

    public function isEtudiant(): bool
    {
        return $this->hasRole('ETUDIANT');
    }

    /**
     * Staff able to handle support conversations (admins + support agents).
     */
    public function isStaff(): bool
    {
        return $this->hasRole(['SUPER_ADMIN', 'ADMIN_ORGANISATION', 'ADMIN_ETABLISSEMENT', 'SUPPORT']);
    }

    /**
     * Route name of the landing dashboard for the user's primary role.
     */
    public function homeRouteName(): string
    {
        return match (true) {
            $this->isAdmin() => 'admin.dashboard',
            $this->isFormateur() => 'teacher.dashboard',
            $this->isEtudiant() => 'student.dashboard',
            $this->hasRole('SUPPORT') => 'support.index',
            default => 'home',
        };
    }

    /**
     * Public URL of the user's avatar, or null when none is stored.
     */
    public function avatarUrl(): ?string
    {
        return $this->avatar !== null ? Storage::disk('public')->url($this->avatar) : null;
    }

    /**
     * Uppercase initials used as an avatar fallback.
     */
    public function initials(): string
    {
        $base = trim(($this->prenom ?? '').' '.($this->nom ?? '')) ?: ($this->name ?? 'U');
        $parts = preg_split('/\s+/', $base) ?: [];
        $initials = collect($parts)->take(2)->map(fn (string $part): string => mb_substr($part, 0, 1))->implode('');

        return mb_strtoupper($initials ?: 'U');
    }
}
