<?php

namespace App\Models\Notification;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Table('canaux_notification')]
#[Fillable(['code', 'nom', 'active'])]
class CanalNotification extends Model
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'active' => 'boolean',
        ];
    }

    public function templates(): HasMany
    {
        return $this->hasMany(NotificationTemplate::class, 'canal_id');
    }
}
