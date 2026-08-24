<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Response to your message — Learnerium</title>
    <style>
        body { margin: 0; padding: 0; background: #f4f6fb; font-family: 'Segoe UI', Arial, sans-serif; }
        .wrapper { max-width: 600px; margin: 40px auto; background: #fff; border-radius: 20px; overflow: hidden; box-shadow: 0 4px 32px rgba(27,34,153,0.10); }
        .header { background: linear-gradient(135deg, #1b2299 0%, #0d1259 100%); padding: 32px 36px; text-align: center; }
        .header h1 { color: #f7de7a; font-size: 22px; font-weight: 900; margin: 0; }
        .header p { color: rgba(255,255,255,0.8); font-size: 13px; margin: 4px 0 0; }
        .body { padding: 36px; }
        .hi { font-size: 16px; font-weight: 700; color: #1b2299; margin-bottom: 16px; }
        .reply-box { background: #f8f9ff; border-left: 4px solid #1b2299; padding: 18px 20px; border-radius: 0 12px 12px 0; margin-bottom: 24px; font-size: 14px; color: #1f2937; line-height: 1.7; }
        .quote-box { background: #f3f4f6; border-radius: 12px; padding: 16px 20px; margin-bottom: 24px; font-size: 13px; color: #4b5563; }
        .quote-title { font-size: 11px; font-weight: 700; text-transform: uppercase; color: #9ca3af; margin-bottom: 6px; letter-spacing: 0.5px; }
        .footer { background: #f8f9ff; padding: 24px 36px; text-align: center; border-top: 1px solid #edf0fb; }
        .footer p { font-size: 11px; color: #9ca3af; margin: 0 0 4px; }
        .footer a { color: #1b2299; text-decoration: none; font-weight: 700; }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="header">
        <h1>Learnerium Support</h1>
        <p>Response to your inquiry</p>
    </div>
    <div class="body">
        <p class="hi">Hello {{ $recipientName }},</p>
        <p style="font-size: 14px; color: #4b5563; margin-bottom: 16px;">
            Thank you for reaching out to us. Here is our response regarding <strong>"{{ $originalSubject }}"</strong>:
        </p>

        <div class="reply-box">
            {!! nl2br(e($replyText)) !!}
        </div>

        <div class="quote-box">
            <div class="quote-title">Your Original Message:</div>
            <p style="margin: 0; font-style: italic;">{!! nl2br(e($originalMessage)) !!}</p>
        </div>

        <p style="font-size: 13px; color: #6b7280;">
            Best regards,<br>
            <strong>{{ $adminName }} &bull; Learnerium Team</strong>
        </p>
    </div>
    <div class="footer">
        <p><strong>Learnerium</strong> &bull; <a href="{{ url('/') }}">learnerium.com.ng</a></p>
        <p>You can reply directly to this email to continue this conversation.</p>
    </div>
</div>
</body>
</html>
