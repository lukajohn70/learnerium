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