<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject }}</title>
    <style>
        body { margin: 0; padding: 0; background: #f4f6fb; font-family: 'Segoe UI', Arial, sans-serif; }
        .wrapper { max-width: 600px; margin: 40px auto; background: #fff; border-radius: 20px; overflow: hidden; box-shadow: 0 4px 32px rgba(27,34,153,0.10); }
        .header { background: linear-gradient(135deg, #1b2299 0%, #0d1259 100%); padding: 36px 36px 28px; text-align: center; }
        .header h1 { color: #f7de7a; font-size: 22px; font-weight: 900; margin: 0; }
        .header p { color: rgba(255,255,255,0.8); font-size: 13px; margin: 4px 0 0; }
        .body { padding: 36px; }
        .hi { font-size: 16px; font-weight: 700; color: #1b2299; margin-bottom: 16px; }
        .content { font-size: 14px; color: #374151; line-height: 1.75; margin-bottom: 28px; }
        .content p { margin-bottom: 14px; }
        .footer { background: #f8f9ff; padding: 24px 36px; text-align: center; border-top: 1px solid #edf0fb; }
        .footer p { font-size: 11px; color: #9ca3af; margin: 0 0 4px; }
        .footer a { color: #1b2299; text-decoration: none; font-weight: 700; }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="header">
        <h1>Learnerium Platform Update</h1>
        <p>Learn Without Limits</p>
    </div>
    <div class="body">
        <p class="hi">Hello {{ $recipient->name ?? 'there' }},</p>
        <div class="content">
            {!! nl2br(e($content)) !!}
        </div>
        <p style="font-size: 13px; color: #6b7280; line-height: 1.6;">
            Best regards,<br>
            <strong>The Learnerium Team</strong>
        </p>
    </div>
    <div class="footer">
        <p><strong>Learnerium</strong> &bull; <a href="{{ url('/') }}">learnerium.com.ng</a></p>
        <p>You can reply directly to this email if you have any questions or feedback.</p>
    </div>
</div>
</body>
</html>
