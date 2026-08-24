<?php

namespace App\Http\Controllers;

use App\Models\BroadcastEmail;
use App\Models\InboundMessage;
use App\Models\User;
use App\Models\AppNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class AdminMailerController extends Controller
{
    /**
     * Send email broadcast to all or selected users.
     */
    public function send(Request $request)
    {
        $request->validate([
            'recipient_type'    => 'required|in:all,students,instructors,specific',
            'recipient_user_id' => 'nullable|required_if:recipient_type,specific|exists:users,id',
            'subject'           => 'required|string|max:255',
            'message'           => 'required|string',
            'also_notify'       => 'nullable|boolean',
        ]);

        $admin = Auth::user();
        $recipientType = $request->recipient_type;
        $subject = $request->subject;
        $body = $request->message;
        $alsoNotify = (bool) $request->input('also_notify', true);

        $query = User::query();
        if ($recipientType === 'students') {
            $query->where('role', 'student');
        } elseif ($recipientType === 'instructors') {
            $query->where('role', 'instructor');
        } elseif ($recipientType === 'specific') {
            $query->where('id', $request->recipient_user_id);
        }

        $recipients = $query->get();
        $totalSent = 0;

        // Log broadcast
        $broadcast = BroadcastEmail::create([
            'sender_id'         => $admin->id,
            'recipient_type'    => $recipientType,
            'recipient_user_id' => $recipientType === 'specific' ? $request->recipient_user_id : null,
            'subject'           => $subject,
            'message'           => $body,
            'total_sent'        => 0,
        ]);

        $replyToEmail = config('mail.from.address') ?: 'learnerium@jlm.com.ng';
        $replyToName  = config('mail.from.name') ?: 'Learnerium Support';

        foreach ($recipients as $recipient) {
            try {
                Mail::send('emails.admin_broadcast', [
                    'recipient'   => $recipient,
                    'subject'     => $subject,
                    'content'     => $body,
                    'broadcastId' => $broadcast->id,
                ], function ($m) use ($recipient, $subject, $replyToEmail, $replyToName) {
                    $m->to($recipient->email, $recipient->name)
                      ->subject($subject)
                      ->replyTo($replyToEmail, $replyToName);
                });

                $totalSent++;

                if ($alsoNotify) {
                    AppNotification::notify(
                        $recipient->id,
                        'announcement',
                        "📢 {$subject}",
                        \Illuminate\Support\Str::limit(strip_tags($body), 140),
                        route('dashboard'),
                        'fa-bullhorn',
                        'blue'
                    );
                }
            } catch (\Exception $e) {
                Log::warning("Failed to send broadcast email to {$recipient->email}: " . $e->getMessage());
            }
        }

        $broadcast->update(['total_sent' => $totalSent]);

        return back()->with('status', "Message successfully sent to {$totalSent} recipient(s).");
    }

    /**
     * Reply to an inbound message.
     */
    public function reply(Request $request, InboundMessage $message)
    {
        $request->validate([
            'reply_text' => 'required|string',
        ]);

        $replyText = $request->reply_text;
        $admin = Auth::user();

        // 1. Always record the reply in database immediately
        $message->update([
            'status'      => 'replied',
            'admin_reply' => $replyText,
            'replied_at'  => now(),
        ]);

        // 2. In-app notification to the user if linked
        $targetUserId = $message->user_id;
        if (!$targetUserId) {
            $matchingUser = User::where('email', $message->email)->first();
            if ($matchingUser) {
                $targetUserId = $matchingUser->id;
                $message->update(['user_id' => $targetUserId]);
            }
        }

        if ($targetUserId) {
            try {
                AppNotification::notify(
                    $targetUserId,
                    'support',
                    "Support Response: Re: {$message->subject}",
                    \Illuminate\Support\Str::limit($replyText, 140),
                    route('user.inbox'),
                    'fa-reply',
                    'green'
                );
            } catch (\Throwable $e) {}
        }

        // 3. Attempt email delivery
        try {
            Mail::send('emails.admin_reply', [
                'recipientName' => $message->name,
                'originalSubject' => $message->subject,
                'originalMessage' => $message->message,
                'replyText' => $replyText,
                'adminName' => $admin->name,
            ], function ($m) use ($message) {
                $m->to($message->email, $message->name)
                  ->subject("Re: {$message->subject} — Learnerium Support");
            });
        } catch (\Exception $e) {
            Log::warning("Could not deliver reply email to {$message->email}: " . $e->getMessage());
        }

        return back()->with('status', "Reply recorded and sent to {$message->name} ({$message->email}).");
    }

    /**
     * Receive inbound contact / support form from students or visitors.
     */
    public function storeInbound(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:150',
            'email'     => 'required|email|max:190',
            'subject'   => 'required|string|max:255',
            'message'   => 'required|string|max:5000',
            'broadcast_email_id' => 'nullable|exists:broadcast_emails,id',
        ]);

        $userId = Auth::id();
        if (!$userId) {
            $matchingUser = User::where('email', $request->email)->first();
            if ($matchingUser) {
                $userId = $matchingUser->id;
            }
        }

        $inbound = InboundMessage::create([
            'user_id'            => $userId,
            'broadcast_email_id' => $request->broadcast_email_id ?: null,
            'name'               => $request->name,
            'email'              => $request->email,
            'subject'            => $request->subject,
            'message'            => $request->message,
            'status'             => 'unread',
        ]);

        // Notify admins in-app
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            AppNotification::notify(
                $admin->id,
                'support',
                "New Inbound Message from {$request->name} ✉️",
                "Subject: {$request->subject}",
                route('admin.dashboard'),
                'fa-envelope',
                'purple'
            );
        }

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Your message has been received! Our support team will get back to you shortly.']);
        }

        return back()->with('status', 'Your message has been sent to the Learnerium team! We will reply via email shortly.');
    }

    /**
     * Mark an inbound message as read (admin).
     */
    public function markRead(InboundMessage $message)
    {
        if ($message->status === 'unread') {
            $message->update(['status' => 'read']);
        }
        return response()->json(['success' => true]);
    }

    /**
     * In-app inbox for authenticated students and instructors.
     * Shows broadcasts sent to them and their sent contact messages + replies.
     */
    public function userInbox()
    {
        $user = Auth::user();

        // Broadcasts that were sent to this user (all, their role, or specific)
        $broadcasts = BroadcastEmail::where(function ($q) use ($user) {
            $q->where('recipient_type', 'all')
              ->orWhere('recipient_type', $user->role === 'instructor' ? 'instructors' : 'students')
              ->orWhere(function ($q2) use ($user) {
                  $q2->where('recipient_type', 'specific')->where('recipient_user_id', $user->id);
              });
        })->with('sender')->latest()->get();

        // Inbound messages submitted by this user (their contact/replies) + admin responses
        $myMessages = InboundMessage::where(function ($q) use ($user) {
            $q->where('user_id', $user->id)
              ->orWhere('email', $user->email);
        })->latest()->get();

        return view('user.inbox', compact('broadcasts', 'myMessages'));
    }

    /**
     * Submit a reply / new contact message from a user (via inbox page).
     */
    public function userReply(Request $request, BroadcastEmail $message)
    {
        $request->validate([
            'reply_text' => 'required|string|max:5000',
        ]);

        $user = Auth::user();

        $inbound = InboundMessage::create([
            'user_id'            => $user->id,
            'broadcast_email_id' => $message->id,
            'name'               => $user->name,
            'email'              => $user->email,
            'subject'            => "Re: {$message->subject}",
            'message'            => $request->reply_text,
            'status'             => 'unread',
        ]);

        // Notify all admins in-app
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            AppNotification::notify(
                $admin->id,
                'support',
                "Reply from {$user->name} ✉️",
                "Re: {$message->subject}",
                route('admin.dashboard'),
                'fa-reply',
                'purple'
            );
        }

        return back()->with('status', 'Your reply has been sent to the Learnerium team!');
    }
}
