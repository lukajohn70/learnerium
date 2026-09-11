<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    /**
     * Show the checkout page.
     */
    public function checkout(Course $course)
    {
        $user = Auth::user();

        // Check if student
        if (!$user->isStudent()) {
            return redirect()->route('course.detail', $course->slug)
                ->with('error', 'Only student accounts can purchase courses.');
        }

        // Check if already paid
        $enrollment = $user->enrollments()->where('course_id', $course->id)->first();
        if ($enrollment && in_array($enrollment->payment_status, ['paid'])) {
            return redirect()->route('course.detail', $course->slug)
                ->with('info', 'You are already enrolled and have full access to this course.');
        }

        // Block enrolling in already-preordered course
        if ($enrollment && $enrollment->payment_status === 'preorder' && $course->isPreorder()) {
            return redirect()->route('course.detail', $course->slug)
                ->with('info', 'You have already pre-ordered this course. You will get access once it launches.');
        }

        $publicKey = config('services.paystack.public_key')
            ?: (env('PAYSTACK_PUBLIC_KEY')
            ?: (env('JLM_PAYSTACK_PUBLIC_KEY')
            ?: 'pk_live_' . '163e689646002d8a87effbe182de242c5649e586'));

        $isPreorder = $course->isPreorder();

        return view('student.checkout', compact('course', 'publicKey', 'isPreorder'));
    }

    /**
     * Apply coupon code and calculate discount.
     */
    public function applyCoupon(Request $request, Course $course)
    {
        $request->validate([
            'coupon_code' => 'nullable|string',
        ]);

        $code = strtoupper(trim($request->input('coupon_code')));
        $coupon = Coupon::where('code', $code)->first();

        if (!$coupon || !$coupon->isValidFor($course)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired coupon code.',
            ], 422);
        }

        // Apply against effective price (preorder or regular)
        $basePrice  = $course->effectivePrice();
        $discount   = $coupon->discountAmount($basePrice);
        $finalPrice = max(0, $basePrice - $discount);

        return response()->json([
            'success'     => true,
            'message'     => 'Coupon code applied successfully!',
            'discount'    => $discount,
            'final_price' => $finalPrice,
        ]);
    }

    /**
     * Initialize transaction and redirect to Paystack.
     */
    public function initialize(Request $request, Course $course)
    {
        $user = Auth::user();
        $isPreorder    = $course->isPreorder();
        $originalPrice = $course->effectivePrice(); // Uses preorder_price when in preorder mode
        $finalPrice    = $originalPrice;
        $couponCode    = null;

        // Apply coupon code if provided
        if ($request->filled('coupon_code')) {
            $code = strtoupper(trim($request->input('coupon_code')));
            $coupon = Coupon::where('code', $code)->first();

            if ($coupon && $coupon->isValidFor($course)) {
                $discount   = $coupon->discountAmount($originalPrice);
                $finalPrice = max(0, $originalPrice - $discount);
                $couponCode = $code;
            }
        }

        // If final price is 0, enroll student directly
        if ($finalPrice <= 0) {
            if ($couponCode) {
                $coupon = Coupon::where('code', $couponCode)->first();
                if ($coupon) {
                    $coupon->incrementUsage();
                }
            }

            // For preorder at price 0 — enroll as preorder if course not published yet, else as paid
            $enrollStatus = ($isPreorder && !$course->published_at) ? 'preorder' : 'paid';

            Enrollment::updateOrCreate(
                [
                    'user_id'   => $user->id,
                    'course_id' => $course->id,
                ],
                [
                    'payment_status'      => $enrollStatus,
                    'amount_paid'         => 0.00,
                    'coupon_code'         => $couponCode,
                    'progress_percentage' => 0,
                ]
            );

            // Send in-app and email notifications
            try {
                if ($enrollStatus === 'preorder') {
                    // Student pre-ordered a free preorder course
                    \App\Models\AppNotification::notify(
                        $user->id,
                        'enrollment',
                        'Pre-Order Confirmed!',
                        "You have successfully pre-ordered \"{$course->title}\". You will receive full access as soon as the course launches!",
                        route('course.detail', $course->slug),
                        'fa-bookmark',
                        'purple'
                    );
                } else {
                    // Normal free enrollment
                    \App\Models\AppNotification::notify(
                        $user->id,
                        'enrollment',
                        'Enrolled in Course!',
                        "You have successfully enrolled in \"{$course->title}\". Start learning now!",
                        route('course.detail', $course->slug),
                        'fa-graduation-cap',
                        'blue'
                    );
                }

                // Notify Instructor
                if ($course->instructor_id) {
                    \App\Models\AppNotification::notify(
                        $course->instructor_id,
                        'enrollment',
                        $enrollStatus === 'preorder' ? 'New Pre-Order Received' : 'New Student Enrolled',
                        "{$user->name} has just " . ($enrollStatus === 'preorder' ? 'pre-ordered' : 'enrolled in') . " your course \"{$course->title}\"" . ($couponCode ? " using coupon {$couponCode}." : "."),
                        route('instructor.courses.students', $course->id),
                        'fa-user-plus',
                        'green'
                    );
                }
            } catch (\Throwable $e) {}

            $successMsg = $enrollStatus === 'preorder'
                ? 'Pre-order confirmed! You will get full access when the course launches.'
                : 'Successfully enrolled in course!';

            return redirect()->route('course.detail', $course->slug)
                ->with('status', $successMsg);
        }


        // Check if Paystack is configured (supports PAYSTACK_SECRET_KEY, JLM_PAYSTACK_SECRET_KEY, and config)
        $secretKey = config('services.paystack.secret_key')
            ?: (env('PAYSTACK_SECRET_KEY')
            ?: (env('JLM_PAYSTACK_SECRET_KEY')
            ?: (env('PAYSTACK_SECRET')
            ?: 'sk_live_' . '6c3a6c6c3a68677c02bcbd71a51d0ca384263df1')));

        if (empty($secretKey)) {
            return back()->with('error', 'Payment gateway is currently not configured.');
        }

        // Generate unique reference
        $reference = 'PSK-C' . $course->id . '-U' . $user->id . '-' . strtoupper(Str::random(8));

        // Create or update pending enrollment — preorder flag is stored in metadata
        Enrollment::updateOrCreate(
            [
                'user_id'   => $user->id,
                'course_id' => $course->id,
            ],
            [
                'payment_status'      => 'pending',
                'amount_paid'         => $finalPrice,
                'coupon_code'         => $couponCode,
                'payment_reference'   => $reference,
            ]
        );

        $currency = strtoupper($request->input('currency', 'NGN'));

        // Paystack Payload with Multi-Currency support
        $payload = [
            'email'        => $user->email,
            'amount'       => (int) round($finalPrice * 100), // in minor currency unit (kobo/cents/pesewas)
            'currency'     => $currency,
            'reference'    => $reference,
            'callback_url' => route('payment.callback'),
            'metadata'     => [
                'course_id'    => $course->id,
                'user_id'      => $user->id,
                'coupon_code'  => $couponCode,
                'currency'     => $currency,
                'is_preorder'  => $isPreorder, // Passed so callback can set correct enrollment status
            ],
        ];

        try {
            $response = Http::withToken($secretKey)
                ->withOptions(['verify' => false])
                ->post('https://api.paystack.co/transaction/initialize', $payload);

            if ($response->failed()) {
                $errorMsg = $response->json()['message'] ?? 'Unable to contact payment gateway.';
                return back()->with('error', 'Paystack Error: ' . $errorMsg);
            }

            $responseData = $response->json();
            $authUrl = $responseData['data']['authorization_url'] ?? null;
            $accessCode = $responseData['data']['access_code'] ?? null;

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'authorization_url' => $authUrl,
                    'access_code' => $accessCode,
                    'reference' => $reference,
                    'public_key' => $publicKey ?? null,
                ]);
            }

            if ($authUrl) {
                return redirect()->away($authUrl);
            }

            return back()->with('error', 'Failed to retrieve authorization URL from payment gateway.');
        } catch (\Exception $e) {
            Log::error('Paystack initialization exception: ' . $e->getMessage());
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            return back()->with('error', 'Payment service exception: ' . $e->getMessage());
        }
    }

    /**
     * Process Paystack callback verification.
     */
    public function callback(Request $request)
    {
        $reference = $request->query('reference');
        if (empty($reference)) {
            return redirect()->route('courses')->with('error', 'Missing transaction reference.');
        }

        $secretKey = config('services.paystack.secret_key');
        if (empty($secretKey)) {
            return redirect()->route('courses')->with('error', 'Payment configuration missing.');
        }

        try {
            $response = Http::withToken($secretKey)
                ->get('https://api.paystack.co/transaction/verify/' . rawurlencode($reference));

            if ($response->failed()) {
                return redirect()->route('courses')->with('error', 'Failed to verify transaction.');
            }

            $result = $response->json();
            $status = $result['data']['status'] ?? '';
            $metadata = $result['data']['metadata'] ?? [];
            $courseId = $metadata['course_id'] ?? null;
            $userId = $metadata['user_id'] ?? null;

            if ($status === 'success') {
                // Find enrollment
                $enrollment = Enrollment::where('payment_reference', $reference)->first();

                if (!$enrollment && $courseId && $userId) {
                    $enrollment = Enrollment::where('user_id', $userId)
                        ->where('course_id', $courseId)
                        ->first();
                }

                if ($enrollment) {
                    $amountPaid = ((float) ($result['data']['amount'] ?? 0)) / 100;
                    $course = Course::find($enrollment->course_id);

                    // Determine correct post-payment status
                    $wasPreorder    = !empty($metadata['is_preorder']) && $course && !$course->published_at;
                    $enrollStatus   = $wasPreorder ? 'preorder' : 'paid';

                    // Calculate revenue split from live platform settings
                    $instructorSharePct = (float) \App\Models\PlatformSetting::get('instructor_revenue_share', 70);
                    $platformSharePct   = (float) \App\Models\PlatformSetting::get('platform_revenue_share', 30);
                    $instructorShare    = round($amountPaid * ($instructorSharePct / 100), 2);
                    $platformShare      = round($amountPaid * ($platformSharePct / 100), 2);

                    $enrollment->update([
                        'payment_status'   => $enrollStatus,
                        'amount_paid'      => $amountPaid,
                        'instructor_share' => $instructorShare,
                        'platform_share'   => $platformShare,
                        'payout_status'    => 'pending',
                    ]);

                    // Fire in-app and email notifications
                    try {
                        // 1. Notify the student
                        if ($wasPreorder) {
                            \App\Models\AppNotification::notify(
                                $enrollment->user_id,
                                'payment',
                                'Pre-Order Payment Confirmed!',
                                "Your pre-order payment for \"{$course->title}\" was successful! You'll get full access as soon as the course launches.",
                                $course ? route('course.detail', $course->slug) : null,
                                'fa-bookmark',
                                'purple'
                            );
                        } else {
                            \App\Models\AppNotification::notify(
                                $enrollment->user_id,
                                'payment',
                                'Payment Confirmed! You are Enrolled!',
                                "You're now enrolled in \"{$course->title}\". Start learning now!",
                                $course ? route('course.detail', $course->slug) : null,
                                'fa-check-circle',
                                'green'
                            );
                        }

                        // 2. Notify the instructor
                        if ($course && $course->instructor_id) {
                            \App\Models\AppNotification::notify(
                                $course->instructor_id,
                                'payment',
                                'New Student Enrolled 💰',
                                "A student just enrolled in \"{$course->title}\" — your share: ₦" . number_format($instructorShare, 2),
                                route('instructor.courses.students', $course->id),
                                'fa-graduation-cap',
                                'blue'
                            );
                        }

                        // 3. Notify all registered Admins of platform revenue
                        $studentUser = $enrollment->user;
                        \App\Models\AppNotification::notifyAdmins(
                            'payment',
                            "💰 New Course Enrollment: ₦" . number_format($amountPaid, 2),
                            "Student {$studentUser?->name} enrolled in \"{$course?->title}\". Platform share: ₦" . number_format($platformShare, 2),
                            route('admin.dashboard'),
                            'fa-receipt',
                            'purple'
                        );
                    } catch (\Exception $notifEx) {
                        Log::warning('Notification error: ' . $notifEx->getMessage());
                    }

                    return redirect()->route('course.detail', $course ? $course->slug : '')
                        ->with('status', 'Payment successful! You are now enrolled.');
                }

                return redirect()->route('courses')->with('status', 'Payment verified successfully.');
            }

            return redirect()->route('courses')->with('error', 'Payment verification failed: ' . ($result['data']['gateway_response'] ?? 'Declined'));
        } catch (\Exception $e) {
            Log::error('Paystack callback exception: ' . $e->getMessage());
            return redirect()->route('courses')->with('error', 'Error verifying payment: ' . $e->getMessage());
        }
    }

    /**
     * Send a payment reminder email to a student with a pending enrollment.
     */
    public function sendPaymentReminder(Enrollment $enrollment)

    {
        // Only allow admin or the course instructor
        $user = Auth::user();
        $course = $enrollment->course;
        $student = $enrollment->user;

        if (!$user || (!$user->isAdmin() && $course->instructor_id !== $user->id)) {
            abort(403);
        }

        if ($enrollment->payment_status === 'paid') {
            return back()->with('status', "{$student->name} is already enrolled and has paid.");
        }

        // Build checkout URL safely
        $checkoutUrl = route('courses.checkout', $course);

        try {
            \Illuminate\Support\Facades\Mail::send(
                'emails.payment_reminder',
                compact('student', 'course', 'enrollment', 'checkoutUrl'),
                function ($m) use ($student, $course) {
                    $m->to($student->email, $student->name)
                      ->subject("⏰ Complete Your Enrollment: {$course->title} — Learnerium");
                }
            );

            // In-app notification
            \App\Models\AppNotification::notify(
                $student->id,
                'payment',
                'Enrollment Reminder 💳',
                "A reminder to complete your enrollment in \"{$course->title}\". Click to go to checkout.",
                $checkoutUrl,
                'fa-credit-card',
                'amber'
            );

            return back()->with('status', "Payment reminder sent to {$student->name} ({$student->email}).");
        } catch (\Exception $e) {
            Log::error('Payment reminder failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to send reminder: ' . $e->getMessage());
        }
    }
}
