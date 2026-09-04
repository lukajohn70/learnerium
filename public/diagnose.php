<?php
/**
 * Learnerium — Live Web Diagnostics & Inspector
 * Access: https://learnerium.jlm.com.ng/diagnose.php
 *
 * Inspects: deployed commit, video player config, logo/avatar delivery,
 *           YouTube iframe parameters, Plyr version, cart DB state.
 */

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle($request = Illuminate\Http\Request::capture());

use App\Models\User;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Module;
use Illuminate\Support\Facades\DB;

header('Content-Type: text/html; charset=utf-8');

// ─── 1. Git / Deploy State ───────────────────────────────────────────────────
$gitCommit = 'Unknown';
$gitBranch = 'Unknown';
if (file_exists(base_path('.git/HEAD'))) {
    $head = trim(file_get_contents(base_path('.git/HEAD')));
    if (str_starts_with($head, 'ref: ')) {
        $refFile = base_path('.git/' . trim(str_replace('ref: ', '', $head)));
        $gitBranch = basename(trim(str_replace('ref: ', '', $head)));
        $gitCommit = file_exists($refFile) ? substr(trim(file_get_contents($refFile)), 0, 12) : 'N/A';
    } else {
        $gitBranch = 'detached HEAD';
        $gitCommit = substr($head, 0, 12);
    }
}

// ─── 2. Lesson Video Parser ───────────────────────────────────────────────────
$recentLessons = Lesson::orderBy('id', 'desc')->take(8)->get();
$lessonData = $recentLessons->map(function($l) {
    $raw  = $l->video_url;
    $type = 'None'; $eid = ''; $embedUrl = '';
    if ($raw) {
        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $raw, $m)) {
            $type = 'YouTube'; $eid = $m[1];
            $embedUrl = "https://www.youtube.com/embed/{$eid}?controls=0&rel=0&modestbranding=1&playsinline=1&enablejsapi=1&disablekb=1&fs=0&iv_load_policy=3";
        } elseif (preg_match('/drive\.google\.com\/(?:file\/d\/|open\?id=)([^\/\?&]+)/i', $raw, $m)) {
            $type = 'Google Drive'; $eid = $m[1];
            $embedUrl = "https://drive.google.com/file/d/{$eid}/preview";
        } elseif (preg_match('/vimeo\.com\/(\d+)/i', $raw, $m)) {
            $type = 'Vimeo'; $eid = $m[1];
            $embedUrl = "https://player.vimeo.com/video/{$eid}";
        } else { $type = 'HTML5/Direct'; $eid = $raw; $embedUrl = $raw; }
    }
    return compact('l', 'raw', 'type', 'eid', 'embedUrl');
});

// ─── 3. Lesson Blade Source — grab the actual YouTube embed line ──────────────
$lessonBladeFile = resource_path('views/student/lesson.blade.php');
$youtubeLine = '[Could not read file]';
$plyrLine    = '[Could not read file]';
if (file_exists($lessonBladeFile)) {
    $lines = file($lessonBladeFile);
    foreach ($lines as $i => $line) {
        if (str_contains($line, 'youtube.com/embed') || str_contains($line, 'youtube-nocookie.com/embed')) {
            $youtubeLine = trim($line);
        }
        if (str_contains($line, 'cdn.plyr.io')) {
            $plyrLine = trim($line);
        }
    }
}

// ─── 4. Assets ────────────────────────────────────────────────────────────────
$assets = ['logo-only.png', 'logo.png', 'favicon.png', 'favicon.ico'];
$assetInfo = [];
foreach ($assets as $a) {
    $path = public_path($a);
    $assetInfo[$a] = [
        'exists' => file_exists($path),
        'size'   => file_exists($path) ? round(filesize($path) / 1024, 1) . ' KB' : '—',
        'mtime'  => file_exists($path) ? date('Y-m-d H:i:s', filemtime($path)) : '—',
        'url'    => asset($a),
    ];
}

// ─── 5. Instructor Avatars ────────────────────────────────────────────────────
$instructors = User::whereIn('role', ['instructor', 'admin'])->get();
$avatarData = $instructors->map(function($u) {
    $raw = $u->avatar ?: $u->profile_picture;
    $url = $u->avatarUrl();
    return [
        'name'  => $u->name,
        'email' => $u->email,
        'raw'   => $raw ?: '(empty)',
        'url'   => $url,
        'type'  => str_starts_with($url, 'data:image/svg') ? '✅ Instant SVG (0ms network)' :
                   (str_starts_with($url, 'data:image/')   ? '✅ Base64 Image' :
                   (str_contains($url, 'ui-avatars.com')   ? '🔴 SLOW — ui-avatars.com (external delay!)' :
                   (str_starts_with($url, 'http')          ? '🌐 External URL' : '📁 Local File'))),
    ];
});

// ─── 6. DB Counts ─────────────────────────────────────────────────────────────
$counts = [];
try {
    $counts = [
        'Users'    => User::count(),
        'Courses'  => Course::count(),
        'Modules'  => Module::count(),
        'Lessons'  => Lesson::count(),
        'Cart Rows' => DB::table('user_cart')->count(),
    ];
    $dbOk = true;
} catch (\Throwable $e) { $dbOk = false; $dbError = $e->getMessage(); }

// ─── 7. Controls=0 Present Check ─────────────────────────────────────────────
$controlsOff = str_contains($youtubeLine, 'controls=0');
$isNoCookie  = str_contains($youtubeLine, 'youtube-nocookie.com');
$hasOriginEncode = str_contains($youtubeLine, 'urlencode');
$plyrVersion = '';
if (preg_match('/cdn\.plyr\.io\/([\d.]+)/', $plyrLine, $pm)) {
    $plyrVersion = $pm[1];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Learnerium Diagnostics</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.plyr.io/3.7.8/plyr.css">
    <style>
        body { font-family: 'Segoe UI', sans-serif; }
        .badge-ok  { @apply bg-emerald-500/20 text-emerald-300 border border-emerald-500/30; }
        .badge-err { @apply bg-red-500/20    text-red-300    border border-red-500/30; }
        .badge-warn{ @apply bg-yellow-500/20 text-yellow-300 border border-yellow-500/30; }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen p-4 md:p-10">
<div class="max-w-5xl mx-auto space-y-8">

    <!-- Header -->
    <div class="flex items-center justify-between border-b border-slate-800 pb-6">
        <div>
            <h1 class="text-2xl font-black text-white">🔍 Learnerium System Diagnostics</h1>
            <p class="text-sm text-slate-400 mt-1">Live web inspection — video player, teacher DPs, assets, and DB state</p>
        </div>
        <div class="text-right text-xs font-mono text-slate-400">
            <div class="text-emerald-400 font-bold">PHP <?= PHP_VERSION ?></div>
            <div><?= date('Y-m-d H:i:s') ?></div>
        </div>
    </div>

    <!-- SECTION 1: Deployment State -->
    <section class="bg-slate-900 rounded-2xl border border-slate-800 p-6 space-y-4">
        <h2 class="text-xs font-bold uppercase tracking-widest text-slate-400">① Deploy / Git State</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 font-mono text-sm">
            <div class="bg-slate-800/60 rounded-xl p-4 border border-slate-700">
                <div class="text-slate-500 text-xs mb-1">Active Branch</div>
                <div class="text-yellow-400 font-bold"><?= htmlspecialchars($gitBranch) ?></div>
            </div>
            <div class="bg-slate-800/60 rounded-xl p-4 border border-slate-700">
                <div class="text-slate-500 text-xs mb-1">HEAD Commit (first 12 chars)</div>
                <div class="text-blue-400 font-bold"><?= htmlspecialchars($gitCommit) ?></div>
            </div>
        </div>
        <p class="text-xs text-slate-500">If the commit hash differs from your local git log, cPanel has NOT deployed the latest code yet.</p>
    </section>

    <!-- SECTION 2: YouTube Player Blade Config -->
    <section class="bg-slate-900 rounded-2xl border border-slate-800 p-6 space-y-4">
        <h2 class="text-xs font-bold uppercase tracking-widest text-slate-400">② YouTube Player Blade Config (server-side source)</h2>
        
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
            <div class="rounded-xl p-3 border <?= $controlsOff ? 'bg-emerald-900/30 border-emerald-700' : 'bg-red-900/30 border-red-700' ?>">
                <div class="font-bold text-lg mb-1"><?= $controlsOff ? '✅' : '❌' ?></div>
                <div class="font-semibold">controls=0</div>
                <div class="text-slate-400"><?= $controlsOff ? 'YouTube UI hidden' : 'YouTube UI VISIBLE (needs re-deploy!)' ?></div>
            </div>
            <div class="rounded-xl p-3 border <?= !$isNoCookie ? 'bg-emerald-900/30 border-emerald-700' : 'bg-yellow-900/30 border-yellow-700' ?>">
                <div class="font-bold text-lg mb-1"><?= !$isNoCookie ? '✅' : '⚠️' ?></div>
                <div class="font-semibold">youtube.com embed</div>
                <div class="text-slate-400"><?= $isNoCookie ? 'Uses youtube-nocookie (may block!)' : 'Standard youtube.com (✓ reliable)' ?></div>
            </div>
            <div class="rounded-xl p-3 border <?= !$hasOriginEncode ? 'bg-emerald-900/30 border-emerald-700' : 'bg-red-900/30 border-red-700' ?>">
                <div class="font-bold text-lg mb-1"><?= !$hasOriginEncode ? '✅' : '❌' ?></div>
                <div class="font-semibold">origin param</div>
                <div class="text-slate-400"><?= $hasOriginEncode ? 'urlencode() present (double-encode bug!)' : 'No urlencode bug' ?></div>
            </div>
            <div class="rounded-xl p-3 border bg-blue-900/30 border-blue-700">
                <div class="font-bold text-lg mb-1">⚙️</div>
                <div class="font-semibold">Plyr Version</div>
                <div class="text-slate-400"><?= $plyrVersion ?: 'Not detected' ?></div>
            </div>
        </div>

        <div class="bg-slate-800 rounded-xl p-4 border border-slate-700">
            <div class="text-xs text-slate-400 mb-2">Actual YouTube iframe src line in lesson.blade.php on THIS server:</div>
            <code class="text-xs text-green-300 break-all"><?= htmlspecialchars($youtubeLine) ?></code>
        </div>
        <div class="bg-slate-800 rounded-xl p-4 border border-slate-700">
            <div class="text-xs text-slate-400 mb-2">Plyr script tag loaded:</div>
            <code class="text-xs text-blue-300 break-all"><?= htmlspecialchars($plyrLine) ?></code>
        </div>
    </section>

    <!-- SECTION 3: Live YouTube Embed Test -->
    <section class="bg-slate-900 rounded-2xl border border-slate-800 p-6 space-y-4">
        <h2 class="text-xs font-bold uppercase tracking-widest text-slate-400">③ Live YouTube Embed Test (controls=0)</h2>
        <p class="text-xs text-slate-400">Playing a test video with <code class="text-green-300">controls=0</code> — Plyr should display ITS OWN controls below the video, YouTube's native controls must be invisible:</p>
        <div class="aspect-video w-full max-w-xl mx-auto rounded-xl overflow-hidden bg-black border border-slate-700">
            <div class="plyr__video-embed js-player-test w-full h-full">
                <iframe
                    src="https://www.youtube.com/embed/7HlvvjMiKnc?controls=0&rel=0&modestbranding=1&playsinline=1&enablejsapi=1&disablekb=1&fs=0&iv_load_policy=3"
                    allowfullscreen allow="autoplay; fullscreen"
                    class="w-full h-full border-0">
                </iframe>
            </div>
        </div>
        <p class="text-xs text-slate-500 text-center">If you see YouTube native controls above, the embed params are wrong. If you see Plyr controls only — it's working correctly.</p>
    </section>

    <!-- SECTION 4: Assets -->
    <section class="bg-slate-900 rounded-2xl border border-slate-800 p-6 space-y-4">
        <h2 class="text-xs font-bold uppercase tracking-widest text-slate-400">④ Logo & Favicon Assets</h2>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <?php foreach ($assetInfo as $name => $info): ?>
            <div class="bg-slate-800/60 rounded-xl p-4 border border-slate-700 text-center space-y-2">
                <?php if ($info['exists']): ?>
                    <img src="<?= $info['url'] ?>" alt="<?= $name ?>" class="w-12 h-12 object-contain mx-auto rounded-lg bg-slate-700/50 p-1">
                    <div class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-600">EXISTS</div>
                <?php else: ?>
                    <div class="w-12 h-12 mx-auto rounded-lg bg-red-900/40 flex items-center justify-center text-2xl">❌</div>
                    <div class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-500/20 text-red-300 border border-red-600">MISSING</div>
                <?php endif; ?>
                <div class="text-xs font-bold text-slate-200"><?= $name ?></div>
                <div class="text-[10px] text-slate-500"><?= $info['size'] ?></div>
                <div class="text-[10px] text-slate-600"><?= $info['mtime'] ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- SECTION 5: Instructor Avatars -->
    <section class="bg-slate-900 rounded-2xl border border-slate-800 p-6 space-y-4">
        <h2 class="text-xs font-bold uppercase tracking-widest text-slate-400">⑤ Teacher / Instructor DPs (Avatar Delivery Engine)</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <?php foreach ($avatarData as $inst): ?>
            <div class="bg-slate-800/60 rounded-xl p-4 border border-slate-700 flex items-center gap-4">
                <img src="<?= $inst['url'] ?>" alt="<?= htmlspecialchars($inst['name']) ?>"
                     class="w-14 h-14 rounded-full border-2 border-indigo-500 object-cover flex-shrink-0"
                     loading="eager">
                <div class="min-w-0 flex-1 space-y-1">
                    <div class="text-sm font-bold text-white truncate"><?= htmlspecialchars($inst['name']) ?></div>
                    <div class="text-xs text-slate-400"><?= htmlspecialchars($inst['email']) ?></div>
                    <div class="text-[11px] text-slate-300 font-mono"><?= $inst['type'] ?></div>
                    <div class="text-[10px] text-slate-500 font-mono truncate">DB: <?= htmlspecialchars($inst['raw']) ?></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- SECTION 6: Lesson Video Stream Info -->
    <section class="bg-slate-900 rounded-2xl border border-slate-800 p-6 space-y-4">
        <h2 class="text-xs font-bold uppercase tracking-widest text-slate-400">⑥ Video Stream — Latest Lessons</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-xs font-mono border-collapse">
                <thead><tr class="text-slate-500 border-b border-slate-800">
                    <th class="p-2 text-left">ID</th>
                    <th class="p-2 text-left">Title</th>
                    <th class="p-2 text-left">Provider</th>
                    <th class="p-2 text-left">Embed ID / URL</th>
                </tr></thead>
                <tbody class="divide-y divide-slate-800">
                    <?php foreach ($lessonData as $d): ?>
                    <tr class="hover:bg-slate-800/40">
                        <td class="p-2 text-slate-500">#<?= $d['l']->id ?></td>
                        <td class="p-2 text-slate-200 font-sans font-semibold"><?= htmlspecialchars($d['l']->title) ?></td>
                        <td class="p-2">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold
                                <?= $d['type'] === 'YouTube' ? 'bg-red-600/20 text-red-300' : ($d['type'] === 'Google Drive' ? 'bg-blue-600/20 text-blue-300' : ($d['type'] === 'None' ? 'bg-slate-700 text-slate-400' : 'bg-purple-600/20 text-purple-300')) ?>">
                                <?= $d['type'] ?>
                            </span>
                        </td>
                        <td class="p-2 text-slate-400 max-w-xs truncate"><?= htmlspecialchars($d['eid']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>

    <!-- SECTION 7: DB Counts -->
    <section class="bg-slate-900 rounded-2xl border border-slate-800 p-6 space-y-4">
        <h2 class="text-xs font-bold uppercase tracking-widest text-slate-400">⑦ Database Counts</h2>
        <?php if (!empty($dbOk)): ?>
        <div class="flex flex-wrap gap-4">
            <?php foreach ($counts as $label => $count): ?>
            <div class="bg-slate-800 rounded-xl px-5 py-3 border border-slate-700 text-center">
                <div class="text-2xl font-black text-blue-400"><?= $count ?></div>
                <div class="text-xs text-slate-400 font-semibold mt-0.5"><?= $label ?></div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="text-red-400 font-mono text-sm">DB Error: <?= htmlspecialchars($dbError ?? 'Unknown') ?></div>
        <?php endif; ?>
    </section>

    <!-- Footer -->
    <div class="flex justify-between items-center pt-2 pb-8">
        <a href="/" class="text-xs text-slate-500 hover:text-white transition">← Back to Site</a>
        <a href="/updatedb.php" class="text-xs font-bold text-yellow-400 hover:text-yellow-200 transition">⚡ Run DB Sync / Cache Clear →</a>
    </div>

</div>

<link rel="stylesheet" href="https://cdn.plyr.io/3.7.8/plyr.css">
<script src="https://cdn.plyr.io/3.7.8/plyr.polyfilled.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const testEl = document.querySelector('.js-player-test');
    if (testEl && typeof Plyr !== 'undefined') {
        new Plyr(testEl, {
            controls: ['play-large','play','rewind','fast-forward','progress','current-time','duration','mute','volume','settings','fullscreen'],
            settings: ['speed'],
            youtube: { noCookie: false, rel: 0, showinfo: 0, iv_load_policy: 3, modestbranding: 1, playsinline: 1, controls: 0 }
        });
    }
});
</script>
</body>
</html>
