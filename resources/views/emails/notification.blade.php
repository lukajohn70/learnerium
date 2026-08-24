<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Notification from Learnerium' }}</title>
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #f8fafc; margin: 0; padding: 24px; color: #1e293b;">
    <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);">
        <!-- Header -->
        <tr>
            <td style="background-color: #1b2299; padding: 24px 32px; text-align: center;">
                <h1 style="color: #ffffff; margin: 0; font-size: 22px; font-weight: 800; letter-spacing: 0.5px;">
                    LEARNERIUM
                </h1>
                <p style="color: #f7de7a; margin: 4px 0 0 0; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">
                    Online Learning Platform
                </p>
            </td>
        </tr>

        <!-- Content -->
        <tr>
            <td style="padding: 32px;">
                <h2 style="font-size: 18px; font-weight: 700; color: #0f172a; margin-top: 0; margin-bottom: 12px;">
                    {{ $title }}
                </h2>
                <p style="font-size: 14px; line-height: 1.6; color: #475569; margin-bottom: 24px;">
                    {{ $bodyMessage }}
                </p>

                @if(!empty($actionUrl))
                <table cellpadding="0" cellspacing="0" style="margin: 28px 0 16px 0;">
                    <tr>
                        <td align="center" style="border-radius: 12px; background-color: #1b2299;">
                            <a href="{{ $actionUrl }}" target="_blank" style="font-size: 13px; font-weight: 700; color: #ffffff; text-decoration: none; padding: 12px 24px; border-radius: 12px; display: inline-block;">
                                View in Learnerium &rarr;
                            </a>
                        </td>
                    </tr>
                </table>
                @endif

                <hr style="border: 0; border-top: 1px solid #f1f5f9; margin: 28px 0;">

                <p style="font-size: 11px; color: #94a3b8; line-height: 1.5; margin: 0;">
                    You are receiving this notification because of your account activity on <a href="{{ url('/') }}" style="color: #1b2299; text-decoration: none; font-weight: 600;">Learnerium</a>.<br>
                    You can manage your notification preferences anytime in your <a href="{{ url('/settings/notifications') }}" style="color: #1b2299; text-decoration: none; font-weight: 600;">Account Notification Settings</a>.
                </p>
            </td>
        </tr>

        <!-- Footer -->
        <tr>
            <td style="background-color: #f8fafc; padding: 16px 32px; text-align: center; border-top: 1px solid #e2e8f0; font-size: 11px; color: #94a3b8;">
                &copy; {{ date('Y') }} Learnerium. All rights reserved.
            </td>
        </tr>
    </table>
</body>
</html>
