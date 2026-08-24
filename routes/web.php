<?php

// In routes/web.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\QuizQuestionController;
use App\Http\Controllers\StudentQuizController;
use App\Http\Controllers\LessonTaskController;
use App\Http\Controllers\StudentTaskController;
use App\Http\Controllers\InstructorApplicationController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\LessonDiscussionController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\BankVerificationController;
use App\Http\Middleware\IsInstructor;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public Pages
Route::get('/', function () {
    $featuredCourses = \App\Models\Course::whereNotNull('published_at')
        ->with('instructor')
        ->latest('published_at')
        ->take(6)
        ->get();
    return view('home', compact('featuredCourses'));
})->name('home');
Route::get('/courses', [CourseController::class, 'index'])->name('courses');
Route::get('/courses/{slug}', [CourseController::class, 'show'])->name('course.detail');
Route::get('/instructors', function () { return view('instructors'); })->name('instructors');
Route::get('/about', function () { return view('about'); })->name('about');
Route::get('/contact', function () { return view('contact'); })->name('contact');
Route::get('/privacy-policy', function () { return view('privacy'); })->name('privacy');
Route::get('/terms-of-service', function () { return view('eua'); })->name('eua');

// SEO: Dynamic XML Sitemap
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

// Shopping Cart Routes (Publicly accessible; persists across sessions and logouts)
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/{course}', [CartController::class, 'store'])->name('cart.store');
Route::delete('/cart/{course}', [CartController::class, 'destroy'])->name('cart.destroy');
Route::post('/cart/{course}/wishlist', [CartController::class, 'moveToWishlist'])->name('cart.move-to-wishlist');

// Payment callback - public route (Paystack redirects here)
Route::get('/payment/callback', [PaymentController::class, 'callback'])->name('payment.callback');

// Database Schema Migration Web Route (Smart Synchronizer)
Route::any('/updatedb.php', function () {
    $log = [];
    $status = 'success';

    function logRouteMsg(&$log, $msg, $type = 'info') {
        $icon = $type === 'success' ? '✅' : ($type === 'warn' ? '⚠️' : ($type === 'error' ? '❌' : 'ℹ️'));
        $log[] = "$icon $msg";
    }

    try {
        logRouteMsg($log, "Starting Learnerium Database Sync...");

        // 1. Ensure migrations table exists
        if (!\Illuminate\Support\Facades\Schema::hasTable('migrations')) {
            \Illuminate\Support\Facades\Schema::create('migrations', function (\Illuminate\Database\Schema\Blueprint $table) {
                $table->increments('id');
                $table->string('migration');
                $table->integer('batch');
            });
            logRouteMsg($log, "Created migrations tracking table.", 'success');
        }

        $recordMigration = function($name) use (&$log) {
            if (!\Illuminate\Support\Facades\DB::table('migrations')->where('migration', $name)->exists()) {
                \Illuminate\Support\Facades\DB::table('migrations')->insert(['migration' => $name, 'batch' => 1]);
                logRouteMsg($log, "Marked baseline migration '$name' as completed.");
            }
        };

        // 2. Mark historical base table migrations if tables already exist
        foreach ([
            'users' => '2014_10_12_000000_create_users_table',
            'password_resets' => '2014_10_12_100000_create_password_resets_table',
            'failed_jobs' => '2019_08_19_000000_create_failed_jobs_table',
            'personal_access_tokens' => '2019_12_14_000001_create_personal_access_tokens_table',
            'courses' => '2025_05_31_185625_create_courses_table',
            'lessons' => '2026_01_24_002837_create_lessons_table',
            'lesson_progress' => '2026_01_24_002842_create_lesson_progress_table',
            'quizzes' => '2026_01_24_004000_create_quizzes_table',
            'questions' => '2026_01_24_004002_create_questions_table',
            'quiz_attempts' => '2026_01_24_004002_create_quiz_attempts_table',
            'tasks' => '2026_08_20_122027_create_tasks_table',
            'submissions' => '2026_08_20_122029_create_submissions_table',
            'peer_reviews' => '2026_08_20_122032_create_peer_reviews_table',
            'instructor_applications' => '2026_08_20_163345_create_instructor_applications_table',
            'modules' => '2026_08_20_175142_create_modules_table',
            'module_materials' => '2026_08_20_181018_create_module_materials_table',
            'coupons' => '2026_08_21_104551_create_coupons_table',
            'lesson_discussions' => '2026_08_21_104555_create_lesson_discussions_table',
            'wishlists' => '2026_08_21_115305_create_wishlists_table',
            'cart_items' => '2026_08_21_115307_create_cart_items_table',
        ] as $tbl => $mig) {
            if (\Illuminate\Support\Facades\Schema::hasTable($tbl)) {
                $recordMigration($mig);
            }
        }
        if (\Illuminate\Support\Facades\Schema::hasTable('enrollments')) {
            $recordMigration('2025_06_05_151641_create_enrollments_table');
            $recordMigration('2025_06_05_153002_add_user_id_and_course_id_to_enrollments_table');
        }

        // 3. Ensure Columns
        if (\Illuminate\Support\Facades\Schema::hasTable('users')) {
            \Illuminate\Support\Facades\Schema::table('users', function (\Illuminate\Database\Schema\Blueprint $table) use (&$log) {
                if (!\Illuminate\Support\Facades\Schema::hasColumn('users', 'role')) {
                    $table->string('role', 50)->default('student')->after('password');
                    logRouteMsg($log, "Added column 'role' to users table.", 'success');
                }
                if (!\Illuminate\Support\Facades\Schema::hasColumn('users', 'avatar')) {
                    $table->string('avatar')->nullable()->after('role');
                    logRouteMsg($log, "Added column 'avatar' to users table.", 'success');
                }
                if (!\Illuminate\Support\Facades\Schema::hasColumn('users', 'bank_name')) {
                    $table->string('bank_name')->nullable()->after('email');
                    logRouteMsg($log, "Added column 'bank_name' to users table.", 'success');
                }
                if (!\Illuminate\Support\Facades\Schema::hasColumn('users', 'bank_code')) {
                    $table->string('bank_code', 20)->nullable()->after('bank_name');
                    logRouteMsg($log, "Added column 'bank_code' to users table.", 'success');
                }
                if (!\Illuminate\Support\Facades\Schema::hasColumn('users', 'account_number')) {
                    $table->string('account_number')->nullable()->after('bank_code');
                    logRouteMsg($log, "Added column 'account_number' to users table.", 'success');
                }
                if (!\Illuminate\Support\Facades\Schema::hasColumn('users', 'account_name')) {
                    $table->string('account_name')->nullable()->after('account_number');
                    logRouteMsg($log, "Added column 'account_name' to users table.", 'success');
                }
                if (!\Illuminate\Support\Facades\Schema::hasColumn('users', 'payout_requested_at')) {
                    $table->string('payout_requested_at')->nullable()->after('account_name');
                    logRouteMsg($log, "Added column 'payout_requested_at' to users table.", 'success');
                }
            });
            $recordMigration('2025_06_05_155614_add_role_to_users_table');
            $recordMigration('2026_08_20_181033_add_avatar_to_users_table');
            $recordMigration('2026_08_24_163500_add_bank_details_to_users');
            $recordMigration('2026_08_24_165000_add_bank_code_to_users');
        }


        if (\Illuminate\Support\Facades\Schema::hasTable('courses')) {
            \Illuminate\Support\Facades\Schema::table('courses', function (\Illuminate\Database\Schema\Blueprint $table) use (&$log) {
                if (!\Illuminate\Support\Facades\Schema::hasColumn('courses', 'category')) {
                    $table->string('category')->nullable()->after('description');
                    logRouteMsg($log, "Added column 'category' to courses table.", 'success');
                }
            });
            $recordMigration('2026_08_20_182351_add_category_to_courses_table');
        }

        if (\Illuminate\Support\Facades\Schema::hasTable('enrollments')) {
            \Illuminate\Support\Facades\Schema::table('enrollments', function (\Illuminate\Database\Schema\Blueprint $table) use (&$log) {
                if (!\Illuminate\Support\Facades\Schema::hasColumn('enrollments', 'progress_percentage')) {
                    $table->integer('progress_percentage')->default(0)->after('completion_date');
                    logRouteMsg($log, "Added column 'progress_percentage' to enrollments table.", 'success');
                }
                if (!\Illuminate\Support\Facades\Schema::hasColumn('enrollments', 'payment_status')) {
                    $table->string('payment_status')->default('pending')->after('progress_percentage');
                    logRouteMsg($log, "Added column 'payment_status' to enrollments table.", 'success');
                }
                if (!\Illuminate\Support\Facades\Schema::hasColumn('enrollments', 'amount_paid')) {
                    $table->decimal('amount_paid', 10, 2)->default(0.00)->after('payment_status');
                    logRouteMsg($log, "Added column 'amount_paid' to enrollments table.", 'success');
                }
                if (!\Illuminate\Support\Facades\Schema::hasColumn('enrollments', 'instructor_share')) {
                    $table->decimal('instructor_share', 12, 2)->default(0)->after('amount_paid');
                    logRouteMsg($log, "Added column 'instructor_share' to enrollments table.", 'success');
                }
                if (!\Illuminate\Support\Facades\Schema::hasColumn('enrollments', 'platform_share')) {
                    $table->decimal('platform_share', 12, 2)->default(0)->after('instructor_share');
                    logRouteMsg($log, "Added column 'platform_share' to enrollments table.", 'success');
                }
                if (!\Illuminate\Support\Facades\Schema::hasColumn('enrollments', 'payout_status')) {
                    $table->string('payout_status')->default('pending')->after('platform_share');
                    logRouteMsg($log, "Added column 'payout_status' to enrollments table.", 'success');
                }
                if (!\Illuminate\Support\Facades\Schema::hasColumn('enrollments', 'coupon_code')) {
                    $table->string('coupon_code')->nullable()->after('payout_status');
                    logRouteMsg($log, "Added column 'coupon_code' to enrollments table.", 'success');
                }
                if (!\Illuminate\Support\Facades\Schema::hasColumn('enrollments', 'payment_reference')) {
                    $table->string('payment_reference')->nullable()->after('coupon_code');
                    logRouteMsg($log, "Added column 'payment_reference' to enrollments table.", 'success');
                }
            });
            $recordMigration('2026_01_24_010000_add_progress_percentage_to_enrollments_table');
            $recordMigration('2026_08_21_104547_update_enrollments_table_for_payments');
            $recordMigration('2026_08_24_154500_add_revenue_split_to_enrollments');
        }

        if (\Illuminate\Support\Facades\Schema::hasTable('lessons')) {
            \Illuminate\Support\Facades\Schema::table('lessons', function (\Illuminate\Database\Schema\Blueprint $table) use (&$log) {
                if (!\Illuminate\Support\Facades\Schema::hasColumn('lessons', 'module_id')) {
                    $table->unsignedBigInteger('module_id')->nullable()->after('course_id');
                    logRouteMsg($log, "Added column 'module_id' to lessons table.", 'success');
                }
            });
            $recordMigration('2026_08_20_175303_add_module_id_to_lessons_table');
        }

        if (\Illuminate\Support\Facades\Schema::hasTable('coupons')) {
            \Illuminate\Support\Facades\Schema::table('coupons', function (\Illuminate\Database\Schema\Blueprint $table) use (&$log) {
                if (!\Illuminate\Support\Facades\Schema::hasColumn('coupons', 'max_uses')) {
                    $table->unsignedInteger('max_uses')->nullable()->after('active');
                    logRouteMsg($log, "Added column 'max_uses' to coupons table.", 'success');
                }
                if (!\Illuminate\Support\Facades\Schema::hasColumn('coupons', 'used_count')) {
                    $table->unsignedInteger('used_count')->default(0)->after('max_uses');
                    logRouteMsg($log, "Added column 'used_count' to coupons table.", 'success');
                }
            });
            $recordMigration('2026_08_21_123224_add_max_uses_and_used_count_to_coupons_table');
        }

        // 4. Ensure PLATFORM_SETTINGS table
        if (!\Illuminate\Support\Facades\Schema::hasTable('platform_settings')) {
            \Illuminate\Support\Facades\Schema::create('platform_settings', function (\Illuminate\Database\Schema\Blueprint $table) {
                $table->id();
                $table->string('key')->unique();
                $table->text('value')->nullable();
                $table->string('type')->default('string');
                $table->string('label')->nullable();
                $table->timestamps();
            });
            \Illuminate\Support\Facades\DB::table('platform_settings')->insert([
                ['key' => 'instructor_revenue_share', 'value' => '70', 'type' => 'decimal', 'label' => 'Instructor Revenue Share (%)', 'created_at' => now(), 'updated_at' => now()],
                ['key' => 'platform_revenue_share',   'value' => '30', 'type' => 'decimal', 'label' => 'Platform Revenue Share (%)',   'created_at' => now(), 'updated_at' => now()],
            ]);
            logRouteMsg($log, "Created 'platform_settings' table and seeded 70/30 default split.", 'success');
        }
        $recordMigration('2026_08_24_154501_create_platform_settings_table');

        // 5. Ensure APP_NOTIFICATIONS & NOTIFICATION_PREFERENCES
        if (!\Illuminate\Support\Facades\Schema::hasTable('app_notifications')) {
            \Illuminate\Support\Facades\Schema::create('app_notifications', function (\Illuminate\Database\Schema\Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->string('type');
                $table->string('title');
                $table->text('message');
                $table->string('action_url')->nullable();
                $table->string('icon')->default('fa-bell');
                $table->string('color')->default('blue');
                $table->boolean('is_read')->default(false);
                $table->timestamps();
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->index(['user_id', 'is_read']);
            });
            logRouteMsg($log, "Created 'app_notifications' table.", 'success');
        }

        if (!\Illuminate\Support\Facades\Schema::hasTable('notification_preferences')) {
            \Illuminate\Support\Facades\Schema::create('notification_preferences', function (\Illuminate\Database\Schema\Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->unique();
                $table->boolean('email_enrollment')->default(true);
                $table->boolean('email_payment')->default(true);
                $table->boolean('email_course_updates')->default(true);
                $table->boolean('email_new_student')->default(true);
                $table->boolean('email_payout')->default(true);
                $table->boolean('email_announcements')->default(true);
                $table->boolean('email_marketing')->default(false);
                $table->boolean('inapp_enrollment')->default(true);
                $table->boolean('inapp_payment')->default(true);
                $table->boolean('inapp_course_updates')->default(true);
                $table->boolean('inapp_announcements')->default(true);
                $table->timestamps();
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
            logRouteMsg($log, "Created 'notification_preferences' table.", 'success');
        }
        $recordMigration('2026_08_24_161000_create_notifications_table');

        // 6. Run remaining Laravel migrations
        try {
            \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
            $migOutput = \Illuminate\Support\Facades\Artisan::output();
            if (trim($migOutput)) {
                logRouteMsg($log, "Artisan Output: " . trim($migOutput));
            }
        } catch (\Throwable $migEx) {
            logRouteMsg($log, "Note: " . $migEx->getMessage(), 'warn');
        }

        // 7. Clear caches
        try {
            \Illuminate\Support\Facades\Artisan::call('view:clear');
            \Illuminate\Support\Facades\Artisan::call('route:clear');
            \Illuminate\Support\Facades\Artisan::call('config:clear');
            logRouteMsg($log, "Cleared view, route, and config caches.", 'success');
        } catch (\Throwable $cEx) {}

        logRouteMsg($log, "Database schema is 100% synchronized and up-to-date!", 'success');

    } catch (\Throwable $e) {
        $status = 'error';
        logRouteMsg($log, "Error: " . $e->getMessage(), 'error');
        logRouteMsg($log, $e->getTraceAsString(), 'error');
    }

    $output = implode("\n", $log);
    return response()->view('updatedb-view', compact('status', 'output'));
});
Route::any('/updatedb', function () {
    return redirect('/updatedb.php');
});


// Media / Uploads file serving route (Guarantees online image delivery on cPanel shared hosting)
Route::get('/uploads/{folder}/{filename}', function ($folder, $filename) {
    $path = public_path("uploads/{$folder}/{$filename}");
    if (file_exists($path)) {
        return response()->file($path);
    }
    $rootPath = base_path("public/uploads/{$folder}/{$filename}");
    if (file_exists($rootPath)) {
        return response()->file($rootPath);
    }
    abort(404);
})->where('folder', 'thumbnails|avatars|materials');

// Authentication Routes (Email Verification Enabled)
Auth::routes(['verify' => true]);

// Dedicated Student Login Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::get('/login/student', [LoginController::class, 'showLoginForm'])->name('login.student');
Route::post('/login/student', [LoginController::class, 'loginStudent'])->middleware('throttle:5,1')->name('login.student.post');

// Dedicated Instructor Login Routes
Route::get('/login/instructor', [LoginController::class, 'showInstructorLoginForm'])->name('login.instructor');
Route::post('/login/instructor', [LoginController::class, 'loginInstructor'])->middleware('throttle:5,1')->name('login.instructor.post');

// Admin Login Routes (subtle / unlisted)
Route::get('/login/admin', [LoginController::class, 'showAdminLoginForm'])->name('login.admin');
Route::post('/login/admin', [LoginController::class, 'loginAdmin'])->middleware('throttle:5,1')->name('login.admin.post');


// Instructor Application & Verification Routes
Route::get('/apply-instructor', [InstructorApplicationController::class, 'showForm'])->name('instructor.apply');
Route::post('/apply-instructor', [InstructorApplicationController::class, 'submit'])->middleware('throttle:5,1')->name('instructor.apply.submit');

Route::get('/register/instructor', [InstructorApplicationController::class, 'showForm'])->name('register.instructor');
Route::post('/register/instructor', [InstructorApplicationController::class, 'submit'])->middleware('throttle:5,1')->name('register.instructor.post');

// --- ADMIN ROUTES ---
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/users', [AdminDashboardController::class, 'users'])->name('users');
    Route::post('/users/{user}/role', [AdminDashboardController::class, 'updateUserRole'])->name('users.role');
    Route::get('/courses', [AdminDashboardController::class, 'courses'])->name('courses');
    Route::post('/courses/{course}/toggle-publish', [AdminDashboardController::class, 'toggleCoursePublish'])->name('courses.toggle');
    Route::get('/coupons', [AdminDashboardController::class, 'coupons'])->name('coupons');
    Route::post('/coupons', [AdminDashboardController::class, 'storeCoupon'])->name('coupons.store');
    Route::delete('/coupons/{coupon}', [AdminDashboardController::class, 'destroyCoupon'])->name('coupons.destroy');
    Route::get('/payments', [AdminDashboardController::class, 'payments'])->name('payments');
    Route::get('/instructor-applications', [InstructorApplicationController::class, 'index'])->name('instructor.applications');
    Route::post('/instructor-applications/{application}/approve', [InstructorApplicationController::class, 'approve'])->name('instructor.applications.approve');
    Route::post('/instructor-applications/{application}/reject', [InstructorApplicationController::class, 'reject'])->name('instructor.applications.reject');
    Route::post('/payouts/{instructor}/mark-paid', [AdminDashboardController::class, 'markInstructorPaid'])->name('payouts.mark-paid');
    Route::post('/settings', [AdminDashboardController::class, 'updateSettings'])->name('settings.update');
});

// Legacy admin routes — secured with auth + admin middleware
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/instructor-applications', [InstructorApplicationController::class, 'index'])->name('admin.instructor.applications');
    Route::post('/admin/instructor-applications/{application}/approve', [InstructorApplicationController::class, 'approve'])->name('admin.instructor.applications.approve');
    Route::post('/admin/instructor-applications/{application}/reject', [InstructorApplicationController::class, 'reject'])->name('admin.instructor.applications.reject');
});

// Dashboard Routes (Protected & Email Verified)
Route::middleware(['auth', 'verified'])->group(function () {
    // Student Quiz Routes
    Route::get('/courses/{course}/lessons/{lesson}/quizzes/{quiz}', [StudentQuizController::class, 'show'])->name('student.quiz.show');
    Route::post('/courses/{course}/lessons/{lesson}/quizzes/{quiz}/submit', [StudentQuizController::class, 'submit'])->name('student.quiz.submit');
    Route::get('/courses/{course}/lessons/{lesson}/quizzes/{quiz}/result', [StudentQuizController::class, 'result'])->name('student.quiz.result');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/switch-role', [DashboardController::class, 'switchRole'])->name('switch.role');
    Route::get('/profile', function () {
        return view('profile');
    })->name('profile');
    Route::post('/profile/avatar', [DashboardController::class, 'updateAvatar'])->name('profile.avatar');
    Route::get('/settings', [DashboardController::class, 'settings'])->name('settings');
    Route::post('/settings/profile', [DashboardController::class, 'updateProfile'])->name('settings.profile');
    Route::post('/settings/password', [DashboardController::class, 'updatePassword'])->name('settings.password');

    Route::get('/student/dashboard', [DashboardController::class, 'studentDashboard'])
         ->name('student.dashboard');
    Route::get('/dashboard/courses', function() {
        $courses = auth()->user()->coursesEnrolled()->with('instructor')->get();
        return view('student.courses', compact('courses'));
    })->name('student.courses');
    Route::get('/dashboard/progress', function() {
        $courses = auth()->user()->coursesEnrolled()->withPivot('progress_percentage')->with('instructor')->get();
        return view('student.progress', compact('courses'));
    })->name('student.progress');
    Route::get('/dashboard/certificates', function() {
        $courses = auth()->user()->coursesEnrolled()->withPivot('progress_percentage')->get();
        return view('student.certificates', compact('courses'));
    })->name('student.certificates');
    Route::get('/courses/{course}/certificate', function(\App\Models\Course $course) {
        $user = auth()->user();
        $enrollment = $user->enrollments()->where('course_id', $course->id)->first();
        if (!$enrollment || $enrollment->progress_percentage < 100) {
            return redirect()->route('course.detail', $course->slug)->with('error', 'You must reach 100% course completion to generate your certificate.');
        }
        return view('student.certificate-view', compact('course', 'user', 'enrollment'));
    })->name('student.certificate.view');

    Route::get('/instructor/dashboard', [DashboardController::class, 'instructorDashboard'])
         ->middleware(IsInstructor::class)
         ->name('instructor.dashboard');
    Route::get('/instructor/manage-courses', [CourseController::class, 'manage'])
         ->middleware(IsInstructor::class)
         ->name('instructor.manage.courses');
    Route::get('/instructor/student-analytics', function() {
        return view('instructor.student-analytics');
    })->middleware(IsInstructor::class)
      ->name('instructor.student.analytics');
    Route::post('/instructor/bank-details', [DashboardController::class, 'updateBankDetails'])
         ->middleware(IsInstructor::class)
         ->name('instructor.bank-details.update');
    Route::post('/instructor/payout/request', [DashboardController::class, 'requestPayout'])
         ->middleware(IsInstructor::class)
         ->name('instructor.payout.request');
    Route::get('/instructor/submissions', [DashboardController::class, 'instructorSubmissions'])
         ->middleware(IsInstructor::class)
         ->name('instructor.submissions');

    // Checkout & Payment Routes
    Route::get('/courses/{course}/checkout', [PaymentController::class, 'checkout'])->name('courses.checkout');
    Route::post('/courses/{course}/checkout/initialize', [PaymentController::class, 'initialize'])->name('courses.checkout.initialize');
    Route::post('/courses/{course}/checkout/coupon', [PaymentController::class, 'applyCoupon'])->name('courses.checkout.coupon');

    // Lesson Discussion Routes
    Route::post('/courses/{course}/lessons/{lesson}/discussions', [LessonDiscussionController::class, 'store'])->name('lesson.discussion.store');
    Route::delete('/discussions/{discussion}', [LessonDiscussionController::class, 'destroy'])->name('lesson.discussion.destroy');

    // Wishlist Routes
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/{course}/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::delete('/wishlist/{course}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');
    Route::post('/wishlist/{course}/cart', [WishlistController::class, 'moveToCart'])->name('wishlist.move-to-cart');

    // Notification Routes
    Route::get('/notifications/count', [NotificationController::class, 'count'])->name('notifications.count');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.read-all');
    Route::get('/settings/notifications', [NotificationController::class, 'preferences'])->name('notifications.preferences');
    Route::post('/settings/notifications', [NotificationController::class, 'savePreferences'])->name('notifications.save');

    // Bank Verification & Account Resolution APIs
    Route::get('/api/banks', [BankVerificationController::class, 'getBanks'])->name('api.banks');
    Route::post('/api/banks/resolve', [BankVerificationController::class, 'resolveAccount'])->name('api.banks.resolve');
});


Route::middleware(['auth', 'instructor'])->group(function () {
    // View students enrolled in a course
    Route::get('/instructor/courses/{course}/students', [CourseController::class, 'students'])->name('instructor.courses.students');
    Route::get('/instructor/courses/create', [CourseController::class, 'create'])->name('instructor.courses.create');
    Route::post('/instructor/courses', [CourseController::class, 'store'])->name('instructor.courses.store');
    Route::get('/instructor/courses/{course}/edit', [CourseController::class, 'edit'])->name('instructor.courses.edit');
    Route::put('/instructor/courses/{course}', [CourseController::class, 'update'])->name('instructor.courses.update');
    Route::delete('/instructor/courses/{course}', [CourseController::class, 'destroy'])->name('instructor.courses.destroy');
    Route::post('/instructor/courses/{course}/publish', [CourseController::class, 'publish'])->name('instructor.courses.publish');

    // Module Routes (instructor only)
    Route::post('/instructor/courses/{course}/modules', [ModuleController::class, 'store'])->name('instructor.modules.store');
    Route::put('/instructor/courses/{course}/modules/{module}', [ModuleController::class, 'update'])->name('instructor.modules.update');
    Route::delete('/instructor/courses/{course}/modules/{module}', [ModuleController::class, 'destroy'])->name('instructor.modules.destroy');
    Route::post('/instructor/courses/{course}/modules/{module}/materials', [ModuleController::class, 'addMaterial'])->name('instructor.modules.materials.store');
    Route::delete('/instructor/courses/{course}/modules/{module}/materials/{material}', [ModuleController::class, 'deleteMaterial'])->name('instructor.modules.materials.destroy');

    // Lesson Routes (instructor only)
    Route::post('/instructor/courses/{course}/lessons', [LessonController::class, 'store'])->name('instructor.lessons.store');
    Route::put('/instructor/courses/{course}/lessons/{lesson}', [LessonController::class, 'update'])->name('instructor.lessons.update');
    Route::delete('/instructor/courses/{course}/lessons/{lesson}', [LessonController::class, 'destroy'])->name('instructor.lessons.destroy');

    // Quiz Routes (instructor only)
    Route::resource('/instructor/lessons.quizzes', QuizController::class);
    Route::get('/instructor/quizzes/{quiz}/analytics', [QuizController::class, 'analytics'])->name('instructor.quizzes.analytics');

    // Question Routes (instructor only)
    Route::resource('/instructor/quizzes.questions', QuizQuestionController::class);

    // Lesson Task Routes (instructor only)
    Route::resource('/instructor/lessons.tasks', LessonTaskController::class);
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/courses/{course}/enroll', [EnrollmentController::class, 'store'])->name('courses.enroll');

    // Lesson Routes
    Route::get('/courses/{course}/lessons/{lesson}', [LessonController::class, 'show'])->name('lesson.show');
    Route::post('/courses/{course}/lessons/{lesson}/complete', [LessonController::class, 'markComplete'])->name('lesson.complete');

    // Student Task Submission Routes
    Route::post('/tasks/{task}/submit', [StudentTaskController::class, 'store'])->name('student.tasks.submit');
    Route::post('/submissions/{submission}/peer-review', [StudentTaskController::class, 'submitPeerReview'])->name('student.tasks.peer-review');
    Route::post('/tasks/{task}/approve-submission/{submission}', [LessonTaskController::class, 'approveSubmission'])->name('instructor.tasks.approve');
    Route::post('/tasks/{task}/reject-submission/{submission}', [LessonTaskController::class, 'rejectSubmission'])->name('instructor.tasks.reject');
});

Route::get('/home', function () {
    return redirect()->route('dashboard');
});