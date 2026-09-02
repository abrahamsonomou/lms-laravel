<?php

namespace App\Models\Notification;

use App\Models\Core\Langue;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Table('notification_templates')]
#[Fillable(['code', 'nom', 'canal_id', 'langue_id', 'sujet', 'contenu', 'active'])]
class NotificationTemplate extends Model
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

    public function canal(): BelongsTo
    {
        return $this->belongsTo(CanalNotification::class, 'canal_id');
    }

    public function langue(): BelongsTo
    {
        return $this->belongsTo(Langue::class, 'langue_id');
    }
}
