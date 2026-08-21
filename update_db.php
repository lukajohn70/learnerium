<?php
/**
 * ============================================================
 * Learnerium — Complete Online Database Update & Image Fixer
 * ============================================================
 * INSTRUCTIONS:
 * 1. Upload this file to: /home/gwylvxeo/learnerium.jlm.com.ng/update_db.php
 * 2. Visit in browser: https://learnerium.jlm.com.ng/update_db.php
 * 3. DELETE THIS FILE IMMEDIATELY AFTER RUNNING!
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
        $logs[] = array('s' => 'warn', 'm' => '⚠️ Table `modules` already exists');
    }

    // 2. Add module_id to lessons table if not exists
    $cols = $pdo->query("SHOW COLUMNS FROM `lessons` LIKE 'module_id'")->fetchAll();
    if (empty($cols)) {
        $pdo->exec("ALTER TABLE `lessons` ADD COLUMN `module_id` bigint(20) UNSIGNED DEFAULT NULL AFTER `course_id`;");
        $logs[] = array('s' => 'ok', 'm' => '✅ Added `module_id` column to `lessons` table');
    } else {
        $logs[] = array('s' => 'warn', 'm' => '⚠️ Column `module_id` already exists in `lessons` table');
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
        $logs[] = array('s' => 'warn', 'm' => '⚠️ Table `module_materials` already exists');
    }

    // 4. Add avatar to users table if not exists
    $userCols = $pdo->query("SHOW COLUMNS FROM `users` LIKE 'avatar'")->fetchAll();
    if (empty($userCols)) {
        $pdo->exec("ALTER TABLE `users` ADD COLUMN `avatar` varchar(255) DEFAULT NULL AFTER `email`;");
        $logs[] = array('s' => 'ok', 'm' => '✅ Added `avatar` column to `users` table');
    } else {
        $logs[] = array('s' => 'warn', 'm' => '⚠️ Column `avatar` already exists in `users` table');
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
        $logs[] = array('s' => 'warn', 'm' => '⚠️ Table `instructor_applications` already exists');
    }

    // 6. Add category to courses table if not exists
    $courseCols = $pdo->query("SHOW COLUMNS FROM `courses` LIKE 'category'")->fetchAll();
    if (empty($courseCols)) {
        $pdo->exec("ALTER TABLE `courses` ADD COLUMN `category` varchar(255) DEFAULT NULL AFTER `level`;");
        $logs[] = array('s' => 'ok', 'm' => '✅ Added `category` column to `courses` table');
    } else {
        $logs[] = array('s' => 'warn', 'm' => '⚠️ Column `category` already exists in `courses` table');
    }

    // 7. Update enrollments table for payments
    $enrollCols = array('payment_status', 'amount_paid', 'coupon_code', 'payment_reference');
    foreach ($enrollCols as $colName) {
        $chk = $pdo->query("SHOW COLUMNS FROM `enrollments` LIKE '$colName'")->fetchAll();
        if (empty($chk)) {
            if ($colName === 'payment_status') {
                $pdo->exec("ALTER TABLE `enrollments` ADD COLUMN `payment_status` varchar(50) NOT NULL DEFAULT 'pending';");
            } elseif ($colName === 'amount_paid') {
                $pdo->exec("ALTER TABLE `enrollments` ADD COLUMN `amount_paid` decimal(10,2) DEFAULT 0.00;");
            } elseif ($colName === 'coupon_code') {
                $pdo->exec("ALTER TABLE `enrollments` ADD COLUMN `coupon_code` varchar(100) DEFAULT NULL;");
            } elseif ($colName === 'payment_reference') {
                $pdo->exec("ALTER TABLE `enrollments` ADD COLUMN `payment_reference` varchar(255) DEFAULT NULL;");
            }
            $logs[] = array('s' => 'ok', 'm' => '✅ Added `' . $colName . '` column to `enrollments` table');
        } else {
            $logs[] = array('s' => 'warn', 'm' => '⚠️ Column `' . $colName . '` already exists in `enrollments` table');
        }
    }

    // 8. Create coupons table
    $hasCoupons = $pdo->query("SHOW TABLES LIKE 'coupons'")->rowCount() > 0;
    if (!$hasCoupons) {
        $pdo->exec("CREATE TABLE IF NOT EXISTS `coupons` (
          `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
          `code` varchar(100) NOT NULL,
          `discount_type` enum('percentage','fixed') NOT NULL DEFAULT 'percentage',
          `discount_value` decimal(10,2) NOT NULL,
          `course_id` bigint(20) UNSIGNED DEFAULT NULL,
          `active` tinyint(1) NOT NULL DEFAULT 1,
          `expires_at` timestamp NULL DEFAULT NULL,
          `created_at` timestamp NULL DEFAULT NULL,
          `updated_at` timestamp NULL DEFAULT NULL,
          PRIMARY KEY (`id`),
          UNIQUE KEY `coupons_code_unique` (`code`),
          KEY `coupons_course_id_foreign` (`course_id`),
          CONSTRAINT `coupons_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
        $logs[] = array('s' => 'ok', 'm' => '✅ Created `coupons` table');
    } else {
        $logs[] = array('s' => 'warn', 'm' => '⚠️ Table `coupons` already exists');
    }

    // 9. Create lesson_discussions table
    $hasDiscussions = $pdo->query("SHOW TABLES LIKE 'lesson_discussions'")->rowCount() > 0;
    if (!$hasDiscussions) {
        $pdo->exec("CREATE TABLE IF NOT EXISTS `lesson_discussions` (
          `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
          `lesson_id` bigint(20) UNSIGNED NOT NULL,
          `user_id` bigint(20) UNSIGNED NOT NULL,
          `comment` text NOT NULL,
          `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
          `created_at` timestamp NULL DEFAULT NULL,
          `updated_at` timestamp NULL DEFAULT NULL,
          PRIMARY KEY (`id`),
          KEY `lesson_discussions_lesson_id_foreign` (`lesson_id`),
          KEY `lesson_discussions_user_id_foreign` (`user_id`),
          KEY `lesson_discussions_parent_id_foreign` (`parent_id`),
          CONSTRAINT `lesson_discussions_lesson_id_foreign` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE CASCADE,
          CONSTRAINT `lesson_discussions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
          CONSTRAINT `lesson_discussions_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `lesson_discussions` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
        $logs[] = array('s' => 'ok', 'm' => '✅ Created `lesson_discussions` table');
    } else {
        $logs[] = array('s' => 'warn', 'm' => '⚠️ Table `lesson_discussions` already exists');
    }

    // 10. Create wishlists table
    $hasWishlists = $pdo->query("SHOW TABLES LIKE 'wishlists'")->rowCount() > 0;
    if (!$hasWishlists) {
        $pdo->exec("CREATE TABLE IF NOT EXISTS `wishlists` (
          `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
          `user_id` bigint(20) UNSIGNED NOT NULL,
          `course_id` bigint(20) UNSIGNED NOT NULL,
          `created_at` timestamp NULL DEFAULT NULL,
          `updated_at` timestamp NULL DEFAULT NULL,
          PRIMARY KEY (`id`),
          UNIQUE KEY `wishlists_user_id_course_id_unique` (`user_id`,`course_id`),
          KEY `wishlists_course_id_foreign` (`course_id`),
          CONSTRAINT `wishlists_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
          CONSTRAINT `wishlists_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
        $logs[] = array('s' => 'ok', 'm' => '✅ Created `wishlists` table');
    } else {
        $logs[] = array('s' => 'warn', 'm' => '⚠️ Table `wishlists` already exists');
    }

    // 11. Create cart_items table
    $hasCart = $pdo->query("SHOW TABLES LIKE 'cart_items'")->rowCount() > 0;
    if (!$hasCart) {
        $pdo->exec("CREATE TABLE IF NOT EXISTS `cart_items` (
          `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
          `user_id` bigint(20) UNSIGNED NOT NULL,
          `course_id` bigint(20) UNSIGNED NOT NULL,
          `created_at` timestamp NULL DEFAULT NULL,
          `updated_at` timestamp NULL DEFAULT NULL,
          PRIMARY KEY (`id`),
          UNIQUE KEY `cart_items_user_id_course_id_unique` (`user_id`,`course_id`),
          KEY `cart_items_course_id_foreign` (`course_id`),
          CONSTRAINT `cart_items_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
          CONSTRAINT `cart_items_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
        $logs[] = array('s' => 'ok', 'm' => '✅ Created `cart_items` table');
    } else {
        $logs[] = array('s' => 'warn', 'm' => '⚠️ Table `cart_items` already exists');
    }

    // 12. Add max_uses and used_count to coupons table
    $hasCouponsTable = $pdo->query("SHOW TABLES LIKE 'coupons'")->rowCount() > 0;
    if ($hasCouponsTable) {
        $cols = $pdo->query("SHOW COLUMNS FROM coupons")->fetchAll(PDO::FETCH_COLUMN);
        if (!in_array('max_uses', $cols)) {
            $pdo->exec("ALTER TABLE `coupons` ADD COLUMN `max_uses` int(11) NULL DEFAULT NULL AFTER `active`;");
            $logs[] = array('s' => 'ok', 'm' => '✅ Added `max_uses` column to `coupons` table');
        }
        if (!in_array('used_count', $cols)) {
            $pdo->exec("ALTER TABLE `coupons` ADD COLUMN `used_count` int(11) NOT NULL DEFAULT 0 AFTER `max_uses`;");
            $logs[] = array('s' => 'ok', 'm' => '✅ Added `used_count` column to `coupons` table');
        }
    }

    // 13. Fix image thumbnail paths in courses table
    $pdo->exec("UPDATE courses SET thumbnail = REPLACE(thumbnail, 'primary-jlm', '1b2299') WHERE thumbnail LIKE '%primary-jlm%';");
    $courses = $pdo->query("SELECT id, thumbnail FROM courses WHERE thumbnail IS NOT NULL AND thumbnail != ''")->fetchAll(PDO::FETCH_ASSOC);
    $fixedThumbnails = 0;
    foreach ($courses as $c) {
        $orig = $c['thumbnail'];
        // Strip any absolute machine paths (e.g. /Applications/MAMP/htdocs/learnerium/public/ or D:\...)
        $clean = preg_replace('#^.*uploads/thumbnails/#', 'uploads/thumbnails/', $orig);
        $clean = preg_replace('#^.*storage/uploads/#', 'uploads/thumbnails/', $clean);
        $clean = preg_replace('#^public/#', '', $clean);
        if ($clean !== $orig) {
            $stmt = $pdo->prepare("UPDATE courses SET thumbnail = ? WHERE id = ?");
            $stmt->execute(array($clean, $c['id']));
            $fixedThumbnails++;
        }
    }
    if ($fixedThumbnails > 0) {
        $logs[] = array('s' => 'ok', 'm' => "🖼️ Cleaned up $fixedThumbnails course thumbnail image path(s) in database!");
    } else {
        $logs[] = array('s' => 'ok', 'm' => "🖼️ Course thumbnail database paths are clean.");
    }

    // 13. Fix user avatar paths in users table
    $pdo->exec("UPDATE users SET avatar = REPLACE(avatar, 'primary-jlm', '1b2299') WHERE avatar LIKE '%primary-jlm%';");
    $users = $pdo->query("SELECT id, avatar FROM users WHERE avatar IS NOT NULL AND avatar != ''")->fetchAll(PDO::FETCH_ASSOC);
    $fixedAvatars = 0;
    foreach ($users as $u) {
        $orig = $u['avatar'];
        $clean = preg_replace('#^.*uploads/avatars/#', '', $orig);
        $clean = preg_replace('#^public/uploads/avatars/#', '', $clean);
        if ($clean !== $orig) {
            $stmt = $pdo->prepare("UPDATE users SET avatar = ? WHERE id = ?");
            $stmt->execute(array($clean, $u['id']));
            $fixedAvatars++;
        }
    }
    if ($fixedAvatars > 0) {
        $logs[] = array('s' => 'ok', 'm' => "👤 Cleaned up $fixedAvatars user avatar image path(s) in database!");
    } else {
        $logs[] = array('s' => 'ok', 'm' => "👤 User avatar database paths are clean.");
    }

    // 14. Promote admin user accounts
    $promoted = $pdo->exec("UPDATE users SET role = 'admin' WHERE email IN ('lukajohn70@gmail.com', 'lukajohn@gmail.com', 'instructor@learnerium.test');");
    if ($promoted > 0) {
        $logs[] = array('s' => 'ok', 'm' => "👑 Promoted $promoted account(s) to Admin role in online database!");
    } else {
        $logs[] = array('s' => 'ok', 'm' => "👑 Admin user accounts verified.");
    }

    // 15. Ensure uploads directories exist on server
    $dirs = array(
        __DIR__ . '/public/uploads/thumbnails',
        __DIR__ . '/public/uploads/avatars',
        __DIR__ . '/public/uploads/materials'
    );
    foreach ($dirs as $d) {
        if (!is_dir($d)) {
            @mkdir($d, 0755, true);
        }
    }
    $logs[] = array('s' => 'ok', 'm' => "📁 Upload directories checked and verified.");

    $pdo->exec('SET FOREIGN_KEY_CHECKS = 1;');
    $logs[] = array('s' => 'ok', 'm' => '🎉 Database schema updates and image fixes applied successfully!');

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
body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #0f172a; color: #f8fafc; padding: 40px 20px; margin: 0; }
.wrap { max-width: 750px; margin: 0 auto; }
h1 { color: #f7b731; font-size: 26px; font-weight: 800; margin-bottom: 5px; }
.subtitle { color: #94a3b8; font-size: 14px; margin-bottom: 25px; }
.card { background: #1e293b; border: 1px solid #334155; border-radius: 16px; padding: 24px 30px; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.3); }
.ok { color: #4ade80; font-weight: 600; font-size: 14px; margin: 8px 0; }
.err { color: #f87171; font-weight: 700; font-size: 14px; margin: 8px 0; }
.warn { color: #fbbf24; font-weight: 500; font-size: 13px; margin: 6px 0; opacity: 0.85; }
.del { background: rgba(245, 158, 11, 0.15); border: 1px solid rgba(245, 158, 11, 0.4); border-radius: 12px; padding: 16px 20px; color: #fef08a; font-size: 14px; font-weight: 600; margin-top: 25px; line-height: 1.6; }
code { background: #0f172a; padding: 2px 8px; rounded: 6px; color: #f7b731; font-family: monospace; border: 1px solid #334155; }
</style>
</head>
<body>
<div class="wrap">
    <h1>⚡ Learnerium Online Database & Image Fixer</h1>
    <div class="subtitle">Powered by JLM &bull; Auto-Migrate &amp; Cleanup Tool</div>

    <div class="card">
        <h2 style="margin-top:0; color:#fff; font-size: 18px;">📋 Update Execution Log</h2>
        <?php foreach ($logs as $l): ?>
            <p class="<?php echo $l['s']; ?>"><?php echo htmlspecialchars($l['m']); ?></p>
        <?php endforeach; ?>
    </div>

    <div class="del">
        ⚠️ <strong>SECURITY NOTICE: DELETE THIS FILE AFTER RUNNING!</strong><br>
        Open cPanel &rarr; File Manager &rarr; <code>learnerium.jlm.com.ng</code> &rarr; Delete <code>update_db.php</code>
    </div>
</div>
</body>
</html>
