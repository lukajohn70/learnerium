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
use App\Http\Middleware\IsInstructor;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public Pages
Route::get('/', function () { return view('home'); });
Route::get('/courses', [CourseController::class, 'index'])->name('courses');
Route::get('/courses/{slug}', [CourseController::class, 'show'])->name('course.detail');
Route::get('/instructors', function () { return view('instructors'); })->name('instructors');
Route::get('/about', function () { return view('about'); })->name('about');
Route::get('/contact', function () { return view('contact'); })->name('contact');

// Authentication Routes (Email Verification Enabled)
Auth::routes(['verify' => true]);

// Dedicated Student Login Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::get('/login/student', [LoginController::class, 'showLoginForm'])->name('login.student');
Route::post('/login/student', [LoginController::class, 'loginStudent'])->name('login.student.post');

// Dedicated Instructor Login Routes
Route::get('/login/instructor', [LoginController::class, 'showInstructorLoginForm'])->name('login.instructor');
Route::post('/login/instructor', [LoginController::class, 'loginInstructor'])->name('login.instructor.post');

// Instructor Registration Routes
Route::get('/register/instructor', [RegisterController::class, 'showInstructorRegistrationForm'])->name('register.instructor');
Route::post('/register/instructor', [RegisterController::class, 'registerInstructor'])->name('register.instructor.post');


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
    Route::get('/settings', function () {
        return view('settings');
    })->name('settings');
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
});

Route::middleware(['auth', 'instructor'])->group(function () {
    // View students enrolled in a course
    Route::get('/instructor/courses/{course}/students', [CourseController::class, 'students'])->name('instructor.courses.students');
    Route::get('/instructor/courses/create', [CourseController::class, 'create'])->name('instructor.courses.create');
    Route::post('/instructor/courses', [CourseController::class, 'store'])->name('instructor.courses.store');
    Route::get('/instructor/courses/{course}/edit', [CourseController::class, 'edit'])->name('instructor.courses.edit');
    Route::put('/instructor/courses/{course}', [CourseController::class, 'update'])->name('instructor.courses.update');
    Route::post('/instructor/courses/{course}/publish', [CourseController::class, 'publish'])->name('instructor.courses.publish');
    
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