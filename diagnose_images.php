<?php
/**
 * Learnerium Online Image & Path Diagnostic Tool
 * Upload this file to learnerium.jlm.com.ng/diagnose_images.php
 */

header('Content-Type: text/html; charset=utf-8');

// Load Laravel Bootstrap if available for DB config, or parse .env manually
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

// Allow manual DB parameters via GET
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

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Learnerium Image & Path Diagnostics</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen py-10 px-4 font-sans">
    <div class="max-w-6xl mx-auto space-y-8">
        
        <!-- Header -->
        <div class="bg-slate-800 border border-slate-700 rounded-2xl p-6 shadow-xl flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-2xl font-black text-white flex items-center gap-2">
                    <span class="text-pink-500">🔍</span> Image & File Path Diagnostic Tool
                </h1>
                <p class="text-xs text-slate-400 mt-1">Inspecting server disk paths, web accessibility, directory permissions, and database URLs.</p>
            </div>
            <a href="?" class="bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs px-4 py-2 rounded-xl transition">
                🔄 Re-run Diagnostics
            </a>
        </div>

        <!-- Environment Info -->
        <div class="bg-slate-800 border border-slate-700 rounded-2xl p-6 shadow-xl space-y-4">
            <h2 class="text-lg font-bold text-white border-b border-slate-700 pb-2">🌐 Server Environment</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-xs font-mono">
                <div class="bg-slate-950 p-3 rounded-xl border border-slate-800">
                    <span class="text-slate-400 block">Root Directory (__DIR__)</span>
                    <span class="text-amber-300 font-bold break-all"><?php echo __DIR__; ?></span>
                </div>
                <div class="bg-slate-950 p-3 rounded-xl border border-slate-800">
                    <span class="text-slate-400 block">Document Root</span>
                    <span class="text-amber-300 font-bold break-all"><?php echo $_SERVER['DOCUMENT_ROOT'] ?? 'N/A'; ?></span>
                </div>
                <div class="bg-slate-950 p-3 rounded-xl border border-slate-800">
                    <span class="text-slate-400 block">HTTP Host</span>
                    <span class="text-emerald-400 font-bold"><?php echo $_SERVER['HTTP_HOST'] ?? 'N/A'; ?></span>
                </div>
                <div class="bg-slate-950 p-3 rounded-xl border border-slate-800">
                    <span class="text-slate-400 block">PHP Version</span>
                    <span class="text-emerald-400 font-bold"><?php echo PHP_VERSION; ?></span>
                </div>
                <div class="bg-slate-950 p-3 rounded-xl border border-slate-800">
                    <span class="text-slate-400 block">GD Extension</span>
                    <span class="font-bold <?php echo extension_loaded('gd') ? 'text-emerald-400' : 'text-rose-400'; ?>">
                        <?php echo extension_loaded('gd') ? '✅ Installed' : '❌ Missing'; ?>
                    </span>
                </div>
                <div class="bg-slate-950 p-3 rounded-xl border border-slate-800">
                    <span class="text-slate-400 block">Database Status</span>
                    <span class="font-bold <?php echo $pdo ? 'text-emerald-400' : 'text-rose-400'; ?>">
                        <?php echo $pdo ? '✅ Connected' : '❌ Connection Failed'; ?>
                    </span>
                </div>
            </div>
        </div>

        <!-- Directory Permissions Scan -->
        <div class="bg-slate-800 border border-slate-700 rounded-2xl p-6 shadow-xl space-y-4">
            <h2 class="text-lg font-bold text-white border-b border-slate-700 pb-2">📁 Folder Existence & Permissions</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs font-mono">
                <?php
                $checkDirs = [
                    'Public Directory' => $publicDir,
                    'Uploads Directory' => $uploadsDir,
                    'Thumbnails Directory' => $thumbnailsDir,
                    'Avatars Directory' => $avatarsDir,
                    'Storage Directory' => __DIR__ . '/storage',
                    'Storage App Public' => __DIR__ . '/storage/app/public',
                ];
                foreach ($checkDirs as $name => $path):
                    $exists = is_dir($path);
                    $writable = $exists && is_writable($path);
                    $fileCount = $exists ? count(scandir($path)) - 2 : 0;
                ?>
                <div class="bg-slate-950 p-4 rounded-xl border border-slate-800 flex justify-between items-center">
                    <div>
                        <span class="text-white font-bold block mb-0.5"><?php echo $name; ?></span>
                        <span class="text-slate-400 text-[11px] break-all"><?php echo $path; ?></span>
                        <?php if ($exists): ?>
                            <span class="text-slate-500 text-[10px] block mt-1">Contains <?php echo $fileCount; ?> items</span>
                        <?php endif; ?>
                    </div>
                    <div class="text-right">
                        <?php if ($exists): ?>
                            <span class="bg-emerald-500/20 text-emerald-400 px-2.5 py-1 rounded-md font-bold block mb-1">Exists</span>
                            <span class="<?php echo $writable ? 'text-emerald-400' : 'text-amber-400'; ?> text-[10px]">
                                <?php echo $writable ? 'Writable' : 'Read-Only'; ?>
                            </span>
                        <?php else: ?>
                            <span class="bg-rose-500/20 text-rose-400 px-2.5 py-1 rounded-md font-bold">Missing</span>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <?php if (!$pdo): ?>
        <!-- Manual DB Input -->
        <div class="bg-amber-950/40 border border-amber-500/30 rounded-2xl p-6 text-amber-200">
            <h3 class="font-bold text-base mb-2">⚠️ Database Connection Error</h3>
            <p class="text-xs mb-4 text-amber-300/80"><?php echo htmlspecialchars($dbError); ?></p>
            <form method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-3 text-xs">
                <input type="text" name="db_host" value="<?php echo htmlspecialchars($dbHost); ?>" placeholder="Host" class="bg-slate-900 border border-slate-700 px-3 py-2 rounded-xl text-white">
                <input type="text" name="db_name" value="<?php echo htmlspecialchars($dbName); ?>" placeholder="Database Name" class="bg-slate-900 border border-slate-700 px-3 py-2 rounded-xl text-white">
                <input type="text" name="db_user" value="<?php echo htmlspecialchars($dbUser); ?>" placeholder="Username" class="bg-slate-900 border border-slate-700 px-3 py-2 rounded-xl text-white">
                <input type="password" name="db_pass" value="<?php echo htmlspecialchars($dbPass); ?>" placeholder="Password" class="bg-slate-900 border border-slate-700 px-3 py-2 rounded-xl text-white">
                <button type="submit" class="sm:col-span-4 bg-amber-500 text-slate-950 font-bold py-2 rounded-xl">Connect Manual Credentials</button>
            </form>
        </div>
        <?php else: ?>

        <!-- Course Thumbnails Diagnostic -->
        <div class="bg-slate-800 border border-slate-700 rounded-2xl p-6 shadow-xl space-y-4">
            <h2 class="text-lg font-bold text-white border-b border-slate-700 pb-2">🖼️ Course Thumbnails Scan</h2>
            <?php
            $courses = $pdo->query("SELECT id, title, thumbnail FROM courses ORDER BY id DESC")->fetchAll();
            ?>
            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left">
                    <thead class="bg-slate-950 text-slate-400 font-mono">
                        <tr>
                            <th class="p-3">ID</th>
                            <th class="p-3">Course Title</th>
                            <th class="p-3">Raw DB Path</th>
                            <th class="p-3">Disk Existence Check</th>
                            <th class="p-3">Live Preview</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700/50">
                        <?php foreach ($courses as $c): 
                            $raw = $c['thumbnail'];
                            $clean = preg_replace('#^.*uploads/thumbnails/#', '', $raw);
                            $clean = preg_replace('#^public/#', '', $clean);

                            $diskPath1 = $thumbnailsDir . '/' . basename($raw);
                            $diskPath2 = $publicDir . '/' . ltrim($raw, '/');
                            
                            $foundPath = null;
                            if (!empty($raw) && file_exists($diskPath1)) {
                                $foundPath = $diskPath1;
                            } elseif (!empty($raw) && file_exists($diskPath2)) {
                                $foundPath = $diskPath2;
                            }

                            $webUrl = '';
                            if (str_starts_with($raw, 'http')) {
                                $webUrl = $raw;
                            } elseif ($foundPath) {
                                $webUrl = 'uploads/thumbnails/' . basename($raw);
                            }
                        ?>
                        <tr class="hover:bg-slate-750 font-mono">
                            <td class="p-3 font-bold text-slate-400">#<?php echo $c['id']; ?></td>
                            <td class="p-3 font-sans font-bold text-white"><?php echo htmlspecialchars($c['title']); ?></td>
                            <td class="p-3 text-amber-300 break-all"><?php echo htmlspecialchars($raw ?: 'NULL / Empty'); ?></td>
                            <td class="p-3">
                                <?php if (str_starts_with($raw, 'http')): ?>
                                    <span class="bg-blue-500/20 text-blue-400 px-2 py-0.5 rounded font-bold">External URL</span>
                                <?php elseif ($foundPath): ?>
                                    <span class="bg-emerald-500/20 text-emerald-400 px-2 py-0.5 rounded font-bold">File Exists on Disk</span>
                                <?php else: ?>
                                    <span class="bg-rose-500/20 text-rose-400 px-2 py-0.5 rounded font-bold">File Missing on Server</span>
                                <?php endif; ?>
                            </td>
                            <td class="p-3">
                                <?php if ($webUrl): ?>
                                    <img src="<?php echo htmlspecialchars($webUrl); ?>" class="w-16 h-10 object-cover rounded border border-slate-600" onerror="this.onerror=null;this.style.border='2px solid red';">
                                <?php else: ?>
                                    <span class="text-slate-500 italic">No File</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- User Avatars Diagnostic -->
        <div class="bg-slate-800 border border-slate-700 rounded-2xl p-6 shadow-xl space-y-4">
            <h2 class="text-lg font-bold text-white border-b border-slate-700 pb-2">👤 User Avatars Scan</h2>
            <?php
            $users = $pdo->query("SELECT id, name, email, avatar FROM users ORDER BY id DESC LIMIT 15")->fetchAll();
            ?>
            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left">
                    <thead class="bg-slate-950 text-slate-400 font-mono">
                        <tr>
                            <th class="p-3">ID</th>
                            <th class="p-3">Name</th>
                            <th class="p-3">Raw DB Avatar</th>
                            <th class="p-3">Disk Existence Check</th>
                            <th class="p-3">Preview</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700/50">
                        <?php foreach ($users as $u): 
                            $raw = $u['avatar'];
                            $diskPath = $avatarsDir . '/' . basename($raw);
                            $exists = !empty($raw) && file_exists($diskPath);
                            $webUrl = $exists ? 'uploads/avatars/' . basename($raw) : (str_starts_with($raw, 'http') ? $raw : '');
                        ?>
                        <tr class="hover:bg-slate-750 font-mono">
                            <td class="p-3 font-bold text-slate-400">#<?php echo $u['id']; ?></td>
                            <td class="p-3 font-sans font-bold text-white"><?php echo htmlspecialchars($u['name']); ?></td>
                            <td class="p-3 text-amber-300 break-all"><?php echo htmlspecialchars($raw ?: 'NULL / Empty'); ?></td>
                            <td class="p-3">
                                <?php if (str_starts_with($raw, 'http')): ?>
                                    <span class="bg-blue-500/20 text-blue-400 px-2 py-0.5 rounded font-bold">External URL</span>
                                <?php elseif ($exists): ?>
                                    <span class="bg-emerald-500/20 text-emerald-400 px-2 py-0.5 rounded font-bold">File Exists on Disk</span>
                                <?php else: ?>
                                    <span class="bg-rose-500/20 text-rose-400 px-2 py-0.5 rounded font-bold">File Missing on Server</span>
                                <?php endif; ?>
                            </td>
                            <td class="p-3">
                                <?php if ($webUrl): ?>
                                    <img src="<?php echo htmlspecialchars($webUrl); ?>" class="w-8 h-8 rounded-full object-cover border border-slate-600">
                                <?php else: ?>
                                    <span class="text-slate-500 italic">No File</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php endif; ?>

    </div>
</body>
</html>
