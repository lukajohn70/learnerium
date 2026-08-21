<?php
/**
 * Learnerium Online Image & Path Diagnostic Tool v2
 * Upload this file to learnerium.jlm.com.ng/diagnose_images.php
 */

header('Content-Type: text/html; charset=utf-8');

$envFile = __DIR__ . '/.env';
$dbHost = '127.0.0.1';
$dbName = '';
$dbUser = '';
$dbPass = '';

if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (str_starts_with(trim($line), '#')) continue;
        if (str_contains($line, '=')) {
            list($key, $val) = explode('=', $line, 2);
            $key = trim($key);
            $val = trim($val, "\"' ");
            if ($key === 'DB_HOST') $dbHost = $val;
            if ($key === 'DB_DATABASE') $dbName = $val;
            if ($key === 'DB_USERNAME') $dbUser = $val;
            if ($key === 'DB_PASSWORD') $dbPass = $val;
        }
    }
}

if (isset($_GET['db_name'])) $dbName = $_GET['db_name'];
if (isset($_GET['db_user'])) $dbUser = $_GET['db_user'];
if (isset($_GET['db_pass'])) $dbPass = $_GET['db_pass'];
if (isset($_GET['db_host'])) $dbHost = $_GET['db_host'];

$pdo = null;
$dbError = null;

if (!empty($dbName) && !empty($dbUser)) {
    try {
        $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4", $dbUser, $dbPass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    } catch (Exception $e) {
        $dbError = $e->getMessage();
    }
}

$publicDir = __DIR__ . '/public';
$uploadsDir = __DIR__ . '/public/uploads';
$thumbnailsDir = __DIR__ . '/public/uploads/thumbnails';
$avatarsDir = __DIR__ . '/public/uploads/avatars';

// Base URL detection
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$baseUrl = $protocol . $host;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Learnerium Image URL Test Tool</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen py-10 px-4 font-sans">
    <div class="max-w-6xl mx-auto space-y-8">
        
        <div class="bg-slate-800 border border-slate-700 rounded-2xl p-6 shadow-xl flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-black text-white flex items-center gap-2">
                    <span class="text-pink-500">🔍</span> Image URL Testing & Diagnostic Tool v2
                </h1>
                <p class="text-xs text-slate-400 mt-1">Testing direct HTTP access vs public/ subfolder access.</p>
            </div>
            <a href="?" class="bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs px-4 py-2 rounded-xl transition">
                🔄 Refresh
            </a>
        </div>

        <?php if ($pdo): ?>
        <!-- Course Thumbnails Test -->
        <div class="bg-slate-800 border border-slate-700 rounded-2xl p-6 shadow-xl space-y-4">
            <h2 class="text-lg font-bold text-white border-b border-slate-700 pb-2">🖼️ Course Thumbnail URL Variations Test</h2>
            <?php
            $courses = $pdo->query("SELECT id, title, thumbnail FROM courses ORDER BY id DESC")->fetchAll();
            ?>
            <div class="space-y-6">
                <?php foreach ($courses as $c): 
                    $raw = $c['thumbnail'];
                    $filename = basename($raw);
                    
                    $url1 = $baseUrl . '/uploads/thumbnails/' . $filename;
                    $url2 = $baseUrl . '/public/uploads/thumbnails/' . $filename;
                    $url3 = $baseUrl . '/' . ltrim($raw, '/');
                    $url4 = $baseUrl . '/public/' . ltrim($raw, '/');
                ?>
                <div class="bg-slate-950 p-5 rounded-2xl border border-slate-800 space-y-3">
                    <div class="flex justify-between items-center">
                        <h3 class="font-bold text-white text-sm">#<?php echo $c['id']; ?>: <?php echo htmlspecialchars($c['title']); ?></h3>
                        <span class="text-xs font-mono text-amber-300">Raw DB: <?php echo htmlspecialchars($raw); ?></span>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-xs font-mono">
                        <!-- Option 1: /uploads/thumbnails/... -->
                        <div class="bg-slate-900 p-3 rounded-xl border border-slate-700 space-y-2">
                            <span class="text-slate-400 font-bold block text-[11px]">Option 1: Direct /uploads/</span>
                            <a href="<?php echo $url1; ?>" target="_blank" class="text-blue-400 hover:underline block break-all text-[10px]"><?php echo $url1; ?></a>
                            <div class="h-24 bg-black rounded-lg border border-slate-800 flex items-center justify-center overflow-hidden">
                                <img src="<?php echo $url1; ?>" class="h-full w-full object-cover" onerror="this.onerror=null;this.replaceWith(document.createTextNode('❌ 404 Failed'));">
                            </div>
                        </div>

                        <!-- Option 2: /public/uploads/thumbnails/... -->
                        <div class="bg-slate-900 p-3 rounded-xl border border-slate-700 space-y-2">
                            <span class="text-slate-400 font-bold block text-[11px]">Option 2: With /public/</span>
                            <a href="<?php echo $url2; ?>" target="_blank" class="text-blue-400 hover:underline block break-all text-[10px]"><?php echo $url2; ?></a>
                            <div class="h-24 bg-black rounded-lg border border-slate-800 flex items-center justify-center overflow-hidden">
                                <img src="<?php echo $url2; ?>" class="h-full w-full object-cover" onerror="this.onerror=null;this.replaceWith(document.createTextNode('❌ 404 Failed'));">
                            </div>
                        </div>

                        <!-- Option 3: Raw DB URL -->
                        <div class="bg-slate-900 p-3 rounded-xl border border-slate-700 space-y-2">
                            <span class="text-slate-400 font-bold block text-[11px]">Option 3: Raw DB Path</span>
                            <a href="<?php echo $url3; ?>" target="_blank" class="text-blue-400 hover:underline block break-all text-[10px]"><?php echo $url3; ?></a>
                            <div class="h-24 bg-black rounded-lg border border-slate-800 flex items-center justify-center overflow-hidden">
                                <img src="<?php echo $url3; ?>" class="h-full w-full object-cover" onerror="this.onerror=null;this.replaceWith(document.createTextNode('❌ 404 Failed'));">
                            </div>
                        </div>

                        <!-- Option 4: /public/ + Raw DB Path -->
                        <div class="bg-slate-900 p-3 rounded-xl border border-slate-700 space-y-2">
                            <span class="text-slate-400 font-bold block text-[11px]">Option 4: /public/ + Raw DB</span>
                            <a href="<?php echo $url4; ?>" target="_blank" class="text-blue-400 hover:underline block break-all text-[10px]"><?php echo $url4; ?></a>
                            <div class="h-24 bg-black rounded-lg border border-slate-800 flex items-center justify-center overflow-hidden">
                                <img src="<?php echo $url4; ?>" class="h-full w-full object-cover" onerror="this.onerror=null;this.replaceWith(document.createTextNode('❌ 404 Failed'));">
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- User Avatars Test -->
        <div class="bg-slate-800 border border-slate-700 rounded-2xl p-6 shadow-xl space-y-4">
            <h2 class="text-lg font-bold text-white border-b border-slate-700 pb-2">👤 User Avatars URL Variations Test</h2>
            <?php
            $users = $pdo->query("SELECT id, name, avatar FROM users WHERE avatar IS NOT NULL AND avatar != '' LIMIT 5")->fetchAll();
            ?>
            <div class="space-y-4">
                <?php foreach ($users as $u):
                    $raw = $u['avatar'];
                    $filename = basename($raw);
                    $url1 = $baseUrl . '/uploads/avatars/' . $filename;
                    $url2 = $baseUrl . '/public/uploads/avatars/' . $filename;
                ?>
                <div class="bg-slate-950 p-4 rounded-xl border border-slate-800 flex items-center justify-between font-mono text-xs">
                    <div>
                        <span class="font-bold text-white"><?php echo htmlspecialchars($u['name']); ?></span>
                        <span class="text-slate-400 block text-[10px]"><?php echo htmlspecialchars($raw); ?></span>
                    </div>
                    <div class="flex items-center gap-6">
                        <div class="text-center">
                            <span class="text-[10px] text-slate-400 block">Direct (/uploads/avatars/)</span>
                            <img src="<?php echo $url1; ?>" class="w-10 h-10 rounded-full object-cover mx-auto mt-1 border border-slate-700" onerror="this.onerror=null;this.style.border='2px solid red';">
                        </div>
                        <div class="text-center">
                            <span class="text-[10px] text-slate-400 block">Via /public/uploads/avatars/</span>
                            <img src="<?php echo $url2; ?>" class="w-10 h-10 rounded-full object-cover mx-auto mt-1 border border-slate-700" onerror="this.onerror=null;this.style.border='2px solid red';">
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

    </div>
</body>
</html>
