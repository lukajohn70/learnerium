<?php
/**
 * ============================================================
 * Learnerium — Online Database Setup & Migration Runner
 * ============================================================
 * INSTRUCTIONS:
 * 1. Upload this file to: /home/gwylvxeo/learnerium.jlm.com.ng/setup_database.php
 * 2. Visit: https://learnerium.jlm.com.ng/setup_database.php
 * 3. Click "Run Setup" to create all tables
 * 4. DELETE THIS FILE IMMEDIATELY after setup is complete!
 * ============================================================
 */

// ─── SECURITY KEY ────────────────────────────────────────────
// Change this if you want extra protection (or remove the check)
define('SECRET_KEY', 'learnerium_setup_2026');

// ─── DATABASE CONFIG ─────────────────────────────────────────
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'gwylvxeo_learnerium');
define('DB_USER', 'gwylvxeo_learnerium');
define('DB_PASS', 'xp.u!l$.onTpUm&X');

$results = [];
$ran = false;
$pdo = null;

// Connect to DB
try {
    $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4', DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    $results[] = ['status' => 'ok', 'msg' => '✅ Connected to database: ' . DB_NAME];
} catch (Exception $e) {
    $results[] = ['status' => 'err', 'msg' => '❌ Database connection failed: ' . $e->getMessage()];
}

// Run setup if form submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['key'] ?? '') === SECRET_KEY && $pdo) {
    $ran = true;
    $pdo->exec('SET FOREIGN_KEY_CHECKS = 0;');

    $tables = [

        // ── USERS ────────────────────────────────────────────
        'users' => "CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'student',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

        // ── PASSWORD RESETS ──────────────────────────────────
        'password_resets' => "CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

        // ── FAILED JOBS ──────────────────────────────────────
        'failed_jobs' => "CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

        // ── PERSONAL ACCESS TOKENS ───────────────────────────
        'personal_access_tokens' => "CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

        // ── INSTRUCTOR APPLICATIONS ──────────────────────────
        'instructor_applications' => "CREATE TABLE IF NOT EXISTS `instructor_applications` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `headline` varchar(255) NOT NULL,
  `expertise_area` varchar(255) NOT NULL,
  `bio` text NOT NULL,
  `portfolio_url` varchar(255) DEFAULT NULL,
  `sample_video_url` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `rejection_reason` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `instructor_applications_user_id_foreign` (`user_id`),
  CONSTRAINT `instructor_applications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

        // ── COURSES ──────────────────────────────────────────
        'courses' => "CREATE TABLE IF NOT EXISTS `courses` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `instructor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `price` decimal(8,2) NOT NULL DEFAULT 0.00,
  `thumbnail` varchar(255) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `level` varchar(255) DEFAULT NULL,
  `duration` varchar(255) DEFAULT NULL,
  `language` varchar(255) DEFAULT 'English',
  `status` varchar(255) NOT NULL DEFAULT 'draft',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `courses_slug_unique` (`slug`),
  KEY `courses_instructor_id_foreign` (`instructor_id`),
  CONSTRAINT `courses_instructor_id_foreign` FOREIGN KEY (`instructor_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

        // ── LESSONS ──────────────────────────────────────────
        'lessons' => "CREATE TABLE IF NOT EXISTS `lessons` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` longtext DEFAULT NULL,
  `video_url` varchar(255) DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `is_free` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lessons_course_id_foreign` (`course_id`),
  CONSTRAINT `lessons_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

        // ── QUIZZES ──────────────────────────────────────────
        'quizzes' => "CREATE TABLE IF NOT EXISTS `quizzes` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `lesson_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `pass_percentage` int(11) NOT NULL DEFAULT 70,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `quizzes_lesson_id_foreign` (`lesson_id`),
  CONSTRAINT `quizzes_lesson_id_foreign` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

        // ── QUESTIONS ────────────────────────────────────────
        'questions' => "CREATE TABLE IF NOT EXISTS `questions` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `quiz_id` bigint(20) UNSIGNED NOT NULL,
  `question_text` text NOT NULL,
  `option_a` varchar(255) NOT NULL,
  `option_b` varchar(255) NOT NULL,
  `option_c` varchar(255) DEFAULT NULL,
  `option_d` varchar(255) DEFAULT NULL,
  `correct_answer` varchar(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `questions_quiz_id_foreign` (`quiz_id`),
  CONSTRAINT `questions_quiz_id_foreign` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

        // ── ENROLLMENTS ──────────────────────────────────────
        'enrollments' => "CREATE TABLE IF NOT EXISTS `enrollments` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `progress_percentage` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `enrollments_user_id_course_id_unique` (`user_id`,`course_id`),
  KEY `enrollments_course_id_foreign` (`course_id`),
  CONSTRAINT `enrollments_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `enrollments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

        // ── LESSON PROGRESS ──────────────────────────────────
        'lesson_progress' => "CREATE TABLE IF NOT EXISTS `lesson_progress` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `lesson_id` bigint(20) UNSIGNED NOT NULL,
  `completed` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `lesson_progress_user_id_lesson_id_unique` (`user_id`,`lesson_id`),
  KEY `lesson_progress_lesson_id_foreign` (`lesson_id`),
  CONSTRAINT `lesson_progress_lesson_id_foreign` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE CASCADE,
  CONSTRAINT `lesson_progress_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

        // ── TASKS ────────────────────────────────────────────
        'tasks' => "CREATE TABLE IF NOT EXISTS `tasks` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `lesson_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `max_score` int(11) NOT NULL DEFAULT 100,
  `peer_review_enabled` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tasks_lesson_id_foreign` (`lesson_id`),
  CONSTRAINT `tasks_lesson_id_foreign` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

        // ── QUIZ ATTEMPTS ────────────────────────────────────
        'quiz_attempts' => "CREATE TABLE IF NOT EXISTS `quiz_attempts` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `quiz_id` bigint(20) UNSIGNED NOT NULL,
  `score` int(11) NOT NULL DEFAULT 0,
  `total` int(11) NOT NULL DEFAULT 0,
  `passed` tinyint(1) NOT NULL DEFAULT 0,
  `answers` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`answers`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `quiz_attempts_user_id_foreign` (`user_id`),
  KEY `quiz_attempts_quiz_id_foreign` (`quiz_id`),
  CONSTRAINT `quiz_attempts_quiz_id_foreign` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `quiz_attempts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

        // ── SUBMISSIONS ──────────────────────────────────────
        'submissions' => "CREATE TABLE IF NOT EXISTS `submissions` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `task_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `content` text DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `score` int(11) DEFAULT NULL,
  `feedback` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `submissions_task_id_foreign` (`task_id`),
  KEY `submissions_user_id_foreign` (`user_id`),
  CONSTRAINT `submissions_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE,
  CONSTRAINT `submissions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

        // ── PEER REVIEWS ─────────────────────────────────────
        'peer_reviews' => "CREATE TABLE IF NOT EXISTS `peer_reviews` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `submission_id` bigint(20) UNSIGNED NOT NULL,
  `reviewer_id` bigint(20) UNSIGNED NOT NULL,
  `rating` int(11) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `peer_reviews_submission_id_foreign` (`submission_id`),
  KEY `peer_reviews_reviewer_id_foreign` (`reviewer_id`),
  CONSTRAINT `peer_reviews_reviewer_id_foreign` FOREIGN KEY (`reviewer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `peer_reviews_submission_id_foreign` FOREIGN KEY (`submission_id`) REFERENCES `submissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

        // ── MIGRATIONS (Laravel tracking) ────────────────────
        'migrations' => "CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    ];

    $created = 0;
    $skipped = 0;
    foreach ($tables as $name => $sql) {
        try {
            // Check if exists
            $exists = $pdo->query("SHOW TABLES LIKE '$name'")->rowCount() > 0;
            if ($exists) {
                $results[] = ['status' => 'warn', 'msg' => "⚠️ Table `$name` already exists — skipped (data preserved)"];
                $skipped++;
            } else {
                $pdo->exec($sql);
                $results[] = ['status' => 'ok', 'msg' => "✅ Created table: `$name`"];
                $created++;
            }
        } catch (Exception $e) {
            $results[] = ['status' => 'err', 'msg' => "❌ Failed on `$name`: " . $e->getMessage()];
        }
    }

    $pdo->exec('SET FOREIGN_KEY_CHECKS = 1;');
    $results[] = ['status' => 'ok', 'msg' => "🎉 DONE! Created $created tables. Skipped $skipped (already existed)."];
}

// Show existing tables
$existingTables = [];
if ($pdo) {
    $existingTables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Learnerium — Database Setup</title>
<style>
  * { box-sizing: border-box; }
  body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f1f5f9; margin: 0; padding: 2rem; color: #1e293b; }
  .wrap { max-width: 860px; margin: 0 auto; }
  h1 { color: #1b2299; font-size: 1.8rem; margin-bottom: 0.25rem; }
  .subtitle { color: #64748b; margin-bottom: 2rem; font-size: 0.9rem; }
  .card { background: white; border-radius: 1rem; padding: 1.5rem 2rem; margin: 1rem 0; box-shadow: 0 2px 8px rgba(0,0,0,0.06); border: 1px solid #e2e8f0; }
  .card h2 { margin-top: 0; font-size: 1rem; color: #475569; text-transform: uppercase; letter-spacing: 0.05em; }
  .ok  { color: #16a34a; }
  .err { color: #dc2626; }
  .warn { color: #d97706; }
  .log { font-size: 0.85rem; line-height: 2; }
  .form-row { display: flex; gap: 1rem; align-items: center; flex-wrap: wrap; }
  input[type=password] { border: 1px solid #cbd5e1; border-radius: 0.5rem; padding: 0.6rem 1rem; font-size: 0.9rem; flex: 1; min-width: 200px; }
  button { background: #1b2299; color: white; border: none; border-radius: 0.5rem; padding: 0.7rem 1.8rem; font-size: 0.9rem; font-weight: 700; cursor: pointer; transition: background 0.2s; }
  button:hover { background: #141a75; }
  .pill { display: inline-block; background: #f1f5f9; border: 1px solid #e2e8f0; border-radius: 999px; padding: 0.15rem 0.7rem; font-size: 0.75rem; font-weight: 600; margin: 0.15rem; color: #475569; }
  .banner { padding: 1rem 1.5rem; border-radius: 0.75rem; font-weight: 700; font-size: 0.9rem; margin-bottom: 1.5rem; }
  .banner.success { background: #dcfce7; color: #166534; border: 1px solid #86efac; }
  .banner.danger { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
  .delete-warn { background: #fef9c3; border: 1px solid #fde047; color: #713f12; padding: 1rem 1.5rem; border-radius: 0.75rem; font-size: 0.85rem; margin-top: 1rem; font-weight: 600; }
</style>
</head>
<body>
<div class="wrap">
    <h1>🗄️ Learnerium Database Setup</h1>
    <p class="subtitle">This tool will safely create all required database tables for Learnerium on this server.</p>

    <?php if ($ran): ?>
        <div class="banner success">✅ Database setup completed! See results below.</div>
    <?php endif; ?>

    <!-- Connection Status -->
    <div class="card">
        <h2>🔌 Database Connection</h2>
        <?php foreach (array_filter($results, fn($r) => str_contains($r['msg'], 'Connected') || str_contains($r['msg'], 'connection failed')) as $r): ?>
            <p class="log <?= $r['status'] ?>"><?= htmlspecialchars($r['msg']) ?></p>
        <?php endforeach; ?>
        <?php if ($pdo): ?>
            <p class="log">📋 Existing tables: 
            <?php foreach ($existingTables as $t): ?>
                <span class="pill"><?= htmlspecialchars($t) ?></span>
            <?php endforeach; ?>
            <?php if (empty($existingTables)): ?>
                <em style="color:#dc2626">None — database is empty</em>
            <?php endif; ?>
            </p>
        <?php endif; ?>
    </div>

    <?php if (!$ran && $pdo): ?>
    <!-- Setup Form -->
    <div class="card">
        <h2>⚙️ Run Database Setup</h2>
        <p style="color:#64748b;font-size:0.85rem">This will create all 16 tables (skipping any that already exist — your data is safe).</p>
        <form method="POST">
            <div class="form-row">
                <input type="password" name="key" placeholder="Enter security key: learnerium_setup_2026" required>
                <button type="submit">🚀 Create All Tables</button>
            </div>
        </form>
    </div>
    <?php endif; ?>

    <!-- Results -->
    <?php if ($ran): ?>
    <div class="card">
        <h2>📋 Setup Results</h2>
        <div class="log">
            <?php foreach ($results as $r): ?>
                <p class="<?= $r['status'] ?>"><?= htmlspecialchars($r['msg']) ?></p>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <div class="delete-warn">
        ⚠️ <strong>IMPORTANT:</strong> Delete <code>setup_database.php</code> from your server immediately after setup is complete! Go to cPanel → File Manager → Delete this file.
    </div>
</div>
</body>
</html>
