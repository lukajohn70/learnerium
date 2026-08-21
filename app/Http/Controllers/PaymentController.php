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
        if ($enrollment && ($course->price == 0 || $enrollment->payment_status === 'paid')) {
            return redirect()->route('course.detail', $course->slug)
                ->with('info', 'You are already enrolled and have full access to this course.');
        }

        return view('student.checkout', compact('course'));
    }

    /**
     * Apply coupon code and calculate discount.
     */
    public function applyCoupon(Request $request, Course $course)
    {
        $request->validate([
            'coupon_code' => 'required|string',
        ]);

        $code = strtoupper(trim($request->input('coupon_code')));
        $coupon = Coupon::where('code', $code)->first();

        if (!$coupon || !$coupon->isValidFor($course)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired coupon code.',
            ], 422);
        }

        $discount = $coupon->discountAmount($course->price);
        $finalPrice = max(0, $course->price - $discount);

        return response()->json([
            'success' => true,
            'message' => 'Coupon code applied successfully!',
            'discount' => $discount,
            'final_price' => $finalPrice,
        ]);
    }

    /**
     * Initialize transaction and redirect to Paystack.
     */
    public function initialize(Request $request, Course $course)
    {
        $user = Auth::user();
        $originalPrice = (float) $course->price;
        $finalPrice = $originalPrice;
        $couponCode = null;

        // Apply coupon code if provided
        if ($request->filled('coupon_code')) {
            $code = strtoupper(trim($request->input('coupon_code')));
            $coupon = Coupon::where('code', $code)->first();

            if ($coupon && $coupon->isValidFor($course)) {
                $discount = $coupon->discountAmount($originalPrice);
                $finalPrice = max(0, $originalPrice - $discount);
                $couponCode = $code;
            }
        }

        // If final price is 0, enroll student directly
        if ($finalPrice <= 0) {
            Enrollment::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'course_id' => $course->id,
                ],
                [
                    'payment_status' => 'paid',
                    'amount_paid' => 0.00,
                    'coupon_code' => $couponCode,
                    'progress_percentage' => 0,
                ]
            );

            return redirect()->route('course.detail', $course->slug)
                ->with('status', 'Successfully enrolled in course!');
        }

        // Check if Paystack is configured
        $secretKey = config('services.paystack.secret_key');
        if (empty($secretKey)) {
            return back()->with('error', 'Payment gateway is currently not configured.');
        }

        // Generate unique reference
        $reference = 'PSK-C' . $course->id . '-U' . $user->id . '-' . strtoupper(Str::random(8));

        // Create or update pending enrollment
        Enrollment::updateOrCreate(
            [
                'user_id' => $user->id,
                'course_id' => $course->id,
            ],
            [
                'payment_status' => 'pending',
                'amount_paid' => $finalPrice,
                'coupon_code' => $couponCode,
                'payment_reference' => $reference,
            ]
        );

        // Paystack Payload
        $payload = [
            'email' => $user->email,
            'amount' => (int) round($finalPrice * 100), // in kobo
            'reference' => $reference,
            'callback_url' => route('payment.callback'),
            'metadata' => [
                'course_id' => $course->id,
                'user_id' => $user->id,
                'coupon_code' => $couponCode,
            ]
        ];

        try {
            $response = Http::withToken($secretKey)
                ->post('https://api.paystack.co/transaction/initialize', $payload);

            if ($response->failed()) {
                $errorMsg = $response->json()['message'] ?? 'Unable to contact payment gateway.';
                return back()->with('error', 'Paystack Error: ' . $errorMsg);
            }

            $responseData = $response->json();
            $authUrl = $responseData['data']['authorization_url'] ?? null;

            if ($authUrl) {
                return redirect()->away($authUrl);
            }

            return back()->with('error', 'Failed to retrieve authorization URL from payment gateway.');
        } catch (\Exception $e) {
            Log::error('Paystack initialization exception: ' . $e->getMessage());
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
                    $enrollment->update([
                        'payment_status' => 'paid',
                        'amount_paid' => ((float) ($result['data']['amount'] ?? 0)) / 100,
                    ]);

                    $course = Course::find($enrollment->course_id);
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
}
