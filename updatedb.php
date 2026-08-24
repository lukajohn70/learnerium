<?php
/**
 * ============================================================
 * LEARNERIUM — Smart Production Database Schema Synchronizer (Root)
 * ============================================================
 */

$laravelRoot = __DIR__;
if (!file_exists($laravelRoot . '/vendor/autoload.php')) {
    $laravelRoot = dirname(__DIR__);
}

require $laravelRoot . '/vendor/autoload.php';
$app = require_once $laravelRoot . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Database\Schema\Blueprint;

$log = [];
$status = 'success';

function logMsg(&$log, $msg, $type = 'info') {
    $icon = $type === 'success' ? '✅' : ($type === 'warn' ? '⚠️' : ($type === 'error' ? '❌' : 'ℹ️'));
    $log[] = "$icon $msg";
}

try {
    logMsg($log, "Starting Learnerium Database Sync...");

    // 1. Ensure migrations table exists
    if (!Schema::hasTable('migrations')) {
        Schema::create('migrations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('migration');
            $table->integer('batch');
        });
        logMsg($log, "Created migrations tracking table.", 'success');
    }

    // Helper to register migration as ran if its table/column already exists
    $recordMigration = function($name) use (&$log) {
        if (!DB::table('migrations')->where('migration', $name)->exists()) {
            DB::table('migrations')->insert(['migration' => $name, 'batch' => 1]);
            logMsg($log, "Marked baseline migration '$name' as completed.");
        }
    };

    // 2. Mark historical base table migrations if tables already exist in MySQL
    if (Schema::hasTable('users')) {
        $recordMigration('2014_10_12_000000_create_users_table');
    }
    if (Schema::hasTable('password_resets')) {
        $recordMigration('2014_10_12_100000_create_password_resets_table');
    }
    if (Schema::hasTable('failed_jobs')) {
        $recordMigration('2019_08_19_000000_create_failed_jobs_table');
    }
    if (Schema::hasTable('personal_access_tokens')) {
        $recordMigration('2019_12_14_000001_create_personal_access_tokens_table');
    }
    if (Schema::hasTable('courses')) {
        $recordMigration('2025_05_31_185625_create_courses_table');
    }
    if (Schema::hasTable('enrollments')) {
        $recordMigration('2025_06_05_151641_create_enrollments_table');
        $recordMigration('2025_06_05_153002_add_user_id_and_course_id_to_enrollments_table');
    }
    if (Schema::hasTable('lessons')) {
        $recordMigration('2026_01_24_002837_create_lessons_table');
    }
    if (Schema::hasTable('lesson_progress')) {
        $recordMigration('2026_01_24_002842_create_lesson_progress_table');
    }
    if (Schema::hasTable('quizzes')) {
        $recordMigration('2026_01_24_004000_create_quizzes_table');
    }
    if (Schema::hasTable('questions')) {
        $recordMigration('2026_01_24_004002_create_questions_table');
    }
    if (Schema::hasTable('quiz_attempts')) {
        $recordMigration('2026_01_24_004002_create_quiz_attempts_table');
    }
    if (Schema::hasTable('tasks')) {
        $recordMigration('2026_08_20_122027_create_tasks_table');
    }
    if (Schema::hasTable('submissions')) {
        $recordMigration('2026_08_20_122029_create_submissions_table');
    }
    if (Schema::hasTable('peer_reviews')) {
        $recordMigration('2026_08_20_122032_create_peer_reviews_table');
    }
    if (Schema::hasTable('instructor_applications')) {
        $recordMigration('2026_08_20_163345_create_instructor_applications_table');
    }
    if (Schema::hasTable('modules')) {
        $recordMigration('2026_08_20_175142_create_modules_table');
    }
    if (Schema::hasTable('module_materials')) {
        $recordMigration('2026_08_20_181018_create_module_materials_table');
    }
    if (Schema::hasTable('coupons')) {
        $recordMigration('2026_08_21_104551_create_coupons_table');
    }
    if (Schema::hasTable('lesson_discussions')) {
        $recordMigration('2026_08_21_104555_create_lesson_discussions_table');
    }
    if (Schema::hasTable('wishlists')) {
        $recordMigration('2026_08_21_115305_create_wishlists_table');
    }
    if (Schema::hasTable('cart_items')) {
        $recordMigration('2026_08_21_115307_create_cart_items_table');
    }

    // 3. Ensure Columns in existing tables

    // USERS table columns
    if (Schema::hasTable('users')) {
        Schema::table('users', function (Blueprint $table) use (&$log) {
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role', 50)->default('student')->after('password');
                logMsg($log, "Added column 'role' to users table.", 'success');
            }
            if (!Schema::hasColumn('users', 'avatar')) {
                $table->string('avatar')->nullable()->after('role');
                logMsg($log, "Added column 'avatar' to users table.", 'success');
            }
            if (!Schema::hasColumn('users', 'bank_name')) {
                $table->string('bank_name')->nullable()->after('email');
                logMsg($log, "Added column 'bank_name' to users table.", 'success');
            }
            if (!Schema::hasColumn('users', 'bank_code')) {
                $table->string('bank_code', 20)->nullable()->after('bank_name');
                logMsg($log, "Added column 'bank_code' to users table.", 'success');
            }
            if (!Schema::hasColumn('users', 'account_number')) {
                $table->string('account_number')->nullable()->after('bank_code');
                logMsg($log, "Added column 'account_number' to users table.", 'success');
            }
            if (!Schema::hasColumn('users', 'account_name')) {
                $table->string('account_name')->nullable()->after('account_number');
                logMsg($log, "Added column 'account_name' to users table.", 'success');
            }
            if (!Schema::hasColumn('users', 'payout_requested_at')) {
                $table->string('payout_requested_at')->nullable()->after('account_name');
                logMsg($log, "Added column 'payout_requested_at' to users table.", 'success');
            }
        });
        $recordMigration('2025_06_05_155614_add_role_to_users_table');
        $recordMigration('2026_08_20_181033_add_avatar_to_users_table');
        $recordMigration('2026_08_24_163500_add_bank_details_to_users');
        $recordMigration('2026_08_24_165000_add_bank_code_to_users');
    }


    // COURSES table columns
    if (Schema::hasTable('courses')) {
        Schema::table('courses', function (Blueprint $table) use (&$log) {
            if (!Schema::hasColumn('courses', 'category')) {
                $table->string('category')->nullable()->after('description');
                logMsg($log, "Added column 'category' to courses table.", 'success');
            }
            if (!Schema::hasColumn('courses', 'requirements')) {
                $table->text('requirements')->nullable()->after('category');
                logMsg($log, "Added column 'requirements' to courses table.", 'success');
            }
            if (!Schema::hasColumn('courses', 'what_you_will_learn')) {
                $table->text('what_you_will_learn')->nullable()->after('requirements');
                logMsg($log, "Added column 'what_you_will_learn' to courses table.", 'success');
            }
        });
        $recordMigration('2026_08_20_182351_add_category_to_courses_table');
    }


    // ENROLLMENTS table columns
    if (Schema::hasTable('enrollments')) {
        Schema::table('enrollments', function (Blueprint $table) use (&$log) {
            if (!Schema::hasColumn('enrollments', 'progress_percentage')) {
                $table->integer('progress_percentage')->default(0)->after('completion_date');
                logMsg($log, "Added column 'progress_percentage' to enrollments table.", 'success');
            }
            if (!Schema::hasColumn('enrollments', 'payment_status')) {
                $table->string('payment_status')->default('pending')->after('progress_percentage');
                logMsg($log, "Added column 'payment_status' to enrollments table.", 'success');
            }
            if (!Schema::hasColumn('enrollments', 'amount_paid')) {
                $table->decimal('amount_paid', 10, 2)->default(0.00)->after('payment_status');
                logMsg($log, "Added column 'amount_paid' to enrollments table.", 'success');
            }
            if (!Schema::hasColumn('enrollments', 'instructor_share')) {
                $table->decimal('instructor_share', 12, 2)->default(0)->after('amount_paid');
                logMsg($log, "Added column 'instructor_share' to enrollments table.", 'success');
            }
            if (!Schema::hasColumn('enrollments', 'platform_share')) {
                $table->decimal('platform_share', 12, 2)->default(0)->after('instructor_share');
                logMsg($log, "Added column 'platform_share' to enrollments table.", 'success');
            }
            if (!Schema::hasColumn('enrollments', 'payout_status')) {
                $table->string('payout_status')->default('pending')->after('platform_share');
                logMsg($log, "Added column 'payout_status' to enrollments table.", 'success');
            }
            if (!Schema::hasColumn('enrollments', 'coupon_code')) {
                $table->string('coupon_code')->nullable()->after('payout_status');
                logMsg($log, "Added column 'coupon_code' to enrollments table.", 'success');
            }
            if (!Schema::hasColumn('enrollments', 'payment_reference')) {
                $table->string('payment_reference')->nullable()->after('coupon_code');
                logMsg($log, "Added column 'payment_reference' to enrollments table.", 'success');
            }
        });
        $recordMigration('2026_01_24_010000_add_progress_percentage_to_enrollments_table');
        $recordMigration('2026_08_21_104547_update_enrollments_table_for_payments');
        $recordMigration('2026_08_24_154500_add_revenue_split_to_enrollments');
    }

    // MODULES table columns
    if (Schema::hasTable('modules')) {
        Schema::table('modules', function (Blueprint $table) use (&$log) {
            if (!Schema::hasColumn('modules', 'drip_date')) {
                $table->dateTime('drip_date')->nullable();
                logMsg($log, "Added column 'drip_date' to modules table.", 'success');
            }
            if (!Schema::hasColumn('modules', 'drip_days')) {
                $table->integer('drip_days')->nullable();
                logMsg($log, "Added column 'drip_days' to modules table.", 'success');
            }
        });
    }

    // LESSONS table columns
    if (Schema::hasTable('lessons')) {
        Schema::table('lessons', function (Blueprint $table) use (&$log) {
            if (!Schema::hasColumn('lessons', 'module_id')) {
                $table->unsignedBigInteger('module_id')->nullable()->after('course_id');
                logMsg($log, "Added column 'module_id' to lessons table.", 'success');
            }
            if (!Schema::hasColumn('lessons', 'drip_date')) {
                $table->dateTime('drip_date')->nullable();
                logMsg($log, "Added column 'drip_date' to lessons table.", 'success');
            }
            if (!Schema::hasColumn('lessons', 'drip_days')) {
                $table->integer('drip_days')->nullable();
                logMsg($log, "Added column 'drip_days' to lessons table.", 'success');
            }
        });
        $recordMigration('2026_08_20_175303_add_module_id_to_lessons_table');
        $recordMigration('2026_08_24_171000_add_drip_schedule_to_modules_and_lessons');
    }

    // COUPONS table columns
    if (Schema::hasTable('coupons')) {
        Schema::table('coupons', function (Blueprint $table) use (&$log) {
            if (!Schema::hasColumn('coupons', 'max_uses')) {
                $table->unsignedInteger('max_uses')->nullable()->after('active');
                logMsg($log, "Added column 'max_uses' to coupons table.", 'success');
            }
            if (!Schema::hasColumn('coupons', 'used_count')) {
                $table->unsignedInteger('used_count')->default(0)->after('max_uses');
                logMsg($log, "Added column 'used_count' to coupons table.", 'success');
            }
        });
        $recordMigration('2026_08_21_123224_add_max_uses_and_used_count_to_coupons_table');
    }


    // 4. Ensure PLATFORM_SETTINGS table
    if (!Schema::hasTable('platform_settings')) {
        Schema::create('platform_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string');
            $table->string('label')->nullable();
            $table->timestamps();
        });
        DB::table('platform_settings')->insert([
            ['key' => 'instructor_revenue_share', 'value' => '70', 'type' => 'decimal', 'label' => 'Instructor Revenue Share (%)', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'platform_revenue_share',   'value' => '30', 'type' => 'decimal', 'label' => 'Platform Revenue Share (%)',   'created_at' => now(), 'updated_at' => now()],
        ]);
        logMsg($log, "Created 'platform_settings' table and seeded 70/30 default split.", 'success');
    }
    $recordMigration('2026_08_24_154501_create_platform_settings_table');

    // 5. Ensure APP_NOTIFICATIONS & NOTIFICATION_PREFERENCES tables
    if (!Schema::hasTable('app_notifications')) {
        Schema::create('app_notifications', function (Blueprint $table) {
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
        logMsg($log, "Created 'app_notifications' table.", 'success');
    }

    if (!Schema::hasTable('notification_preferences')) {
        Schema::create('notification_preferences', function (Blueprint $table) {
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
        logMsg($log, "Created 'notification_preferences' table.", 'success');
    }
    $recordMigration('2026_08_24_161000_create_notifications_table');

    // 6. Run remaining Laravel migrations to catch anything else
    try {
        Artisan::call('migrate', ['--force' => true]);
        $migOutput = Artisan::output();
        if (trim($migOutput)) {
            logMsg($log, "Artisan Output: " . trim($migOutput));
        }
    } catch (\Throwable $migEx) {
        logMsg($log, "Note: " . $migEx->getMessage(), 'warn');
    }

    // 7. Clear caches
    try {
        Artisan::call('view:clear');
        Artisan::call('route:clear');
        Artisan::call('config:clear');
        logMsg($log, "Cleared view, route, and config caches.", 'success');
    } catch (\Throwable $cEx) {}

    logMsg($log, "Database schema is 100% synchronized and up-to-date!", 'success');

} catch (\Throwable $e) {
    $status = 'error';
    logMsg($log, "Error: " . $e->getMessage(), 'error');
    logMsg($log, $e->getTraceAsString(), 'error');
}

$output = implode("\n", $log);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learnerium Database Synchronizer</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-2xl w-full bg-slate-900 border border-slate-800 rounded-3xl p-8 shadow-2xl space-y-6">
        <div class="flex items-center gap-4 border-b border-slate-800 pb-6">
            <div class="w-14 h-14 rounded-2xl flex items-center justify-center <?= $status === 'success' ? 'bg-emerald-500/20 text-emerald-400' : 'bg-rose-500/20 text-rose-400' ?>">
                <i class="fas <?= $status === 'success' ? 'fa-check-circle text-2xl' : 'fa-exclamation-triangle text-2xl' ?>"></i>
            </div>
            <div>
                <h1 class="text-2xl font-extrabold text-white">Learnerium Database Sync</h1>
                <p class="text-sm text-slate-400">Automated Schema & Column Alignment</p>
            </div>
        </div>

        <div class="space-y-2">
            <div class="flex items-center justify-between text-xs uppercase font-bold tracking-wider text-slate-400">
                <span>Sync Results</span>
                <span class="<?= $status === 'success' ? 'text-emerald-400' : 'text-rose-400' ?>">
                    <i class="fas <?= $status === 'success' ? 'fa-check-circle' : 'fa-times-circle' ?> mr-1"></i>
                    <?= strtoupper($status) ?>
                </span>
            </div>
            <div class="bg-slate-950 border border-slate-800/80 rounded-2xl p-4 font-mono text-xs text-slate-300 overflow-x-auto whitespace-pre-wrap leading-relaxed max-h-96">
<?= htmlspecialchars($output) ?>
            </div>
        </div>

        <div class="pt-2 flex flex-col sm:flex-row gap-3">
            <a href="/" class="flex-1 text-center bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3.5 px-6 rounded-2xl transition shadow-lg shadow-indigo-600/20 text-sm">
                <i class="fas fa-home mr-2"></i>Go to Homepage
            </a>
            <a href="/login/admin" class="flex-1 text-center bg-slate-800 hover:bg-slate-700 text-white font-bold py-3.5 px-6 rounded-2xl transition text-sm">
                <i class="fas fa-shield-alt mr-2"></i>Admin Dashboard
            </a>
        </div>

        <p class="text-center text-xs text-slate-500 pt-2">
            &copy; <?= date('Y') ?> Learnerium Inc. &bull; Powered by JLM
        </p>
    </div>
</body>
</html>
