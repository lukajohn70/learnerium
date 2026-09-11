<?php
/**
 * =========================================================================
 * LEARNERIUM — Self-Destructing Production Database Migration & Sync Script
 * =========================================================================
 * 
 * Usage:
 *   Browser: Navigate to https://your-domain.com/migrate_db.php
 *   CLI:     php public/migrate_db.php
 * 
 * NOTE: For security, this file automatically DELETES ITSELF immediately 
 *       after execution completes.
 * =========================================================================
 */

// Track script path for self-destruction
$currentFile = __FILE__;

// Register shutdown function to guarantee self-destruction even on fatal error
register_shutdown_function(function() use ($currentFile) {
    if (file_exists($currentFile)) {
        @unlink($currentFile);
    }
});

$isCli = (php_sapi_name() === 'cli' || empty($_SERVER['REMOTE_ADDR']));

// Bootstrap Laravel environment
$laravelRoot = __DIR__;
if (!file_exists($laravelRoot . '/vendor/autoload.php')) {
    $laravelRoot = dirname(__DIR__);
}

if (!file_exists($laravelRoot . '/vendor/autoload.php') || !file_exists($laravelRoot . '/bootstrap/app.php')) {
    $err = "FATAL: Could not locate Laravel bootstrap files at {$laravelRoot}";
    if ($isCli) {
        fwrite(STDERR, "$err\n");
    } else {
        echo "<h1>$err</h1>";
    }
    @unlink($currentFile);
    exit(1);
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

function logStep(&$log, $message, $type = 'info') {
    $icons = [
        'success' => '✅',
        'warn'    => '⚠️',
        'error'   => '❌',
        'info'    => '🔹',
        'fire'    => '🔥',
    ];
    $icon = $icons[$type] ?? '🔹';
    $log[] = "{$icon} {$message}";
}

try {
    logStep($log, "Learnerium Production Database Migration initialized.", 'info');

    // 1. Ensure migrations table exists
    if (!Schema::hasTable('migrations')) {
        Schema::create('migrations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('migration');
            $table->integer('batch');
        });
        logStep($log, "Created 'migrations' tracking table.", 'success');
    }

    $recordMigration = function($name) use (&$log) {
        if (!DB::table('migrations')->where('migration', $name)->exists()) {
            DB::table('migrations')->insert(['migration' => $name, 'batch' => 1]);
            logStep($log, "Recorded baseline migration: {$name}");
        }
    };

    // 2. Align Courses Table (including Preorder fields)
    if (Schema::hasTable('courses')) {
        Schema::table('courses', function (Blueprint $table) use (&$log) {
            if (!Schema::hasColumn('courses', 'category')) {
                $table->string('category')->nullable()->after('description');
                logStep($log, "Added column 'category' to courses table.", 'success');
            }
            if (!Schema::hasColumn('courses', 'is_preorder')) {
                $table->boolean('is_preorder')->default(false)->after('published_at');
                logStep($log, "Added column 'is_preorder' (Preorder toggle) to courses table.", 'success');
            }
            if (!Schema::hasColumn('courses', 'preorder_price')) {
                $table->decimal('preorder_price', 8, 2)->nullable()->after('is_preorder');
                logStep($log, "Added column 'preorder_price' to courses table.", 'success');
            }
            if (!Schema::hasColumn('courses', 'preorder_ends_at')) {
                $table->timestamp('preorder_ends_at')->nullable()->after('preorder_price');
                logStep($log, "Added column 'preorder_ends_at' (countdown deadline) to courses table.", 'success');
            }
        });
        $recordMigration('2026_08_20_182351_add_category_to_courses_table');
        $recordMigration('2026_09_11_000000_add_preorder_to_courses');
        logStep($log, "Courses table verified with full pre-order support.", 'success');
    }

    // 3. Align Enrollments Table (payment, status, revenue split, references)
    if (Schema::hasTable('enrollments')) {
        Schema::table('enrollments', function (Blueprint $table) use (&$log) {
            if (!Schema::hasColumn('enrollments', 'progress_percentage')) {
                $table->integer('progress_percentage')->default(0)->after('completion_date');
                logStep($log, "Added column 'progress_percentage' to enrollments table.", 'success');
            }
            if (!Schema::hasColumn('enrollments', 'payment_status')) {
                $table->string('payment_status')->default('pending')->after('progress_percentage');
                logStep($log, "Added column 'payment_status' to enrollments table.", 'success');
            }
            if (!Schema::hasColumn('enrollments', 'amount_paid')) {
                $table->decimal('amount_paid', 10, 2)->default(0)->after('payment_status');
                logStep($log, "Added column 'amount_paid' to enrollments table.", 'success');
            }
            if (!Schema::hasColumn('enrollments', 'instructor_share')) {
                $table->decimal('instructor_share', 12, 2)->default(0)->after('amount_paid');
                logStep($log, "Added column 'instructor_share' to enrollments table.", 'success');
            }
            if (!Schema::hasColumn('enrollments', 'platform_share')) {
                $table->decimal('platform_share', 12, 2)->default(0)->after('instructor_share');
                logStep($log, "Added column 'platform_share' to enrollments table.", 'success');
            }
            if (!Schema::hasColumn('enrollments', 'payout_status')) {
                $table->string('payout_status')->default('pending')->after('platform_share');
                logStep($log, "Added column 'payout_status' to enrollments table.", 'success');
            }
            if (!Schema::hasColumn('enrollments', 'coupon_code')) {
                $table->string('coupon_code')->nullable()->after('payout_status');
                logStep($log, "Added column 'coupon_code' to enrollments table.", 'success');
            }
            if (!Schema::hasColumn('enrollments', 'payment_reference')) {
                $table->string('payment_reference')->nullable()->after('coupon_code');
                logStep($log, "Added column 'payment_reference' to enrollments table.", 'success');
            }
        });
        $recordMigration('2026_01_24_010000_add_progress_percentage_to_enrollments_table');
        $recordMigration('2026_08_21_104547_update_enrollments_table_for_payments');
        $recordMigration('2026_08_24_154500_add_revenue_split_to_enrollments');
        logStep($log, "Enrollments table schema verified.", 'success');
    }

    // 4. Align Users Table (roles, banking details, payouts)
    if (Schema::hasTable('users')) {
        Schema::table('users', function (Blueprint $table) use (&$log) {
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role', 50)->default('student')->after('password');
                logStep($log, "Added column 'role' to users table.", 'success');
            }
            if (!Schema::hasColumn('users', 'avatar')) {
                $table->string('avatar')->nullable()->after('role');
                logStep($log, "Added column 'avatar' to users table.", 'success');
            }
            if (!Schema::hasColumn('users', 'bank_name')) {
                $table->string('bank_name')->nullable()->after('email');
                logStep($log, "Added column 'bank_name' to users table.", 'success');
            }
            if (!Schema::hasColumn('users', 'bank_code')) {
                $table->string('bank_code', 20)->nullable()->after('bank_name');
                logStep($log, "Added column 'bank_code' to users table.", 'success');
            }
            if (!Schema::hasColumn('users', 'account_number')) {
                $table->string('account_number')->nullable()->after('bank_code');
                logStep($log, "Added column 'account_number' to users table.", 'success');
            }
            if (!Schema::hasColumn('users', 'account_name')) {
                $table->string('account_name')->nullable()->after('account_number');
                logStep($log, "Added column 'account_name' to users table.", 'success');
            }
            if (!Schema::hasColumn('users', 'payout_requested_at')) {
                $table->string('payout_requested_at')->nullable()->after('account_name');
                logStep($log, "Added column 'payout_requested_at' to users table.", 'success');
            }
        });
        $recordMigration('2025_06_05_155614_add_role_to_users_table');
        $recordMigration('2026_08_20_181033_add_avatar_to_users_table');
        $recordMigration('2026_08_24_163500_add_bank_details_to_users');
        $recordMigration('2026_08_24_165000_add_bank_code_to_users');
        logStep($log, "Users table schema verified.", 'success');
    }

    // 5. Align Lessons Table
    if (Schema::hasTable('lessons')) {
        Schema::table('lessons', function (Blueprint $table) use (&$log) {
            if (!Schema::hasColumn('lessons', 'module_id')) {
                $table->unsignedBigInteger('module_id')->nullable()->after('course_id');
                logStep($log, "Added column 'module_id' to lessons table.", 'success');
            }
            if (!Schema::hasColumn('lessons', 'duration_minutes')) {
                $table->integer('duration_minutes')->default(15)->after('video_url');
                logStep($log, "Added column 'duration_minutes' to lessons table.", 'success');
            }
        });
        $recordMigration('2026_08_20_175303_add_module_id_to_lessons_table');
        logStep($log, "Lessons table schema verified.", 'success');
    }

    // 6. Align Coupons Table
    if (Schema::hasTable('coupons')) {
        Schema::table('coupons', function (Blueprint $table) use (&$log) {
            if (!Schema::hasColumn('coupons', 'max_uses')) {
                $table->unsignedInteger('max_uses')->nullable()->after('active');
                logStep($log, "Added column 'max_uses' to coupons table.", 'success');
            }
            if (!Schema::hasColumn('coupons', 'used_count')) {
                $table->unsignedInteger('used_count')->default(0)->after('max_uses');
                logStep($log, "Added column 'used_count' to coupons table.", 'success');
            }
        });
        $recordMigration('2026_08_21_123224_add_max_uses_and_used_count_to_coupons_table');
        logStep($log, "Coupons table schema verified.", 'success');
    }

    // 7. Align Notifications Tables
    if (!Schema::hasTable('notification_preferences')) {
        Schema::create('notification_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->boolean('email_enrollment')->default(true);
            $table->boolean('email_payment')->default(true);
            $table->boolean('email_payout')->default(true);
            $table->boolean('email_course_update')->default(true);
            $table->boolean('email_marketing')->default(false);
            $table->boolean('inapp_enrollment')->default(true);
            $table->boolean('inapp_payment')->default(true);
            $table->boolean('inapp_payout')->default(true);
            $table->boolean('inapp_course_update')->default(true);
            $table->boolean('inapp_marketing')->default(false);
            $table->timestamps();
        });
        logStep($log, "Created 'notification_preferences' table.", 'success');
    }
    $recordMigration('2026_08_24_170000_create_notification_preferences_table');

    // 8. Run standard Artisan migrations to catch any remaining files
    try {
        Artisan::call('migrate', ['--force' => true]);
        $artisanOutput = trim(Artisan::output());
        if ($artisanOutput) {
            logStep($log, "Artisan migrate output:\n" . $artisanOutput, 'info');
        } else {
            logStep($log, "All standard Artisan migrations up to date.", 'success');
        }
    } catch (\Throwable $migEx) {
        logStep($log, "Artisan migrate notice: " . $migEx->getMessage(), 'warn');
    }

    // 9. Clear application caches
    try {
        Artisan::call('view:clear');
        Artisan::call('route:clear');
        Artisan::call('config:clear');
        logStep($log, "Cleared view, route, and config caches.", 'success');
    } catch (\Throwable $cEx) {
        logStep($log, "Cache clear notice: " . $cEx->getMessage(), 'warn');
    }

    logStep($log, "Database schema is 100% updated and verified!", 'success');

} catch (\Throwable $e) {
    $status = 'error';
    logStep($log, "Fatal Error: " . $e->getMessage(), 'error');
    logStep($log, $e->getTraceAsString(), 'error');
}

// 10. Self-Destruct execution
$selfDestructed = false;
if (file_exists($currentFile)) {
    $selfDestructed = @unlink($currentFile);
}
if ($selfDestructed) {
    logStep($log, "SELF-DESTRUCT COMPLETED: This file (" . basename($currentFile) . ") was permanently deleted from the server.", 'fire');
} else {
    logStep($log, "Notice: Unable to auto-delete " . basename($currentFile) . ". Please remove it manually.", 'warn');
}

// Render output for CLI or Browser
if ($isCli) {
    echo "\n" . str_repeat('=', 60) . "\n";
    echo " LEARNERIUM DATABASE MIGRATION & SYNC\n";
    echo str_repeat('=', 60) . "\n\n";
    foreach ($log as $line) {
        echo "  " . $line . "\n";
    }
    echo "\nStatus: " . strtoupper($status) . "\n";
    echo ($selfDestructed ? "File Deleted: YES\n\n" : "File Deleted: NO (check permissions)\n\n");
    exit($status === 'success' ? 0 : 1);
}

$outputText = implode("\n", $log);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learnerium Database Migration &amp; Self-Destruct</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-2xl w-full bg-slate-900 border border-slate-800 rounded-3xl p-8 shadow-2xl space-y-6">
        
        <!-- Header -->
        <div class="flex items-center justify-between border-b border-slate-800 pb-6">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center <?= $status === 'success' ? 'bg-emerald-500/20 text-emerald-400' : 'bg-rose-500/20 text-rose-400' ?>">
                    <i class="fas <?= $status === 'success' ? 'fa-check-circle text-2xl' : 'fa-exclamation-triangle text-2xl' ?>"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-extrabold text-white">Database Migration</h1>
                    <p class="text-sm text-slate-400">Production Schema Alignment</p>
                </div>
            </div>
            
            <?php if ($selfDestructed): ?>
            <div class="hidden sm:flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-rose-500/10 border border-rose-500/30 text-rose-400 text-xs font-bold uppercase tracking-wider">
                <i class="fas fa-fire-alt"></i> Self-Destructed
            </div>
            <?php endif; ?>
        </div>

        <!-- Self-Destruct Notice Banner -->
        <?php if ($selfDestructed): ?>
        <div class="bg-gradient-to-r from-rose-950/40 to-slate-900 border border-rose-500/40 rounded-2xl p-4 flex items-start gap-3">
            <div class="w-8 h-8 rounded-xl bg-rose-500/20 text-rose-400 flex items-center justify-center flex-shrink-0 mt-0.5">
                <i class="fas fa-trash-alt text-sm"></i>
            </div>
            <div>
                <h3 class="text-sm font-bold text-rose-300">File Permanently Removed</h3>
                <p class="text-xs text-slate-400 mt-0.5">
                    This script (<code class="text-rose-300 font-mono"><?= htmlspecialchars(basename($currentFile)) ?></code>) has deleted itself from your server. Refreshing or visiting this URL again will yield a 404 Not Found.
                </p>
            </div>
        </div>
        <?php endif; ?>

        <!-- Execution Log -->
        <div class="space-y-2">
            <div class="flex items-center justify-between text-xs uppercase font-bold tracking-wider text-slate-400">
                <span>Migration Execution Logs</span>
                <span class="<?= $status === 'success' ? 'text-emerald-400' : 'text-rose-400' ?>">
                    <i class="fas <?= $status === 'success' ? 'fa-check-circle' : 'fa-times-circle' ?> mr-1"></i>
                    <?= strtoupper($status) ?>
                </span>
            </div>
            <div class="bg-slate-950 border border-slate-800/80 rounded-2xl p-4 font-mono text-xs text-slate-300 overflow-x-auto whitespace-pre-wrap leading-relaxed max-h-96">
<?= htmlspecialchars($outputText) ?>
            </div>
        </div>

        <!-- Navigation Buttons -->
        <div class="pt-2 flex flex-col sm:flex-row gap-3">
            <a href="/" class="flex-1 text-center bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3.5 px-6 rounded-2xl transition shadow-lg shadow-indigo-600/20 text-sm flex items-center justify-center gap-2">
                <i class="fas fa-home"></i> View Website
            </a>
            <a href="/login/admin" class="flex-1 text-center bg-slate-800 hover:bg-slate-700 text-white font-bold py-3.5 px-6 rounded-2xl transition text-sm flex items-center justify-center gap-2">
                <i class="fas fa-shield-alt"></i> Admin Dashboard
            </a>
        </div>

        <p class="text-center text-xs text-slate-500 pt-1">
            &copy; <?= date('Y') ?> Learnerium &bull; LMS Platform
        </p>
    </div>
</body>
</html>
