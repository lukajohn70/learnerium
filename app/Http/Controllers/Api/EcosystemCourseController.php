<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AppNotification;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\PlatformSetting;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class EcosystemCourseController extends Controller
{
    /**
     * Service health check for ZAVORA & Ecosystem monitoring.
     *
     * GET /api/v1/health
     */
    public function health(Request $request): JsonResponse
    {
        $dbConnected = false;
        $courseCount = 0;
        $studentCount = 0;

        try {
            DB::connection()->getPdo();
            $dbConnected = true;
            $courseCount = Course::where(function ($q) {
                $q->whereNotNull('published_at')
                  ->orWhere('is_preorder', true);
            })->count();
            $studentCount = User::where('role', 'student')->count();
        } catch (\Throwable $e) {
            Log::warning('Ecosystem health check DB error: ' . $e->getMessage());
        }

        $status = $dbConnected ? 'ok' : 'degraded';
        $httpCode = $dbConnected ? 200 : 503;

        return response()->json([
            'success'            => $dbConnected,
            'status'             => $status,
            'service'            => 'Learnerium LMS',
            'environment'        => config('app.env'),
            'laravel_version'    => app()->version(),
            'database'           => $dbConnected ? 'connected' : 'disconnected',
            'courses_available'  => $courseCount,
            'students_count'     => $studentCount,
            'timestamp'          => now()->toIso8601String(),
        ], $httpCode);
    }

    /**
     * Course catalogue endpoint for ZAVORA storefront discovery.
     *
     * GET /api/v1/courses/catalogue
     */
    public function catalogue(Request $request): JsonResponse
    {
        try {
            $query = Course::with(['instructor:id,name,avatar,profile_picture'])
                ->withCount(['modules', 'lessons'])
                ->where(function ($q) {
                    $q->whereNotNull('published_at')
                      ->orWhere('is_preorder', true);
                });

            // Filters
            if ($request->filled('category')) {
                $query->where('category', $request->query('category'));
            }

            if ($request->filled('level')) {
                $query->where('level', $request->query('level'));
            }

            if ($request->filled('search')) {
                $search = $request->query('search');
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            }

            // Exclude preorders if explicitly asked
            if ($request->boolean('exclude_preorders')) {
                $query->where('is_preorder', false);
            }

            $perPage = min((int) $request->query('per_page', 24), 100);
            $courses = $query->latest('published_at')->paginate($perPage);

            $transformed = $courses->getCollection()->map(function ($course) {
                return $this->formatCourseSummary($course);
            });

            return response()->json([
                'success' => true,
                'data'    => $transformed,
                'pagination' => [
                    'current_page' => $courses->currentPage(),
                    'last_page'    => $courses->lastPage(),
                    'per_page'     => $courses->perPage(),
                    'total'        => $courses->total(),
                    'has_more'     => $courses->hasMorePages(),
                ],
            ]);
        } catch (\Throwable $e) {
            Log::error('Ecosystem catalogue fetch error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error'   => 'server_error',
                'message' => 'Unable to fetch course catalogue at this time.',
            ], 500);
        }
    }

    /**
     * Course details endpoint with curriculum overview (modules + lesson titles).
     *
     * GET /api/v1/courses/{identifier}
     */
    public function courseDetails(Request $request, string $identifier): JsonResponse
    {
        try {
            $course = is_numeric($identifier)
                ? Course::find($identifier)
                : Course::where('slug', $identifier)->first();

            if (!$course) {
                return response()->json([
                    'success' => false,
                    'error'   => 'not_found',
                    'message' => "Course with identifier '{$identifier}' not found.",
                ], 404);
            }

            $course->load([
                'instructor:id,name,avatar,profile_picture',
                'modules' => function ($q) {
                    $q->orderBy('order', 'asc')->with([
                        'lessons' => function ($lq) {
                            $lq->select('id', 'module_id', 'course_id', 'title', 'order')
                               ->orderBy('order', 'asc');
                        }
                    ]);
                }
            ]);

            $course->loadCount(['modules', 'lessons']);

            $data = $this->formatCourseSummary($course);
            $data['requirements'] = $course->requirements ?? [];
            $data['what_you_will_learn'] = $course->what_you_will_learn ?? [];

            // Curriculum outline (module & lesson titles without streaming video URLs)
            $data['curriculum'] = $course->modules->map(function ($module) {
                return [
                    'id'            => $module->id,
                    'title'         => $module->title,
                    'description'   => $module->description,
                    'order'         => $module->order,
                    'lessons_count' => $module->lessons->count(),
                    'lessons'       => $module->lessons->map(function ($lesson) {
                        return [
                            'id'    => $lesson->id,
                            'title' => $lesson->title,
                            'order' => $lesson->order,
                        ];
                    }),
                ];
            });

            return response()->json([
                'success' => true,
                'data'    => $data,
            ]);
        } catch (\Throwable $e) {
            Log::error('Ecosystem course details error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error'   => 'server_error',
                'message' => 'Unable to retrieve course details.',
            ], 500);
        }
    }

    /**
     * Provision enrollment for a verified course purchase on ZAVORA.
     *
     * POST /api/v1/enrolments/provision
     *
     * Idempotent: repeated calls with the same payment_reference will not create duplicate enrolments.
     */
    public function provisionEnrolment(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'student_email'     => 'required|email|max:191',
            'student_name'      => 'required|string|max:191',
            'course_id'         => 'required_without:course_slug|nullable|integer',
            'course_slug'       => 'required_without:course_id|nullable|string',
            'payment_reference' => 'required|string|max:191',
            'amount_paid'       => 'required|numeric|min:0',
            'currency'          => 'nullable|string|max:10',
            'metadata'          => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error'   => 'validation_error',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $reference = trim($request->input('payment_reference'));
        $email = strtolower(trim($request->input('student_email')));
        $name = trim($request->input('student_name'));
        $amountPaid = (float) $request->input('amount_paid');

        // 1. Idempotency Check: Did we already provision this exact transaction?
        $existingEnrollment = Enrollment::where('payment_reference', $reference)
            ->with(['course:id,title,slug', 'user:id,name,email'])
            ->first();

        if ($existingEnrollment) {
            $course = $existingEnrollment->course;
            return response()->json([
                'success'    => true,
                'idempotent' => true,
                'message'    => 'Enrolment already provisioned for this payment reference.',
                'data'       => [
                    'enrolment_id'      => $existingEnrollment->id,
                    'payment_reference' => $reference,
                    'payment_status'    => $existingEnrollment->payment_status,
                    'amount_paid'       => (float) $existingEnrollment->amount_paid,
                    'is_new_user'       => false,
                    'student'           => [
                        'id'    => $existingEnrollment->user->id ?? $existingEnrollment->user_id,
                        'name'  => $existingEnrollment->user->name ?? $name,
                        'email' => $existingEnrollment->user->email ?? $email,
                    ],
                    'course'            => [
                        'id'    => $course->id ?? $existingEnrollment->course_id,
                        'title' => $course->title ?? '',
                        'slug'  => $course->slug ?? '',
                    ],
                    'learning_url'      => $course ? route('course.detail', $course->slug) : url('/dashboard'),
                    'login_url'         => route('login.student'),
                    'enrolled_at'       => $existingEnrollment->created_at ? $existingEnrollment->created_at->toIso8601String() : null,
                ],
            ], 200);
        }

        // 2. Find Course
        $course = null;
        if ($request->filled('course_id')) {
            $course = Course::find($request->input('course_id'));
        } elseif ($request->filled('course_slug')) {
            $course = Course::where('slug', $request->input('course_slug'))->first();
        }

        if (!$course) {
            return response()->json([
                'success' => false,
                'error'   => 'course_not_found',
                'message' => 'Specified course does not exist on this LMS platform.',
            ], 404);
        }

        // 3. Find or Create User Account
        $isNewUser = false;
        $generatedPassword = null;
        $user = User::where('email', $email)->first();

        if (!$user) {
            $generatedPassword = Str::random(12);
            $user = User::create([
                'name'              => $name,
                'email'             => $email,
                'password'          => Hash::make($generatedPassword),
                'role'              => 'student',
                'email_verified_at' => now(), // Auto-verify email for paid marketplace customers
            ]);
            $isNewUser = true;
        }

        // 4. Calculate Revenue Split
        $wasPreorder = $course->isPreorder();
        $enrollStatus = $wasPreorder ? 'preorder' : 'paid';

        $instructorSharePct = (float) PlatformSetting::get('instructor_revenue_share', 70);
        $platformSharePct   = (float) PlatformSetting::get('platform_revenue_share', 30);
        $instructorShare    = round($amountPaid * ($instructorSharePct / 100), 2);
        $platformShare      = round($amountPaid * ($platformSharePct / 100), 2);

        // 5. Create or Update Enrollment
        $enrollment = Enrollment::updateOrCreate(
            [
                'user_id'   => $user->id,
                'course_id' => $course->id,
            ],
            [
                'payment_status'      => $enrollStatus,
                'amount_paid'         => $amountPaid,
                'instructor_share'    => $instructorShare,
                'platform_share'      => $platformShare,
                'payout_status'       => 'pending',
                'payment_reference'   => $reference,
                'progress_percentage' => 0,
            ]
        );

        // 6. Dispatch Notifications
        try {
            if ($wasPreorder) {
                AppNotification::notify(
                    $user->id,
                    'payment',
                    'Pre-Order Confirmed (via ZAVORA)!',
                    "Your pre-order for \"{$course->title}\" is confirmed. You will have full access when the course launches.",
                    route('course.detail', $course->slug),
                    'fa-bookmark',
                    'purple'
                );
            } else {
                AppNotification::notify(
                    $user->id,
                    'payment',
                    'Course Access Confirmed!',
                    "You are now enrolled in \"{$course->title}\" through ZAVORA Marketplace. Start learning now!",
                    route('course.detail', $course->slug),
                    'fa-check-circle',
                    'green'
                );
            }

            if ($course->instructor_id) {
                AppNotification::notify(
                    $course->instructor_id,
                    'payment',
                    'New Student Enrolled via ZAVORA 💰',
                    "A student enrolled in \"{$course->title}\" via ZAVORA. Your share: ₦" . number_format($instructorShare, 2),
                    route('instructor.courses.students', $course->id),
                    'fa-graduation-cap',
                    'blue'
                );
            }
        } catch (\Throwable $e) {
            Log::warning('Ecosystem notification dispatch error: ' . $e->getMessage());
        }

        return response()->json([
            'success'    => true,
            'idempotent' => false,
            'message'    => 'Student enrolment successfully provisioned.',
            'data'       => [
                'enrolment_id'        => $enrollment->id,
                'payment_reference'   => $reference,
                'payment_status'      => $enrollStatus,
                'amount_paid'         => $amountPaid,
                'is_new_user'         => $isNewUser,
                'temporary_password'  => $generatedPassword, // Returned once so ZAVORA can display or send via email
                'student'             => [
                    'id'    => $user->id,
                    'name'  => $user->name,
                    'email' => $user->email,
                ],
                'course'              => [
                    'id'    => $course->id,
                    'title' => $course->title,
                    'slug'  => $course->slug,
                ],
                'learning_url'        => route('course.detail', $course->slug),
                'login_url'           => route('login.student'),
                'enrolled_at'         => ($enrollment->created_at ? $enrollment->created_at->toIso8601String() : null) ?: now()->toIso8601String(),
            ],
        ], 201);
    }

    /**
     * Check the status of an enrollment using transaction reference.
     *
     * GET /api/v1/enrolments/status/{reference}
     */
    public function enrolmentStatus(Request $request, string $reference): JsonResponse
    {
        try {
            $enrollment = Enrollment::where('payment_reference', $reference)
                ->with(['course:id,title,slug,thumbnail', 'user:id,name,email'])
                ->first();

            if (!$enrollment) {
                return response()->json([
                    'success' => false,
                    'error'   => 'not_found',
                    'message' => "No enrolment found for reference '{$reference}'.",
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data'    => [
                    'enrolment_id'        => $enrollment->id,
                    'payment_reference'   => $enrollment->payment_reference,
                    'payment_status'      => $enrollment->payment_status,
                    'amount_paid'         => (float) $enrollment->amount_paid,
                    'progress_percentage' => (int) $enrollment->progress_percentage,
                    'completion_date'     => $enrollment->completion_date ? $enrollment->completion_date->toIso8601String() : null,
                    'created_at'          => $enrollment->created_at ? $enrollment->created_at->toIso8601String() : null,
                    'student'             => [
                        'id'    => $enrollment->user->id ?? $enrollment->user_id,
                        'name'  => $enrollment->user->name ?? '',
                        'email' => $enrollment->user->email ?? '',
                    ],
                    'course'              => [
                        'id'            => $enrollment->course->id ?? $enrollment->course_id,
                        'title'         => $enrollment->course->title ?? '',
                        'slug'          => $enrollment->course->slug ?? '',
                        'thumbnail_url' => $enrollment->course ? $enrollment->course->thumbnailUrl() : null,
                    ],
                    'learning_url'        => $enrollment->course ? route('course.detail', $enrollment->course->slug) : null,
                ],
            ]);
        } catch (\Throwable $e) {
            Log::error('Ecosystem enrolment status check error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error'   => 'server_error',
                'message' => 'Unable to check enrolment status.',
            ], 500);
        }
    }

    /**
     * Format a Course model into standard marketplace resource JSON.
     */
    protected function formatCourseSummary(Course $course): array
    {
        $durationHours = round($course->duration_minutes / 60, 1);
        $durationFormatted = $durationHours >= 1
            ? "{$durationHours} " . ($durationHours == 1 ? 'hour' : 'hours')
            : "{$course->duration_minutes} mins";

        return [
            'id'                 => $course->id,
            'title'              => $course->title,
            'slug'               => $course->slug,
            'description'        => $course->description,
            'price'              => (float) $course->price,
            'effective_price'    => $course->effectivePrice(),
            'currency'           => 'NGN',
            'is_preorder'        => (bool) $course->is_preorder,
            'preorder_price'     => $course->preorder_price ? (float) $course->preorder_price : null,
            'preorder_ends_at'   => $course->preorder_ends_at ? $course->preorder_ends_at->toIso8601String() : null,
            'level'              => $course->level ?: 'All Levels',
            'category'           => $course->category ?: 'General',
            'duration_minutes'   => (int) $course->duration_minutes,
            'duration_formatted' => $durationFormatted,
            'thumbnail_url'      => $course->thumbnailUrl(),
            'modules_count'      => (int) ($course->modules_count ?? $course->modules()->count()),
            'lessons_count'      => (int) ($course->lessons_count ?? $course->lessons()->count()),
            'instructor'         => [
                'id'         => $course->instructor ? $course->instructor->id : null,
                'name'       => ($course->instructor && $course->instructor->name) ? $course->instructor->name : 'John Luka Media Instructor',
                'avatar_url' => $course->instructor ? $course->instructor->avatarUrl() : null,
            ],
            'lms_url'            => route('course.detail', $course->slug),
            'published_at'       => $course->published_at ? $course->published_at->toIso8601String() : null,
        ];
    }
}
