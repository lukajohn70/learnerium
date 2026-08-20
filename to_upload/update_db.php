<?php
/**
 * ============================================================
 * Learnerium — Database Update / Migration Script
 * ============================================================
 * Upload to: /home/gwylvxeo/learnerium.jlm.com.ng/update_db.php
 * Visit: https://learnerium.jlm.com.ng/update_db.php
 * DELETE THIS FILE AFTER RUNNING!
 * ============================================================
 */

define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'gwylvxeo_learnerium');
define('DB_USER', 'gwylvxeo_learnerium');
define('DB_PASS', 'xp.u!l$.onTpUm&X');

$logs = array();

try {
    $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4', DB_USER, DB_PASS, array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ));
    $logs[] = array('s' => 'ok', 'm' => '✅ Connected to database: ' . DB_NAME);

    $pdo->exec('SET FOREIGN_KEY_CHECKS = 0;');

    // 1. Create modules table
    $hasModules = $pdo->query("SHOW TABLES LIKE 'modules'")->rowCount() > 0;
    if (!$hasModules) {
        $pdo->exec("CREATE TABLE IF NOT EXISTS `modules` (
          `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
          `course_id` bigint(20) UNSIGNED NOT NULL,
          `title` varchar(255) NOT NULL,
          `description` text DEFAULT NULL,
          `order` int(11) NOT NULL DEFAULT 0,
          `created_at` timestamp NULL DEFAULT NULL,
          `updated_at` timestamp NULL DEFAULT NULL,
          PRIMARY KEY (`id`),
          KEY `modules_course_id_foreign` (`course_id`),
          CONSTRAINT `modules_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
        $logs[] = array('s' => 'ok', 'm' => '✅ Created `modules` table');
    } else {
        $logs[] = array('s' => 'warn', 'm' => '⚠️ Table `modules` already exists (skipped)');
    }

    // 2. Add module_id to lessons table if not exists
    $cols = $pdo->query("SHOW COLUMNS FROM `lessons` LIKE 'module_id'")->fetchAll();
    if (empty($cols)) {
        $pdo->exec("ALTER TABLE `lessons` ADD COLUMN `module_id` bigint(20) UNSIGNED DEFAULT NULL AFTER `course_id`;");
        $logs[] = array('s' => 'ok', 'm' => '✅ Added `module_id` column to `lessons` table');
    } else {
        $logs[] = array('s' => 'warn', 'm' => '⚠️ Column `module_id` already exists in `lessons` table (skipped)');
    }

    // 3. Create module_materials table
    $hasMaterials = $pdo->query("SHOW TABLES LIKE 'module_materials'")->rowCount() > 0;
    if (!$hasMaterials) {
        $pdo->exec("CREATE TABLE IF NOT EXISTS `module_materials` (
          `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
          `module_id` bigint(20) UNSIGNED NOT NULL,
          `title` varchar(255) NOT NULL,
          `type` enum('document','link') NOT NULL DEFAULT 'document',
          `url_or_path` varchar(255) NOT NULL,
          `file_name` varchar(255) DEFAULT NULL,
          `created_at` timestamp NULL DEFAULT NULL,
          `updated_at` timestamp NULL DEFAULT NULL,
          PRIMARY KEY (`id`),
          KEY `module_materials_module_id_foreign` (`module_id`),
          CONSTRAINT `module_materials_module_id_foreign` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
        $logs[] = array('s' => 'ok', 'm' => '✅ Created `module_materials` table');
    } else {
        $logs[] = array('s' => 'warn', 'm' => '⚠️ Table `module_materials` already exists (skipped)');
    }

    // 4. Add avatar to users table if not exists
    $userCols = $pdo->query("SHOW COLUMNS FROM `users` LIKE 'avatar'")->fetchAll();
    if (empty($userCols)) {
        $pdo->exec("ALTER TABLE `users` ADD COLUMN `avatar` varchar(255) DEFAULT NULL AFTER `email`;");
        $logs[] = array('s' => 'ok', 'm' => '✅ Added `avatar` column to `users` table');
    } else {
        $logs[] = array('s' => 'warn', 'm' => '⚠️ Column `avatar` already exists in `users` table (skipped)');
    }

    // 5. Create instructor_applications table if not exists
    $hasApps = $pdo->query("SHOW TABLES LIKE 'instructor_applications'")->rowCount() > 0;
    if (!$hasApps) {
        $pdo->exec("CREATE TABLE IF NOT EXISTS `instructor_applications` (
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
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
        $logs[] = array('s' => 'ok', 'm' => '✅ Created `instructor_applications` table');
    } else {
        $logs[] = array('s' => 'warn', 'm' => '⚠️ Table `instructor_applications` already exists (skipped)');
    }

    // 6. Add category to courses table if not exists
    $courseCols = $pdo->query("SHOW COLUMNS FROM `courses` LIKE 'category'")->fetchAll();
    if (empty($courseCols)) {
        $pdo->exec("ALTER TABLE `courses` ADD COLUMN `category` varchar(255) DEFAULT NULL AFTER `level`;");
        $logs[] = array('s' => 'ok', 'm' => '✅ Added `category` column to `courses` table');
    } else {
        $logs[] = array('s' => 'warn', 'm' => '⚠️ Column `category` already exists in `courses` table (skipped)');
    }

    $pdo->exec('SET FOREIGN_KEY_CHECKS = 1;');
    $logs[] = array('s' => 'ok', 'm' => '🎉 Database schema updates applied successfully!');

} catch (Exception $e) {
    $logs[] = array('s' => 'err', 'm' => '❌ Database update error: ' . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Database Update — Learnerium</title>
<style>
body { font-family: Arial, sans-serif; background: #f4f6f9; padding: 30px; margin: 0; }
.wrap { max-width: 700px; margin: 0 auto; }
h1 { color: #1b2299; }
.card { background: #fff; border-radius: 10px; padding: 20px 25px; margin: 15px 0; box-shadow: 0 2px 6px rgba(0,0,0,0.08); }
.ok { color: #16a34a; font-weight: bold; }
.err { color: #dc2626; font-weight: bold; }
.warn { color: #d97706; font-weight: bold; }
p { margin: 8px 0; font-size: 14px; }
.del { background: #fef9c3; border: 1px solid #fde047; border-radius: 8px; padding: 12px 18px; color: #713f12; font-size: 13px; font-weight: bold; margin-top: 15px; }
</style>
</head>
<body>
<div class="wrap">
    <h1>⚡ Learnerium Database Update Script</h1>

    <div class="card">
        <h2 style="margin-top:0">📋 Update Execution Log</h2>
        <?php foreach ($logs as $l): ?>
            <p class="<?php echo $l['s']; ?>"><?php echo htmlspecialchars($l['m']); ?></p>
        <?php endforeach; ?>
    </div>

    <div class="del">
        ⚠️ <strong>DELETE this file after running!</strong><br>
        cPanel → File Manager → learnerium.jlm.com.ng → delete <code>update_db.php</code>
    </div>
</div>
</body>
</html>
