<?php
/**
 * Learnerium — Live System Inspector v2
 * Access: https://learnerium.jlm.com.ng/diagnose.php  (root-level script)
 */

$root = __DIR__;
require $root . '/vendor/autoload.php';
$app    = require_once $root . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle($req = Illuminate\Http\Request::capture());

use App\Models\{User, Course, Lesson, Module};
use Illuminate\Support\Facades\DB;

header('Content-Type: text/html; charset=utf-8');

// ── Git Commit ─────────────────────────────────────────────────────────────────
$gitBranch = $gitCommit = 'Unknown';
if (file_exists("$root/.git/HEAD")) {
    $head = trim(file_get_contents("$root/.git/HEAD"));
    if (str_starts_with($head, 'ref: ')) {
        $ref = str_replace('ref: ', '', $head);
        $gitBranch = basename($ref);
        $refFile = "$root/.git/$ref";
        $gitCommit = file_exists($refFile) ? substr(trim(file_get_contents($refFile)), 0, 12) : 'N/A';
    } else {
        $gitBranch = 'detached';
        $gitCommit = substr($head, 0, 12);
    }
}

// ── Read lesson.blade.php source live ─────────────────────────────────────────
$bladeFile   = "$root/resources/views/student/lesson.blade.php";
$youtubeLine = '[file not readable]';
$plyrSrc     = '[not found]';
$plyrVersion = '?';
if (file_exists($bladeFile)) {
    foreach (file($bladeFile) as $line) {
        if (str_contains($line, 'youtube.com/embed') || str_contains($line, 'youtube-nocookie')) {
            $youtubeLine = trim($line);
        }
        if (str_contains($line, 'cdn.plyr.io')) {
            $plyrSrc = trim($line);
            preg_match('/cdn\.plyr\.io\/([\d.]+)/', $line, $pm);
            $plyrVersion = $pm[1] ?? '?';
        }
    }
}
$controlsOff  = str_contains($youtubeLine, 'controls=0');
$isNoCookie   = str_contains($youtubeLine, 'youtube-nocookie');
$hasUrlEncode = str_contains($youtubeLine, 'urlencode');

// ── Assets ─────────────────────────────────────────────────────────────────────
$assetMap = [
    'logo-only.png' => asset('logo-only.png'),
    'logo.png'      => asset('logo.png'),
    'favicon.png'   => asset('favicon.png'),
    'favicon.ico'   => asset('favicon.ico'),
];
$assetInfo = [];
foreach ($assetMap as $name => $url) {
    // On this server, public assets are in $root/public/
    $path = "$root/public/$name";
    $assetInfo[$name] = [
        'exists' => file_exists($path),
        'size'   => file_exists($path) ? round(filesize($path)/1024, 1).'KB' : '—',
        'url'    => $url,
    ];
}

// ── Instructor avatars ─────────────────────────────────────────────────────────
$instructors = User::whereIn('role', ['instructor', 'admin'])->get();

// ── Lesson videos ──────────────────────────────────────────────────────────────
$lessons = Lesson::orderBy('id','desc')->take(10)->get()->map(function($l) {
    $raw  = $l->video_url; $type = 'None'; $eid = '';
    if ($raw) {
        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $raw, $m)) { $type='YouTube'; $eid=$m[1]; }
        elseif (preg_match('/drive\.google\.com\/(?:file\/d\/|open\?id=)([^\/\?&]+)/i', $raw, $m))                                   { $type='Drive';   $eid=$m[1]; }
        elseif (preg_match('/vimeo\.com\/(\d+)/i', $raw, $m))                                                                         { $type='Vimeo';   $eid=$m[1]; }
        else { $type='HTML5'; $eid=$raw; }
    }
    return compact('l','raw','type','eid');
});

// ── DB counts ──────────────────────────────────────────────────────────────────
try {
    $counts = ['Users'=>User::count(),'Courses'=>Course::count(),'Modules'=>Module::count(),'Lessons'=>Lesson::count(),'Cart Rows'=>DB::table('user_cart')->count()];
    $dbOk = true;
} catch (\Throwable $e) { $dbOk = false; $dbError = $e->getMessage(); }

// ── CSP / security header check ────────────────────────────────────────────────
$cspFile   = "$root/app/Http/Middleware/SecurityHeaders.php";
$cspSrc    = file_exists($cspFile) ? file_get_contents($cspFile) : '';
$cspHasPlyr    = str_contains($cspSrc, 'cdn.plyr.io');
$cspFrameNone  = str_contains($cspSrc, "frame-ancestors 'none'");
$cspFrameSelf  = str_contains($cspSrc, "frame-ancestors 'self'");
$cspHasYTSrc   = str_contains($cspSrc, 'www.youtube.com') && str_contains($cspSrc, 'frame-src');
$xFrameDeny    = str_contains($cspSrc, "'X-Frame-Options', 'DENY'");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Learnerium Diagnostics v2</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen p-4 md:p-10 font-sans">
<div class="max-w-5xl mx-auto space-y-8">

    <!-- Header -->
    <div class="flex items-center justify-between border-b border-slate-800 pb-5">
        <div>
            <h1 class="text-xl font-black text-white tracking-tight">🔍 Learnerium Live Inspector v2</h1>
            <p class="text-xs text-slate-400 mt-0.5">Player · DPs · Assets · CSP · DB — <?= date('Y-m-d H:i:s') ?></p>
        </div>
        <span class="font-mono text-xs text-emerald-400 bg-emerald-950 border border-emerald-800 px-3 py-1.5 rounded-full">PHP <?= PHP_VERSION ?></span>
    </div>

    <!-- ① Deploy State -->
    <section class="bg-slate-900 rounded-2xl border border-slate-800 p-5">
        <h2 class="text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-4">① Deployment State</h2>
        <div class="grid grid-cols-2 gap-3 font-mono text-sm">
            <div class="bg-slate-800 rounded-xl p-3 border border-slate-700">
                <div class="text-[10px] text-slate-500 mb-1">Branch</div>
                <div class="text-yellow-400 font-bold"><?= e($gitBranch) ?></div>
            </div>
            <div class="bg-slate-800 rounded-xl p-3 border border-slate-700">
                <div class="text-[10px] text-slate-500 mb-1">HEAD Commit</div>
                <div class="text-blue-400 font-bold"><?= e($gitCommit) ?></div>
            </div>
        </div>
        <p class="text-[11px] text-slate-500 mt-3">⚠️ If HEAD doesn't match your latest local commit, cPanel hasn't deployed yet. The old lesson.blade.php is still live.</p>
    </section>

    <!-- ② CSP / Security Blocker Check -->
    <section class="bg-slate-900 rounded-2xl border border-slate-800 p-5">
        <h2 class="text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-4">② Content Security Policy — Video Player Blockers</h2>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
            <?php
            $badges = [
                ['label'=>'frame-ancestors', 'ok'=>$cspFrameSelf && !$cspFrameNone, 'ok_txt'=>"✅ 'self' (iframes allowed)", 'fail_txt'=>"🔴 'none' — BLOCKS YouTube!"],
                ['label'=>'X-Frame-Options', 'ok'=>!$xFrameDeny, 'ok_txt'=>'✅ SAMEORIGIN', 'fail_txt'=>'🔴 DENY — blocks iframes!'],
                ['label'=>'Plyr CDN in CSP', 'ok'=>$cspHasPlyr, 'ok_txt'=>'✅ cdn.plyr.io allowed', 'fail_txt'=>'🔴 Plyr CDN BLOCKED!'],
                ['label'=>'YouTube in frame-src', 'ok'=>$cspHasYTSrc, 'ok_txt'=>'✅ youtube.com in frame-src', 'fail_txt'=>'🔴 YouTube not whitelisted!'],
            ];
            foreach ($badges as $b):
                $cls = $b['ok'] ? 'bg-emerald-900/30 border-emerald-700 text-emerald-300' : 'bg-red-900/30 border-red-700 text-red-300';
            ?>
            <div class="rounded-xl p-3 border <?= $cls ?> space-y-1">
                <div class="font-bold font-mono"><?= $b['label'] ?></div>
                <div><?= $b['ok'] ? $b['ok_txt'] : $b['fail_txt'] ?></div>
            </div>
            <?php endforeach; ?>
        </div>
        <p class="text-[11px] text-slate-500 mt-3">🔴 items = reason the YouTube player shows black or keeps native controls.</p>
    </section>

    <!-- ③ YouTube Iframe Params -->
    <section class="bg-slate-900 rounded-2xl border border-slate-800 p-5">
        <h2 class="text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-4">③ YouTube Iframe Params (deployed lesson.blade.php)</h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 text-xs mb-4">
            <?php
            $iChecks = [
                ['label'=>'controls=0', 'ok'=>$controlsOff, 'ok_txt'=>'✅ YouTube UI hidden', 'fail_txt'=>'🔴 YouTube native controls visible!'],
                ['label'=>'youtube.com (not nocookie)', 'ok'=>!$isNoCookie, 'ok_txt'=>'✅ Standard domain (reliable)', 'fail_txt'=>'⚠️ youtube-nocookie (may block)'],
                ['label'=>'No urlencode() bug', 'ok'=>!$hasUrlEncode, 'ok_txt'=>'✅ Origin param clean', 'fail_txt'=>'🔴 urlencode() double-encodes URL!'],
                ['label'=>'Plyr CDN version', 'ok'=>$plyrVersion !== '?', 'ok_txt'=>"✅ v$plyrVersion", 'fail_txt'=>'⚠️ Not detected'],
            ];
            foreach ($iChecks as $c):
                $cls = $c['ok'] ? 'bg-emerald-900/30 border-emerald-700 text-emerald-300' : 'bg-red-900/30 border-red-700 text-red-300';
            ?>
            <div class="rounded-xl p-3 border <?= $cls ?> space-y-1">
                <div class="font-bold font-mono"><?= $c['label'] ?></div>
                <div><?= $c['ok'] ? $c['ok_txt'] : $c['fail_txt'] ?></div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="bg-slate-800 rounded-xl p-3 border border-slate-700 font-mono text-[11px] break-all text-green-300">
            <?= e($youtubeLine) ?>
        </div>
    </section>

    <!-- ④ Live Embed Test -->
    <section class="bg-slate-900 rounded-2xl border border-slate-800 p-5">
        <h2 class="text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-3">④ Live YouTube Embed Test (Plyr wrapper, controls=0)</h2>
        <p class="text-xs text-slate-400 mb-4">Plyr controls should be visible. YouTube's own chrome (title bar, watermark, end cards) must be hidden.</p>
        <div class="aspect-video w-full max-w-2xl mx-auto rounded-xl overflow-hidden bg-black border border-slate-700">
            <div class="plyr__video-embed test-player w-full h-full">
                <iframe src="https://www.youtube.com/embed/7HlvvjMiKnc?controls=0&rel=0&modestbranding=1&playsinline=1&enablejsapi=1&disablekb=1&fs=0&iv_load_policy=3"
                    allowfullscreen allow="autoplay; fullscreen; picture-in-picture"
                    class="w-full h-full border-0">
                </iframe>
            </div>
        </div>
    </section>

    <!-- ⑤ Assets -->
    <section class="bg-slate-900 rounded-2xl border border-slate-800 p-5">
        <h2 class="text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-4">⑤ Logo & Favicon Assets</h2>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <?php foreach ($assetInfo as $name => $info): ?>
            <div class="bg-slate-800 rounded-xl p-4 border border-slate-700 text-center space-y-2">
                <?php if ($info['exists']): ?>
                    <img src="<?= $info['url'] ?>" alt="<?= $name ?>" class="w-12 h-12 object-contain mx-auto rounded bg-white/5 p-1">
                    <span class="text-[10px] font-bold text-emerald-300 bg-emerald-900/30 border border-emerald-700 px-2 py-0.5 rounded-full">EXISTS <?= $info['size'] ?></span>
                <?php else: ?>
                    <div class="w-12 h-12 mx-auto rounded bg-red-900/30 flex items-center justify-center text-xl">❌</div>
                    <span class="text-[10px] font-bold text-red-300 bg-red-900/30 border border-red-700 px-2 py-0.5 rounded-full">MISSING</span>
                <?php endif; ?>
                <div class="text-xs font-bold text-slate-300"><?= $name ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- ⑥ Instructor DPs -->
    <section class="bg-slate-900 rounded-2xl border border-slate-800 p-5">
        <h2 class="text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-4">⑥ Teacher DP Delivery Engine</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <?php foreach ($instructors as $u):
                $url = $u->avatarUrl();
                $isSvg = str_starts_with($url, 'data:image/svg');
                $isExternal = str_contains($url, 'ui-avatars.com');
            ?>
            <div class="bg-slate-800 rounded-xl p-4 border border-slate-700 flex items-center gap-3">
                <img src="<?= e($url) ?>" alt="<?= e($u->name) ?>"
                     class="w-12 h-12 rounded-full border-2 <?= $isSvg ? 'border-emerald-500' : ($isExternal ? 'border-red-500' : 'border-blue-500') ?> object-cover flex-shrink-0"
                     loading="eager">
                <div class="min-w-0 flex-1 text-xs space-y-0.5">
                    <div class="font-bold text-sm text-white truncate"><?= e($u->name) ?></div>
                    <div class="text-slate-400"><?= e($u->email) ?></div>
                    <div class="font-mono <?= $isExternal ? 'text-red-400' : 'text-emerald-400' ?>">
                        <?= $isSvg ? '✅ Instant Base64 SVG (0ms)' : ($isExternal ? '🔴 ui-avatars.com — SLOW external!' : '🌐 External URL') ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- ⑦ Video Stream -->
    <section class="bg-slate-900 rounded-2xl border border-slate-800 p-5">
        <h2 class="text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-4">⑦ Lesson Video Streams (latest 10)</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-xs border-collapse">
                <thead><tr class="text-slate-500 border-b border-slate-800 text-left">
                    <th class="p-2">ID</th><th class="p-2">Title</th><th class="p-2">Provider</th><th class="p-2 font-mono">Embed ID</th>
                </tr></thead>
                <tbody class="divide-y divide-slate-800">
                <?php foreach ($lessons as $d): ?>
                <tr>
                    <td class="p-2 text-slate-500 font-mono">#<?= $d['l']->id ?></td>
                    <td class="p-2 font-semibold text-slate-200"><?= e($d['l']->title) ?></td>
                    <td class="p-2"><span class="px-2 py-0.5 rounded text-[10px] font-bold <?= $d['type']==='YouTube'?'bg-red-600/20 text-red-300':($d['type']==='Drive'?'bg-blue-600/20 text-blue-300':'bg-purple-600/20 text-purple-300') ?>"><?= $d['type'] ?></span></td>
                    <td class="p-2 font-mono text-slate-400 max-w-xs truncate"><?= e(substr($d['eid'],0,60)) ?></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>

    <!-- ⑧ DB Counts -->
    <section class="bg-slate-900 rounded-2xl border border-slate-800 p-5">
        <h2 class="text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-4">⑧ Database Counts</h2>
        <?php if ($dbOk): ?>
        <div class="flex flex-wrap gap-3">
            <?php foreach ($counts as $label => $n): ?>
            <div class="bg-slate-800 rounded-xl px-5 py-3 border border-slate-700 text-center">
                <div class="text-2xl font-black text-blue-400"><?= $n ?></div>
                <div class="text-xs text-slate-400"><?= $label ?></div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="text-red-400 text-sm font-mono"><?= e($dbError) ?></div>
        <?php endif; ?>
    </section>

    <!-- Footer -->
    <div class="flex justify-between pb-10 text-xs">
        <a href="/" class="text-slate-500 hover:text-white">← Back to Site</a>
        <a href="/updatedb.php" class="text-yellow-400 hover:text-yellow-200 font-bold">⚡ Run DB Sync →</a>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.plyr.io/3.7.8/plyr.css">
<script src="https://cdn.plyr.io/3.7.8/plyr.polyfilled.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const el = document.querySelector('.test-player');
    if (el && typeof Plyr !== 'undefined') {
        new Plyr(el, {
            controls: ['play-large','play','rewind','fast-forward','progress','current-time','duration','mute','volume','settings','fullscreen'],
            settings: ['speed'],
            youtube: { noCookie: false, rel: 0, showinfo: 0, iv_load_policy: 3, modestbranding: 1, playsinline: 1, controls: 0 }
        });
    }
});
</script>
</body>
</html>
