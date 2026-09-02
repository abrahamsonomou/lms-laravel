<?php

namespace App\Http\Controllers;

use App\Models\Notification\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(Request $request): View
    {
        $notifications = $request->user()
            ->appNotifications()
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    public function markRead(Request $request, Notification $notification): RedirectResponse
    {
        abort_unless($notification->user_id === $request->user()->id, 403);

        $notification->update(['lu' => true, 'date_lecture' => now()]);

        return back();
    }

    public function markAllRead(Request $request): RedirectResponse
    {
        $request->user()
            ->appNotifications()
            ->where('lu', false)
            ->update(['lu' => true, 'date_lecture' => now()]);

        return back()->with('success', 'Toutes les notifications ont été marquées comme lues.');
    }
}
