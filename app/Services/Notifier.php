<?php

namespace App\Services;

use App\Mail\EvenementMail;
use App\Models\Notification\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class Notifier
{
    /**
     * Create an in-app notification for a user, and optionally email them.
     *
     * @param  array<string, mixed>  $data
     */
    public function notify(
        User $user,
        string $type,
        string $titre,
        string $message,
        array $data = [],
        bool $email = true,
        ?string $actionUrl = null,
        ?string $actionText = null,
    ): Notification {
        $notification = Notification::query()->create([
            'user_id' => $user->id,
            'type' => $type,
            'titre' => $titre,
            'message' => $message,
            'data' => $data ?: null,
            'lu' => false,
        ]);

        if ($email && $user->email !== null) {
            Mail::to($user->email)->send(new EvenementMail(
                sujet: $titre,
                titre: $titre,
                intro: $message,
                actionUrl: $actionUrl,
                actionText: $actionText,
            ));
        }

        return $notification;
    }
}
