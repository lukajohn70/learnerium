<?php

namespace App\Http\Controllers;

use App\Models\AppNotification;
use App\Models\NotificationPreference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Return unread notification count (JSON — for bell badge).
     */
    public function count()
    {
        $count = AppNotification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->count();
        return response()->json(['count' => $count]);
    }

    /**
     * Return latest notifications (JSON — for dropdown panel).
     */
    public function index()
    {
        $notifications = AppNotification::where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->limit(20)
            ->get();
        return response()->json($notifications);
    }

    /**
     * Mark a single notification as read.
     */
    public function markRead(AppNotification $notification)
    {
        if ($notification->user_id !== Auth::id()) abort(403);
        $notification->update(['is_read' => true]);
        return response()->json(['ok' => true]);
    }

    /**
     * Mark ALL notifications as read.
     */
    public function markAllRead()
    {
        AppNotification::where('user_id', Auth::id())->update(['is_read' => true]);
        return response()->json(['ok' => true]);
    }

    /**
     * Show & save notification preferences page.
     */
    public function preferences()
    {
        $prefs = NotificationPreference::forUser(Auth::id());
        return view('notifications.preferences', compact('prefs'));
    }

    /**
     * Save notification preferences.
     */
    public function savePreferences(Request $request)
    {
        $fields = [
            'email_enrollment', 'email_payment', 'email_course_updates',
            'email_new_student', 'email_payout', 'email_announcements', 'email_marketing',
            'inapp_enrollment', 'inapp_payment', 'inapp_course_updates', 'inapp_announcements',
        ];

        $data = [];
        foreach ($fields as $field) {
            $data[$field] = $request->boolean($field);
        }

        NotificationPreference::updateOrCreate(
            ['user_id' => Auth::id()],
            $data
        );

        return back()->with('status', 'Notification preferences saved!');
    }
}
