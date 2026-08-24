<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Your Enrollment — Learnerium</title>
    <style>
        body { margin: 0; padding: 0; background: #f4f6fb; font-family: 'Segoe UI', Arial, sans-serif; }
        .wrapper { max-width: 580px; margin: 40px auto; background: #fff; border-radius: 20px; overflow: hidden; box-shadow: 0 4px 32px rgba(27,34,153,0.10); }
        .header { background: linear-gradient(135deg, #1b2299 0%, #e4306d 100%); padding: 40px 36px 32px; text-align: center; }
        .header img { height: 42px; margin-bottom: 16px; }
        .header h1 { color: #f7de7a; font-size: 24px; font-weight: 900; margin: 0 0 6px; letter-spacing: -0.5px; }
        .header p { color: rgba(255,255,255,0.85); font-size: 14px; margin: 0; }
        .body { padding: 36px; }
        .hi { font-size: 17px; font-weight: 700; color: #1b2299; margin-bottom: 14px; }
        .message { font-size: 14px; color: #374151; line-height: 1.7; margin-bottom: 24px; }
        .course-card { background: #f8f9ff; border: 1.5px solid #e0e3ff; border-radius: 14px; padding: 18px 20px; margin-bottom: 28px; display: flex; gap: 16px; align-items: center; }
        .course-card img { width: 72px; height: 52px; border-radius: 10px; object-fit: cover; flex-shrink: 0; }
        .course-card .info h3 { font-size: 15px; font-weight: 800; color: #1b2299; margin: 0 0 4px; }
        .course-card .info p { font-size: 12px; color: #6b7280; margin: 0 0 6px; }
        .course-card .price { font-size: 18px; font-weight: 900; color: #e4306d; }
        .cta-btn { display: block; text-align: center; background: linear-gradient(135deg, #e4306d, #1b2299); color: #fff !important; font-size: 15px; font-weight: 900; text-decoration: none; padding: 16px 32px; border-radius: 14px; margin: 0 auto 24px; letter-spacing: 0.3px; }
        .cta-btn:hover { opacity: 0.93; }
        .divider { border: none; border-top: 1px solid #e5e7eb; margin: 28px 0; }
        .footer { background: #f8f9ff; padding: 24px 36px; text-align: center; }
        .footer p { font-size: 11px; color: #9ca3af; margin: 0 0 4px; }
        .footer a { color: #1b2299; text-decoration: none; font-weight: 700; }
        .badge { display: inline-block; background: #fef3c7; color: #92400e; font-size: 11px; font-weight: 700; padding: 3px 10px; border-radius: 20px; margin-bottom: 16px; text-transform: uppercase; letter-spacing: 0.5px; }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="header">
        <h1>⏰ Don't Miss Out!</h1>
        <p>Your enrollment in this course is still incomplete</p>
    </div>
    <div class="body">
        <span class="badge">Payment Reminder</span>
        <p class="hi">Hi {{ $student->name }},</p>
        <p class="message">
            We noticed you started enrolling in <strong>{{ $course->title }}</strong> but haven't completed your payment yet.
            Your spot is waiting for you! Complete your enrollment now and unlock all course materials, lessons, and your certificate.
        </p>

        <div class="course-card">
            <img src="{{ $course->thumbnailUrl() }}" alt="{{ $course->title }}" onerror="this.src='https://placehold.co/72x52/1b2299/f7de7a?text=Course'">
            <div class="info">
                <h3>{{ $course->title }}</h3>
                <p>by {{ $course->instructor->name ?? 'Learnerium Instructor' }} &bull; {{ $course->level }}</p>
                <span class="price">₦{{ number_format($enrollment->amount_paid ?? $course->price, 2) }}</span>
            </div>
        </div>

        <a href="{{ $checkoutUrl }}" class="cta-btn">
            ✅ Complete My Enrollment Now
        </a>

        <p class="message" style="font-size:13px;color:#6b7280;text-align:center;">
            This link takes you directly to the checkout page. If you have any issues, reply to this email and we'll assist you.
        </p>

        <hr class="divider">
        <p class="message" style="font-size:12px;color:#9ca3af;text-align:center;">
            If you no longer wish to enroll, you can simply ignore this email. No further action is needed.
        </p>
    </div>
    <div class="footer">
        <p><strong>Learnerium</strong> — Learn Without Limits</p>
        <p><a href="{{ url('/') }}">learnerium.com.ng</a> &bull; <a href="{{ url('/courses') }}">Browse Courses</a></p>
        <p style="margin-top:8px">You received this because you started an enrollment on Learnerium.</p>
    </div>
</div>
</body>
</html>
